$(document).ready(function() {

	$('#selectall').click(function() {
		$('input[type="checkbox"]').attr('checked',this.checked);
	});

	function tableRowClick(table, action) {

		$(table).find('tr').on('click', function(e) {

			var target = $(e.target);

			// If the user clicked on an input element, or if this cell contains an input
			// element then do nothing.
			if (target.is(':input') || (target.is('td') && target.find('input').length)) {
				return;
			}

			var id = $(this).data('id');

			if (id) {
				action(id);
				window.location.href = baseUrl+'/admin/editUser/'+id;
			}
		});
	}

	tableRowClick('#admin_users', function(id) {
		window.location.href = baseUrl+'/admin/editUser/'+id;
	});

	tableRowClick('#admin_firms', function(id) {
		window.location.href = baseUrl+'/admin/editFirm/'+id;
	});


	$('#admin_contactlabels li .column_id, #admin_contactlabels li .column_name').click(function(e) {
		e.preventDefault();

		if ($(this).parent().attr('data-attr-id')) {
			window.location.href = baseUrl+'/admin/editContactLabel/'+$(this).parent().attr('data-attr-id');
		}
	});

	handleButton($('#et_save'),function(e) {
		e.preventDefault();

		$('#adminform').submit();
	});

	handleButton($('#et_cancel'),function(e) {
		e.preventDefault();

		var e = window.location.href.split('/');

		var page = false;

		if (parseInt(e[e.length-1])) {
			page = Math.ceil(parseInt(e[e.length-1]) / items_per_page);
		}

		for (var i in e) {
			if (e[i] == 'admin') {
				var object = e[parseInt(i)+1].replace(/^[a-z]+/,'').toLowerCase()+'s';
				window.location.href = baseUrl+'/admin/'+object+(page ? '/'+page : '');
			}
		}
	});

	handleButton($('#et_contact_cancel'),function(e) {
		e.preventDefault();
		history.back();
	});

	handleButton($('#et_add'),function(e) {
		e.preventDefault();

		var e = window.location.href.split('?')[0].split('/');

		for (var i in e) {
			if (e[i] == 'admin') {
				if (e[parseInt(i)+1].match(/ies$/)) {
					var object = ucfirst(e[parseInt(i)+1].replace(/ies$/,'y'));
				} else {
					var object = ucfirst(e[parseInt(i)+1].replace(/s$/,''));
				}
				window.location.href = baseUrl+'/admin/add'+object;
			}
		}
	});

	handleButton($('#et_delete'),function(e) {
		e.preventDefault();

		var e = window.location.href.split('?')[0].split('/');

		for (var i in e) {
			if (e[i] == 'admin') {
				var object = e[parseInt(i)+1].replace(/s$/,'');
			}
		}

		$.ajax({
			'type': 'POST',
			'url': baseUrl+'/admin/delete'+ucfirst(object)+'s',
			'data': $('#admin_'+object+'s').serialize(),
			'success': function(html) {
				if (html == '1') {
					window.location.reload();
				} else {
					new OpenEyes.Dialog.Alert({
						content: "One or more "+object+"s could not be deleted as they are in use."
					}).open();
				}
			}
		});
	});

	handleButton($('#lookup_user'),function(e) {
		e.preventDefault();

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/admin/lookupUser?username='+$('#User_username').val(),
			'success': function(resp) {
				m = resp.match(/[0-9]+/);
				if (m) {
					window.location.href = baseUrl+'/admin/editUser/'+m[0];
				} else {
					new OpenEyes.Dialog.Alert({
						content: "User not found"
					}).open();
				}
			}
		});
	});

	handleButton($('#et_add_label'),function(e) {
		e.preventDefault();

	});
});