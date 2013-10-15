<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the service class for syncing
 */
class SyncService
{
	public $server = null;
	public $table_count = 0;
	public $position = 0;

	public function __construct($server=null)
	{
		$this->server = $server;
	}

	public function sync()
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$sync_date = date('Y-m-d H:i:s');

		$local_core_tables = $this->getCoreTableListInSyncOrder();
		$remote_core_tables = $this->getRemoteCoreTableListInSyncOrder();

		$this->table_count = count($local_core_tables) + count($remote_core_tables) + 20;

		$pushed = $pulled = 0;

		foreach ($local_core_tables as $table) {
			$this->status("Pushing: $table");
			$pushed += $this->push($table);
		}

		foreach ($remote_core_tables as $table) {
			$this->status("Pulling: $table");
			$pulled += $this->pull($table);
		}

		$this->status("Pushing: event");
		$pushed += $this->pushEvents();

		$this->status("Pulling: event");
		$pulled += $this->pullEvents();

		$this->status("Pushing: sync_remap");
		$pushed += $this->push('sync_remap');

		$this->status("Pulling: sync_remap");
		$pulled += $this->pull('sync_remap');

		foreach (array('audit_action','audit_ipaddr','audit_model','audit_module','audit_server','audit_type','audit_useragent','audit') as $table) {
			$this->status("Pushing: $table");
			$pushed += $this->push($table);
		}

		foreach (array('audit_action','audit_ipaddr','audit_model','audit_module','audit_server','audit_type','audit_useragent','audit') as $table) {
			$this->status("Pushing: $table");
			$pulled += $this->pull($table);
		}

		$this->server->last_sync = $sync_date;
		if (!$this->server->save()) {
			throw new Exception("Unable to save sync_server: ".print_r($this->server->getErrors(),true));
		}

