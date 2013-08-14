/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

function OphCoTherapyapplication_AddDiagnosis(disorder_id, name) {
	$('#disorder_id').val(disorder_id);
	$('#clinical-create').submit();
}

$(document).ready(function () {
	$(this).delegate('#add-new', 'click', function () {
		$('#add-new-form').removeClass('hidden');
	});

	$('.OphCoTherapyapplication_DecisionTree').delegate('.add_node', 'click', function (e) {
		var data = {};
		data['dt_id'] = $(this).attr('data-dt_id');
		if ($(this).attr('data-parent_id')) {
			data['parent_id'] = $(this).attr('data-parent_id');
		}

		$.ajax({
			'type': 'GET',
			//TODO: fix this URL to be generated by the code
			'url': '/OphCoTherapyapplication/admin/createDecisionTreeNode/' + data['dt_id'] + '?' + $.param(data),
			'success': function (data) {
				if (data.length > 0) {
					var popup = $("<div></div>");
					popup.html(data).dialog({
						height: 400,
						minHeight: 400,
						width: 700,
						title: 'Decision Tree Node',
						modal: true,
					}).dialog('open');

				}
			}
		});

		e.preventDefault();
	});

	$('.OphCoTherapyapplication_DecisionTree').delegate('.edit_node', 'click', function (e) {
		var node_id = $(this).attr('data-node_id');
		var url = '/OphCoTherapyapplication/admin/updateDecisionTreeNode/' + node_id;
		$('<iframe style="min-width: 95%"></iframe>').attr('src', url).dialog({
			height: 400,
			width: 700,
			title: 'Decision Tree Node',
			modal: true,
			close: function (ev, ui) {
				window.location.reload();
			}
		}).dialog('open');

		e.preventDefault();
	});

	$('.OphCoTherapyapplication_DecisionTreeNode').delegate('.add_rule', 'click', function (e) {
		var node_id = $(this).attr('data-node_id');

		$.ajax({
			'type': 'GET',
			//TODO: fix this URL to be generated by the code
			'url': '/OphCoTherapyapplication/admin/createDecisionTreeNodeRule/' + node_id,
			'success': function (data) {
				if (data.length > 0) {
					var popup = $("<div></div>");
					popup.html(data).dialog({
						height: 400,
						minHeight: 400,
						width: 700,
						title: 'Decision Tree Node Rule',
						modal: true
					}).dialog('open');

				}
			}
		});

		e.preventDefault();
	});

	$('.OphCoTherapyapplication_DecisionTreeNode').delegate('.edit_rule', 'click', function (e) {
		var rule_id = $(this).attr('data-rule_id');

		$.ajax({
			'type': 'GET',
			//TODO: fix this URL to be generated by the code
			'url': '/OphCoTherapyapplication/admin/updateDecisionTreeNodeRule/' + rule_id,
			'success': function (data) {
				if (data.length > 0) {
					var popup = $("<div></div>");
					popup.html(data).dialog({
						height: 400,
						minHeight: 400,
						width: 700,
						title: 'Decision Tree Node Rule',
						modal: true
					}).dialog('open');

				}
			}
		});

		e.preventDefault();
	});

	$('#OphCoTherapyapplication_adminform .response_type').delegate('select', 'change', function (e) {
		var selected = $(this).val();
		var dt;

		$(this).find('option').each(function () {
			if ($(this).val() == selected) {
				dt = $(this).data('datatype');
				return false;
			}
		});

		var template = $('#template_default_value_' + dt).html();
		if (!template) {
			template = $('#template_default_value_default').html();
		}

		var data = {
			"name": "OphCoTherapyapplication_DecisionTreeNode[default_value]",
			"id": "OphCoTherapyapplication_DecisionTreeNode_default_value",
		};
		var inp = Mustache.render(template, data);
		$('#OphCoTherapyapplication_DecisionTreeNode_default_value').replaceWith(inp);
		e.preventDefault();
	});

	// add file fields for file collection admin
	$('#div_OphCoTherapyapplication_FileCollection_file').delegate('.addFile', 'click', function (e) {
		var keys = $('.OphCoTherapyapplication_FileCollection_file').map(function (index, el) {
			return parseInt($(el).attr('data-key'));
		}).get();
		// ensure we start at zero
		keys.push(-1);
		var key = Math.max.apply(null, keys) + 1;

		$('<input type="file" class="OphCoTherapyapplication_FileCollection_file" name="OphCoTherapyapplication_FileCollection_files[' + key + ']" data-key="' + key + '" /><br />').insertBefore($(this));
	});

	$('#div_OphCoTherapyapplication_FileCollection_file').delegate('.OphCoTherapyapplication_FileCollection_file', 'change', function (e) {
		var error = false;
		if (this.files.length > parseInt($(this).data('count-limit'))) {
			new OpenEyes.Dialog.Alert({
				content: 'Cannot have more than ' + $(this).data('count-limit') + ' files'
			}).open();
			error = true;
		}
		else {
			var mx_filesize = parseInt($(this).data('max-filesize'));
			var mx_total = parseInt($(this).data('total-max-size'));
			var total = 0;
			for (var i = 0; i < this.files.length; i++) {
				var file = this.files[i];
				if (file.size > mx_filesize) {
					new OpenEyes.Dialog.Alert({
						content: 'File ' + file.name + ' is too large'
					}).open();
					error = true;
					break;
				}
				total += file.size;
				if (total > mx_total) {
					new OpenEyes.Dialog.Alert({
						content: 'Total size of files is too large'
					}).open();
					error = true;
					break;
				}
			}
		}

		if (error) {
			$(this).val('');
		}

	});

	$('.removeFile').live('click',function() {
		$('#remove_file_id').val($(this).parent().data('file-id'));

		$('#confirm_remove_file_dialog').dialog({
			resizable: false,
			modal: true,
			width: 560
		});

		return false;
	});

	$('button.btn_remove_file').click(function() {
		$("#confirm_remove_file_dialog").dialog("close");

		var file_id = $('#remove_file_id').val();

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/OphCoTherapyapplication/admin/removeFileCollection_File?filecollection_id='+ filecollection_id +'&file_id='+file_id,
			'dataType': 'json',
			'success': function(resp) {
				if (resp.success) {
					var row = $('#currentFiles li[data-file-id="' + file_id + '"]');
					row.remove();
				} else {
					new OpenEyes.Dialog.Alert({
						content: "Sorry, an internal error occurred and we were unable to remove the file.\n\nPlease contact support for assistance."
					}).open();
				}
			},
			'error': function() {
				new OpenEyes.Dialog.Alert({
					content: "Sorry, an internal error occurred and we were unable to remove the file.\n\nPlease contact support for assistance."
				}).open();
			}
		});

		return false;
	});

	$('button.btn_cancel_remove_file').click(function() {
		$("#confirm_remove_file_dialog").dialog("close");
		return false;
	});

	$('.sortable').sortable({
		update: function (event, ui) {
			var ids = [];
			$('div.sortable').children('li').map(function () {
				ids.push($(this).attr('data-attr-id'));
			});
			$.ajax({
				'type': 'POST',
				'url': OphCoTherapyapplication_sort_url,
				'data': {order: ids},
				'success': function (data) {
					new OpenEyes.Dialog.Alert({
						content: 'Re-ordered'
					}).open();
				}
			});

		}
	});
});
