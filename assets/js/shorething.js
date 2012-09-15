// JavaScript Document
$(document).ready(function(){
	prepareFolderUnmapButtons();
	prepareFolderMapButtons();
});

function prepareFolderUnmapButtons() {
	$('button.unmap').click(function() {
		var label = $(this).prev().text();
		$.post(baseurl+"teachers/foldersEngine", 
			   { action:'unmap', assignment_id: $(this).val(), folder_label: label }, function(s) {
			window.location=baseurl+s;
		}, "text");
	});
}

function prepareFolderMapButtons() {
	$('ul.dropdown-menu li a').click(function() {
		$.post(baseurl+"teachers/foldersEngine", 
			   { action:'map', assignment_id: $(this).attr('name'), folder_label: $(this).text() }, function(s) {
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