		$this->status("Sync completed, $pushed pushed $pulled pulled. date:[".date('jS F Y, H:i',strtotime($this->server->last_sync))."]");
	}

	public function status($message)
	{
		OELog::log("[sync] $message");

		$this->position++;

		$pc = round($this->position / ($this->table_count / 100));
		if ($pc > 100) {$pc = 100;}

		echo "event: status\n";
		echo "data: $message".($pc <100 ? " - $pc%" : "")."\n\n";
		flush();
		ob_flush();
	}

	public function push($table)
	{
		$data = $this->getItems($table,$this->server->last_sync,'PUSH');

		if (empty($data)) {
			return;
		}

		if ($table == 'protected_file') {
			$count = 0;

			foreach ($data as $dataItem) {
				$resp = $this->request(array(
					'type' => 'PUSH',
					'table' => $table,
					'data' => array($dataItem),
				));

				$count += $resp['message']['inserted'];
				$count += $resp['message']['updated'];
			}

			return $count;
		}

		$resp = $this->request(array(
			'type' => 'PUSH',
			'table' => $table,
			'data' => $data,
		));

		return ($resp['message']['inserted'] + $resp['message']['updated']);
	}

	public function pushEvents()
	{
		$events = $this->getItems('event',$this->server->last_sync,'PUSH');

		if (empty($events)) {
			return array('received'=>0,'inserted'=>0,'updated'=>0,'not-modified'=>0);
		}

		$resp = $this->request(array(
			'type' => 'PUSH',
			'table' => 'event',
			'data' => $events,
		));

		return ($resp['message']['inserted'] + $resp['message']['updated']);
	}

	public function wrapElements($event)
	{
		$elements = array();

		$criteria = new CDbCriteria;
		$criteria->addCondition('event_type_id=:event_type_id');
		$criteria->params[':event_type_id'] = $event['event_type_id'];
		$criteria->order = 'display_order asc';

		foreach (ElementType::model()->findAll($criteria) as $element_type) {
			$class = $element_type->class_name;
			$table = $class::model()->tableName();

			if ($element = Yii::app()->db->createCommand()->select("*")->from($table)->where("event_id = :event_id",array(":event_id"=>$event['id']))->queryRow()) {
				$elements[] = $this->wrapElement($table, $element);
			}
		}

		return $elements;
	}

	public function wrapElement($table, $element)
	{
		$cyclic = $this->fixCyclicDependencies($table, $element, $this->getRelatedItems($table, $element));

		return array(
			'table' => $table,
			'data' => $cyclic['element'],
			'related' => $cyclic['related'],
			'defer' => $cyclic['defer'],
		);
	}

	public function getRelatedItems($table, $element, $ignore=array())
	{
		$related = array();

		if (preg_match('/^et_/',$table)) {
			foreach (Yii::app()->db->getSchema()->getTables() as $_table) {
				foreach ($_table->foreignKeys as $field => $key) {
					if ($key[0] == $table && !in_array($_table->name,$ignore)) {
						$data = Yii::app()->db->createCommand()->select("*")->from($_table->name)->where("$field = :$field",array(":$field"=>$element['id']))->queryAll();

						if (!empty($data)) {
							foreach ($data as $item) {
								$related[] = array(
									'type' => 'reverse',
									'table' => $_table->name,
									'data' => $item,
									'related' => $this->getRelatedItems($_table->name, $item, $ignore),
								);
							}
						}
					}
				}
			}
		}

		foreach (Yii::app()->db->getSchema()->getTable($table)->foreignKeys as $field => $key) {
			if (preg_match('/^oph/',$key[0]) && !in_array($key[0],$ignore)) {
				if ($element[$field] !== null) {
					if ($relatedItem = Yii::app()->db->createCommand()->select("*")->from($key[0])->where("id=?",array($element[$field]))->queryRow()) {
						$related[] = array(
							'type' => 'foreign',
							'table' => $key[0],
							'data' => $relatedItem,
							'related' => $this->getRelatedItems($key[0], $relatedItem, $ignore),
						);
					}
				}
			}
		}

		return $related;
	}

	public function fixCyclicDependencies($element_table, $element, $related) {
		$defer = array();

		foreach ($related as $i => $item) {
			if ($item['type'] == 'foreign') {
				$table = Yii::app()->db->getSchema()->getTable($element_table);

				foreach ($table->foreignKeys as $field => $key) {
					if ($key[0] == $item['table'] && $element[$field] == $item['data']['id']) {
						// found the element table key that points to the foreign table, now we check to see if the foreign table has a key that points back to the element. if so we need to remove it from the foreign list and defer the key in the element that points to it.

						$foreignTable = Yii::app()->db->getSchema()->getTable($item['table']);

						foreach ($foreignTable->foreignKeys as $foreignField => $key) {
							if ($key[0] == $element_table && $item['data'][$foreignField] == $element['id']) {
								unset($related[$i]);
								$defer[$field] = $element[$field];
								$element[$field] = null;
							}
						}
					}
				}
			}
		}

		return array(
			'element' => $element,
			'related' => $related,
			'defer' => $defer,
		);
	}

	public function wrapDeletes($event, $last_sync)
	{
		$deletes = array();

		$event_type = EventType::model()->findByPk($event['event_type_id']);

		foreach (Yii::app()->db->createCommand()->select("*")->from("delete_log")->where("event_id = ? and created_date > ?",array($event['id'],$last_sync))->order("created_date asc")->queryAll() as $dl) {
			if (preg_match('/^et_'.strtolower($event_type->class_name).'_/',$dl['item_table']) || preg_match('/^'.strtolower($event_type->class_name).'_/',$dl['item_table'])) {
				$deletes[] = array(
					'table' => $dl['item_table'],
					'id' => $dl['item_id'],
					'datetime' => $dl['created_date'],
				);
			}
		}

		return $deletes;
	}

	public function wrapReferenceTables($event_type, $last_sync) {
		$ref_tables = $this->getReferenceTables($event_type->class_name);

		$reference = array();

		foreach ($ref_tables as $table) {
			if ($data = $this->wrapReferenceTable($table, $last_sync)) {
				$reference[$table] = $data;
			}
		}

		return $reference;
	}

	public function wrapReferenceTable($table, $last_sync) {
		$reference = array();

		$_table = Yii::app()->db->getSchema()->getTable($table);

		foreach ($_table->foreignKeys as $field => $key) {
			if ($key[0] == $table) {
				// different ballgame detected, this is a job for wrapSelfReferentialReferenceTable()
				return $this->wrapSelfReferentialReferenceTable($table, $last_sync, $field);
			}
		}

		foreach (Yii::app()->db->createCommand()->select("*")->from($_table->name)->where("last_modified_date > ?",array($last_sync))->order("last_modified_date asc")->queryAll() as $row) {
			$reference[] = array(
				'table' => $_table->name,
				'data' => $row,
				'related' => $this->getRelatedItems($_table->name, $row),
			);
		}

		return $reference;
	}

	public function wrapSelfReferentialReferenceTable($table, $last_sync, $field, $parent = null, $return=array()) {
		if ($parent == null) {
			$data = Yii::app()->db->createCommand()->select("*")->from($table)->where("$field is null")->queryAll();
		} else {
			$data = Yii::app()->db->createCommand()->select("*")->from($table)->where("$field =:$field",array(":$field"=>$parent))->queryAll();
		}

		foreach ($data as $row) {
			if (strtotime($row['last_modified_date']) > strtotime($last_sync)) {
				$return[] = array(
					'table' => $table,
					'data' => $row,
					'related' => $this->getRelatedItems($table, $row, array($table)),
				);
			}
			$return = $this->wrapSelfReferentialReferenceTable($table, $last_sync, $field, $row['id'], $return);
		}

		return $return;
	}

	public function pull($table)
	{
		$resp = $this->request(array(
			'type' => 'PULL',
			'table' => $table,
		));

		if ($resp['status'] != 'ok') {
			die("Failed: {$resp['message']}\n");
		}

		$resp = $this->receiveItems($table, $resp['message']['data'], "PULL");

		return ($resp['inserted'] + $resp['updated']);
	}

	public function pullEvents()
	{
		$resp = $this->request(array(
			'type' => 'PULL',
			'table' => 'event',
		));

		if ($resp['status'] != 'ok') {
			die("Failed: {$resp['message']}\n");
		}

		$resp = $this->receiveItems('event', $resp['message']['data'], "PULL");

		return ($resp['inserted'] + $resp['updated']);
	}

	public function inSync()
	{
		$response = $this->request(json_encode(array(
			'key' => $this->server->key,
			'timestamp' => $this->server->last_sync,
			'type' => 'STATUS',
		)));

		if (!$resp = @json_decode($response,true)) {
			$this->messages[] = "unable to parse server response";
			return false;
		}

		if (!$resp['sync_status']) {
			$server->sync_status = 0;
			if (!$server->save()) {
				throw new Exception("Unable to mark server out of sync: ".print_r($server->getErrors(),true));
			}
		}

		return $resp['sync_status'];
	}

	public function request($request)
	{
		$request['key'] = $this->server->key;
		$request['last_sync'] = $this->server->last_sync;

		$c = curl_init();
		curl_setopt($c,CURLOPT_URL,"http://{$this->server->hostname}/sync/csrf");
		curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
		$csrf = trim(curl_exec($c));

		curl_setopt($c,CURLOPT_URL,"http://{$this->server->hostname}/sync/request");
		curl_setopt($c,CURLOPT_POST,true);
		curl_setopt($c,CURLOPT_POSTFIELDS,"data=".rawurlencode(json_encode($request))."&YII_CSRF_TOKEN=".$csrf);

		if (isset(Yii::app()->params['sync_http_username']) && isset(Yii::app()->params['sync_http_password'])) {
			curl_setopt($c,CURLOPT_USERPWD,Yii::app()->params['sync_http_username'].":".Yii::app()->params['sync_http_password']);
		}

		$data = curl_exec($c);

		if (!$resp = @json_decode($data,true)) {
			if (preg_match('/Authorization Required/i',$data)) {
				die("http authorisation required");
			} else {
				die($data);
				die("unable to parse server response");
			}
		}

		if ($resp['status'] != 'ok') {
			die("Request failed: {$resp['message']}");
		}

		return $resp;
	}

	public function getCoreTableListInSyncOrder($last_sync=false)
	{
		if (!$last_sync && $this->server) {
			$last_sync = $this->server->last_sync;
		}

		$tables = array('user');
		$exclude = array('authitem','authitemchild','authassignment','event','user_session','tbl_migration','audit','audit_action','audit_ipaddr','audit_model','audit_module','audit_server','audit_type','audit_useragent','sync_server','sync_remap');

		foreach (Yii::app()->db->getSchema()->getTables() as $table) {
			if (!preg_match('/^et_oph/',$table->name) && !preg_match('/^oph/',$table->name)) {
				if (!in_array($table->name,$tables)) {
					$tables = $this->getDescendentDependencies($table, $tables, $exclude, $last_sync);

					if (!in_array($table->name,$tables) && !in_array($table->name,$exclude)) {
						if ($this->hasChanged($table->name,$last_sync)) {
							$tables[] = $table->name;
						}
					}
				}
			}
		}

		return $tables;
	}

	public function getDescendentDependencies($table, $tables, $exclude, $last_sync) {
		foreach ($this->getDependencies($table,$tables) as $deptable) {
			if (!in_array($deptable,$tables) && !in_array($deptable,$exclude)) {
				if ($this->hasChanged($deptable,$last_sync)) {
					$_deptable = Yii::app()->db->getSchema()->getTable($deptable);
					$tables = $this->getDescendentDependencies($_deptable, $tables, $exclude, $last_sync);
					$tables[] = $deptable;
				}
			}
		}

		return $tables;
	}

	public function hasChanged($table, $last_sync) {
		return Yii::app()->db->createCommand()->select("*")->from($table)->where("last_modified_date > :last_sync",array(":last_sync" => $last_sync))->queryRow();
	}

	public function getDependencies($table, $ignore)
	{
		$deps = array();

		foreach ($table->foreignKeys as $key) {
			if (!in_array($key[0],$deps) && !in_array($key[0],$ignore)) {
				$deps[] = $key[0];

				if ($table->name != $key[0]) {
					foreach ($this->getDependencies($this->getTableObject($key[0]),$ignore) as $deptable) {
						if (!in_array($deptable,$deps)) {
							$deps[] = $deptable;
						}
					}
				}
			}
		}

		return $deps;
	}

	public function getReferenceTables($module_class) {
		$element_tables = $this->getElementTables($module_class);

		$ref_tables = array();

		foreach (Yii::app()->db->getSchema()->getTables() as $table) {
			if (preg_match('/^'.strtolower($module_class).'_/',$table->name) && !in_array($table->name,$element_tables)) {
				$ref_tables[] = $table->name;
			}
		}

		return $ref_tables;
	}

	public function getElementTables($module_class) {
		$tables = array();

		foreach (Yii::app()->db->getSchema()->getTables() as $table) {
			if (preg_match('/^et_'.strtolower($module_class).'_/',$table->name)) {
				$tables = $this->getDependencies2($module_class, $table->name, $tables);
			}
		}

		return $tables;
	}

	public function getDependencies2($module_class, $table, $tables) {
		if (!$_table = Yii::app()->db->getSchema()->getTable($table)) {
			die("Fatal error, table not found: $table\n");
		}

		foreach ($_table->foreignKeys as $key) {
			if (!in_array($key[0],$tables) && preg_match('/^'.strtolower($module_class).'_/',$key[0])) {
				$tables[] = $key[0];
				$tables = $this->getDependencies2($module_class, $key[0], $tables);
			}
		}

		if (preg_match('/^et_/',$table)) {
			foreach (Yii::app()->db->getSchema()->getTables() as $table2) {
				foreach ($table2->foreignKeys as $key) {
					if ($key[0] == $table) {
						if (!in_array($table2->name,$tables) && preg_match('/^'.strtolower($module_class).'_/',$table2->name)) {
							$tables[] = $table2->name;
							$tables = $this->getDependencies2($module_class, $table2->name, $tables);
						}
					}
				}
			}
		}

		return $tables;
	}

	public function getTableObject($table)
	{
		return Yii::app()->db->getSchema()->getTable($table);
	}

	public function receiveItems($table, $data, $method)
	{
		$resp = array(
			'received' => count($data),
			'inserted' => 0,
			'updated' => 0,
			'not-modified' => 0,
		);

		$receiveMethod = "receiveItems_$table";

		if (method_exists($this,$receiveMethod)) {
			return $this->$receiveMethod($resp, $data, $method);
		}

		foreach ($data as $item) {
			$id = @$item['id'];

			if ($id && $local = Yii::app()->db->createCommand()->select("*")->from($table)->where("id = :id",array('id'=>$id))->queryRow()) {
				if (strtotime($item['last_modified_date']) > strtotime($local['last_modified_date']) ||
					($method == 'PULL' && $table == 'episode' && $item['deleted'])) {
					unset($item['id']);

					Yii::app()->db->createCommand()->update($table, $item, "id = :id", array(":id" => $id));
					$resp['updated']++;
				} else {
					$resp['not-modified']++;
				}
			} else if (!$this->wasMoreRecentlyDeleted($table, $item)) {
				Yii::app()->db->createCommand()->insert($table, $item);
				$resp['inserted']++;
			}
		}

		return $resp;
	}

	public function wasMoreRecentlyDeleted($table, $item)
	{
		if ($dl = DeleteLog::model()->find('item_table=? and item_id=?',array($table,$item['id']))) {
			if (strtotime($dl->created_date) > strtotime($item->last_modified_date)) {
				return true;
			}
		}

		return false;
	}

	public function receiveItems_proc_opcs_assignment($resp, $data, $method)
	{
		foreach ($data as $item) {
			if (!$local = Yii::app()->db->createCommand()->select("*")->from('proc_opcs_assignment')->where("proc_id=:proc_id and opcs_code_id=:opcs_code_id",array(':proc_id'=>$item['proc_id'],':opcs_code_id'=>$item['opcs_code_id']))->queryRow()) {
				Yii::app()->db->createCommand()->insert($table, $item);
				$resp['inserted']++;
			}
		}

		return $resp;
	}

	public function receiveItems_delete_log($resp, $data, $method)
	{
		foreach ($data as $item) {
			if ($item['event_id'] === null) {
				if ($local = Yii::app()->db->createCommand()->select("*")->from($item['item_table'])->where("id=:id",array(":id"=>$item['item_id']))->queryRow()) {
					if (strtotime($local['last_modified_date']) <= strtotime($item['created_date'])) {
						Yii::app()->db->createCommand()->delete($item['item_table'],"id=:id",array(":id"=>$item['item_id']));
					}
				}

				if (!$local = Yii::app()->db->createCommand()->select("*")->from('delete_log')->where('id=:id',array(':id'=>$item['id']))->queryRow()) {
					Yii::app()->db->createCommand()->insert('delete_log',$item);
					$resp['inserted']++;
				} else {
					$resp['not-modified']++;
				}
			}
		}

		return $resp;
	}

	public function receiveItems_episode($resp, $data, $method)
	{
		foreach ($data as $item) {
			if ($local = Yii::app()->db->createCommand()->select("*")->from('episode')->where("id=:id",array(":id"=>$item['id']))->queryRow()) {
				if (strtotime($local['last_modified_date']) <= strtotime($item['last_modified_date'])) {
					Yii::app()->db->createCommand()->update('episode',$item,"id=:id",array(":id"=>$item['id']));
					$resp['updated']++;
				} else {
					$resp['not-modified']++;
				}
			} else {
				Yii::app()->db->createCommand()->insert('episode',$item);
				$resp['inserted']++;

				if ($method == 'PUSH') {
					$subspecialty_id = Yii::app()->db->createCommand()
						->select("s.*")
						->from("subspecialty s")
						->join("service_subspecialty_assignment ssa","ssa.subspecialty_id = s.id")
						->join("firm f","f.service_subspecialty_assignment_id = ssa.id")
						->where("f.id = :firm_id",array(":firm_id" => $item['firm_id']))
						->queryScalar();

					if ($existingEpisode = Yii::app()->db->createCommand()
						->select("ep.*")
						->from("episode ep")
						->join("firm f","ep.firm_id = f.id")
						->join("service_subspecialty_assignment ssa","f.service_subspecialty_assignment_id = ssa.id")
						->where("subspecialty_id = :subspecialty_id and deleted = :deleted and ep.patient_id = :patient_id and ep.id != :episode_id",array(":subspecialty_id" => $subspecialty_id, ":deleted" => 0, ":patient_id" => $item['patient_id'],":episode_id" => $item['id']))
						->queryRow()) {

						if (strtotime($item['created_date']) < strtotime($existingEpisode['created_date'])) {
							// Remap
							Yii::app()->db->createCommand()->update('event',array('episode_id' => $item['id']),"episode_id = :id",array(":id" => $existingEpisode['id']));
							Yii::app()->db->createCommand()->update('audit',array('episode_id' => $item['id']),"episode_id = :id",array(":id" => $existingEpisode['id']));
							Yii::app()->db->createCommand()->update('episode',array('deleted' => 1),"id=:id",array(":id" => $existingEpisode['id']));

							$sr = new SyncRemap;
							$sr->old_episode_id = $existingEpisode['id'];
							$sr->new_episode_id = $item['id'];

							if (!$sr->save()) {
								throw new Exception("Unable to save sync_remap item: ".print_r($sr->getErrors(),true));
							}
						} else {
							// Remap
							Yii::app()->db->createCommand()->update('event',array('episode_id' => $existingEpisode['id']),"episode_id = :id",array(":id" => $item['id']));
							Yii::app()->db->createCommand()->update('audit',array('episode_id' => $existingEpisode['id']),"episode_id = :id",array(":id" => $item['id']));
							Yii::app()->db->createCommand()->update('episode',array('deleted' => 1),"id=:id",array(":id" => $item['id']));

							$sr = new SyncRemap;
							$sr->old_episode_id = $item['id'];
							$sr->new_episode_id = $existingEpisode['id'];

							if (!$sr->save()) {
								throw new Exception("Unable to save sync_remap item: ".print_r($sr->getErrors(),true));
							}
						}
					}
				}
			}
		}

		return $resp;
	}

	public function receiveItems_event($resp, $data, $method)
	{
		$reference = $data['_reference'];
		unset($data['_reference']);

		foreach ($data as $i => $event) {
			$_data = $event;
			foreach ($_data as $key => $value) {
				if ($key[0] == '_') {
					unset($_data[$key]);
				}
			}

			if ($method == 'PUSH') {
				if ($sr = SyncRemap::model()->find('old_episode_id=?',array($_data['episode_id']))) {
					$_data['episode_id'] = $sr->new_episode_id;
				}
			}

			if ($local = Yii::app()->db->createCommand()->select("*")->from("event")->where("id=:id",array(":id"=>$event['id']))->queryRow()) {
				if (strtotime($_data['last_modified_date']) > strtotime($local['last_modified_date'])) {
					Yii::app()->db->createCommand()->update("event",$_data,"id=:id",array(":id"=>$event['id']));
					$resp['updated']++;
				} else {
					$resp['not-modified']++;
				}
			} else {
				Yii::app()->db->createCommand()->insert("event",$_data);
				$resp['inserted']++;
			}

			$this->processRelatedData($event['_elements']);
			$this->processDeletes($event['_deletes']);

			if ($method == 'PUSH' && $event['episode_id'] != $_data['episode_id']) {
				// Ensure new episode_id is passed back when the PULL is issued
				Yii::app()->db->createCommand()->update("event",array('last_modified_date'=>date('Y-m-d H:i:s',strtotime($_data['last_modified_date']) +1)),"id=:id",array(":id"=>$event['id']));
			}
		}

		$this->processReferenceData($reference);

		return $resp;
	}

	public function processReferenceData($reference)
	{
		foreach ($reference as $module_class => $referenceData) {
			foreach ($referenceData as $table => $items) {
				$this->processRelatedData($items);
			}
		}
	}

	public function processRelatedData($related, $type=null)
	{
		foreach ($related as $item) {
			$was_modified = false;

			!empty($item['related']) && $this->processRelatedData($item['related'], ($type===null ? 'foreign' : $type));

			if (@$item['type'] == $type) {
				if (!isset($item['data']['id'])) {
					echo $item['table']."\n";
					print_r($item['data'],true);
					exit;
				}

				if ($local = Yii::app()->db->createCommand()->select("*")->from($item['table'])->where("id=:id",array(":id"=>$item['data']['id']))->queryRow()) {
					if (strtotime($item['data']['last_modified_date']) > strtotime($local['last_modified_date'])) {
						Yii::app()->db->createCommand()->update($item['table'],$item['data'],"id=:id",array(":id"=>$item['data']['id']));
						$was_modified = true;
					}
				} else {
					Yii::app()->db->createCommand()->insert($item['table'],$item['data']);
					$was_modified = true;
				}
			}

			!empty($item['related']) && $this->processRelatedData($item['related'], ($type===null ? 'reverse' : $type));

			if ($type === null && $was_modified && !empty($item['defer'])) {
				Yii::app()->db->createCommand()->update($item['table'],$item['defer'],"id=:id",array(":id"=>$item['data']['id']));
			}
		}
	}

	public function processDeletes($deletes)
	{
		foreach ($deletes as $delete) {
			if ($local = Yii::app()->db->createCommand()->select("*")->from($delete['table'])->where("id=:id",array(":id"=>$delete['id']))->queryRow()) {
				if (strtotime($delete['datetime']) > strtotime($local['last_modified_date'])) {
					$this->nuke($delete['table'],$delete['id']);
				}
			}
		}
	}

	// Delete row and anything that points to it recursively
	public function nuke($table, $id)
	{
		foreach (Yii::app()->db->getSchema()->getTables() as $_table) {
			if ($_table->name != $table) {
				foreach ($_table->foreignKeys as $field => $key) {
					if ($key[0] == $table) {
						foreach (Yii::app()->db->createCommand()->select("*")->from($_table->name)->where("$field = :$field",array(":$field" => $id))->queryAll() as $row) {
							$this->nuke($_table->name,$row['id']);
						}
					}
				}
			}
		}

		Yii::app()->db->createCommand()->delete($table,"id=:id",array(":id"=>$id));
	}

	public function getItems($table, $last_sync, $method)
	{
		$items = Yii::app()->db->createCommand()->select("*")->from($table)->where("last_modified_date > ?",array($last_sync))->order("last_modified_date asc")->queryAll();

		if ($table == 'event') {
			foreach ($items as $i => $item) {
				$items[$i]['_elements'] = $this->wrapElements($item);
				$items[$i]['_deletes'] = $this->wrapDeletes($item, $last_sync);
				$items[$i]['_episode'] = Yii::app()->db->createCommand()->select("*")->from("episode")->where("id=:id",array(":id"=>$item['episode_id']))->queryRow();
			}

			$items['_reference'] = array();

			foreach (EventType::model()->findAll() as $item_type) {
				if ($data = $this->wrapReferenceTables($item_type, $last_sync)) {
					$items['_reference'][$item_type->class_name] = $data;
				}
			}
		}

		if ($table == 'protected_file') {
			foreach ($items as $i => $item) {
				$items[$i]['_data'] = base64_encode(file_get_contents("protected/files/".$item['uid'][0]."/".$item['uid'][1]."/".$item['uid'][2]."/".$item['uid']));
			}
		}

		return $items;
	}

	public function getRemoteCoreTableListInSyncOrder() {
		$resp = $this->request(array(
			'type' => 'TABLES',
		));

		if ($resp['status'] != 'ok') {
			die("Failed: {$resp['message']}\n");
		}

		return $resp['message']['data'];
	}

	public function receiveItems_sync_remap($resp, $data, $method) {
		foreach ($data as $sync_remap) {
			Yii::app()->db->createCommand()->update('audit',array('episode_id'=>$sync_remap['new_episode_id']),"episode_id=:episode_id",array(":episode_id"=>$sync_remap['old_episode_id']));
			Yii::app()->db->createCommand()->update('event',array('episode_id'=>$sync_remap['new_episode_id']),"episode_id=:episode_id",array(":episode_id"=>$sync_remap['old_episode_id']));
			Yii::app()->db->createCommand()->update('episode',array('deleted'=>1),"id=:id",array(":id"=>$sync_remap['old_episode_id']));
		}
	}

	public function receiveItems_protected_file($resp, $data, $method) {
		foreach ($data as $protectedFile) {
			$_data = $protectedFile;
			foreach ($_data as $key => $value) {
				if ($key[0] == '_') {
					unset($_data[$key]);
				}
			}

			if ($local = Yii::app()->db->createCommand()->select("*")->from("protected_file")->where("id=:id",array(":id"=>$protectedFile['id']))->queryRow()) {
				if (strtotime($_data['last_modified_date']) > strtotime($local['last_modified_date'])) {
					Yii::app()->db->createCommand()->update("protected_file",$_data,"id=:id",array(":id"=>$protectedFile['id']));
					$resp['updated']++;
				} else {
					$resp['not-modified']++;
				}
			} else {
				Yii::app()->db->createCommand()->insert("protected_file",$_data);
				$resp['inserted']++;
			}

			$path = "protected/files/".$protectedFile['uid'][0]."/".$protectedFile['uid'][1]."/".$protectedFile['uid'][2]."/".$protectedFile['uid'];

			if (!file_exists(dirname($path))) {
				mkdir(dirname($path),0755,true);
			}

			file_put_contents($path, base64_decode($protectedFile['_data']));
		}

		return $resp;
	}
}