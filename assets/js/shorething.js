// JavaScript Document
$(document).ready(function(){
	prepareAssignmentDrags();						   
	prepareFolderMapButtons();						   
	prepareFolderUnmapButtons();
	prepareStudentPagers();
});

function prepareAssignmentDrags() {
	$('#teacherassignments tr').draggable({ 
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

function prepareStudentPagers() {
	$('ul.pager li a').click(function() {
		var thisa = $(this);
		if ($(this).parent().hasClass('disabled')) { return false; }
		// get next or previous
		var dir = $(this).parent().hasClass('next') ? "next" : "previous" ;
		// decompose the pipe-separated variable
		var pagevar = $(this).attr('id').split('|');
		
		$.post(baseurl+"students/studentPagingEngine", 
			   { direction:dir, class_id: pagevar[0], page: pagevar[1] },
			function(s) {
				var tbody = $(thisa).closest('tbody');
				// determine the number of tr's to get the pagesize variable
				var pagesize = $('tr', $(tbody)).length-1;
			
				$(tbody).empty();
				$.each(s, function(i,a) {
					
					// instead of typing the HTML here, use a view to return a string!
					$(tbody).append("<tr><td><a class='btn btn-small' href='upload/" + 
									a.assignment_filepath + "'> <i class='icon-file'></i></a></td>" +
									"<td><a href='upload/" + a.assignment_filepath + "'>" +
									a.assignment_label + "</a></td></tr>");	
				});
				
				curpage				= (dir=='next') ? parseInt(pagevar[1])+1 : parseInt(pagevar[1])-1;
				idnext				= pagevar[0]+"|"+curpage+"|"+pagevar[2];
				idprevious			= pagevar[0]+"|"+curpage+"|"+pagevar[2];
				var disablenext 	= '';
				var disableprevious	= '';
				
				if (dir=='previous' && pagevar[1]=='2') {
					disableprevious	='disabled';
				} else if (dir=='next' && s.length<pagesize) {
					disablenext		='disabled';
				}
								
				$(tbody).append("<tr><td colspan='2'><ul class='pager'>"+
								"<li class='previous " + disableprevious + "'><a id='" + idprevious + 
								"' href='#'>&larr; Newer</a></li><li class='next " + disablenext + 
								"'><a id='" + idnext + "' href='#'>&rarr; Older</a></li>"+
								"</ul></td></tr>");
				$('ul.pager li a').unbind('click');
				prepareStudentPagers();
		}, "json");
		return false;
	});	
}