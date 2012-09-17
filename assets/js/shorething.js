// JavaScript Document
$(document).ready(function(){
	prepareAssignmentDrags();						   
	prepareFolderMapButtons();						   
	prepareFolderUnmapButtons();
});

function prepareAssignmentDrags() {
	$('tbody tr').draggable({ 
		cursor: 'move',
		cursorAt: { top: -5, left: -5 }, 
		handle: ".sequencemover",
		helper: function(event) {
			var label = $(this).children('td:eq(1)').text();
			return $("<div class='alert alert-info'>"+label+"</div>"); },
		start: function(event,ui) {
			$(droppableBlankrow)
				.insertBefore($(this).siblings('tr').not($(this).next())).droppable({
					tolerance: 'touch',																																			
					drop: function (event,ui) {
						// apply the reorder via ajax
						$.post( baseurl+"teachers/assignmentsEngine", 
							   { action:'reorder', mover_id:$(ui.draggable).attr('id'), moved_id:$(this).next().attr('id') },
							   function(s) {
								   window.location=baseurl+s;
						}, "text");
					},
					over: droppableIn,
					out: droppableOut
			}); // end before droppable initialization
			// if the draggable isn't the last row
			if ($(this).attr('id') != $('tbody tr').last().attr('id')) {
				$(droppableBlankrow)
				.insertAfter($(this).siblings('tr').last()).droppable({
					tolerance: 'touch',																																			
					drop: function (event,ui) {
						// apply the reorder via ajax
						$.post( baseurl+"teachers/assignmentsEngine", 
							   { action:'makelast', mover_id:$(ui.draggable).attr('id') },
							   function(s) {
								   window.location=baseurl+s;
						}, "text");
					},
					over: droppableIn,
					out: droppableOut
				}); // end droppable initialization
			}
		},
		stop: function(event, ui) {
			$('.droprow').remove();	
		}
	});		
}
	var droppableBlankrow = "<tr class='droprow'><td colspan='4'></td></tr>";
	var droppableIn	 = function(event,ui) { 
		$(this).addClass('success');
		$(ui.helper).removeClass('alert-info').addClass('alert-success');
	}

	var droppableOut = function(event,ui) { 
		$(this).removeClass('success');	
		$(ui.helper).removeClass('alert-success').addClass('alert-info');						
	};
				
function prepareFolderMapButtons() {
	$('ul.dropdown-menu li a').click(function() {
		$.post(baseurl+"teachers/foldersEngine", 
			   { action:'map', assignment_id: $(this).attr('name'), folder_label: $(this).text() }, function(s) {
			window.location=baseurl+s;
		}, "text");
	});
}

function prepareFolderUnmapButtons() {
	$('button.unmap').click(function() {
		var label = $(this).prev().text();
		$.post(baseurl+"teachers/foldersEngine", 
			   { action:'unmap', assignment_id: $(this).val(), folder_label: label }, function(s) {
			window.location=baseurl+s;
		}, "text");
	});
}

/* -------------------------- utility functions -------------------------- */

function enableKeyCode(obj, f, actionKeyCode) {
	$(obj).bind('keydown', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == actionKeyCode) {
			f();
		}
	}); // that...is nice.
}