// JavaScript Document
$(document).ready(function(){
	//prepareFormSubmits();					   
});

/* -------------------------- utility functions -------------------------- */

function enableKeyCode(obj, f, actionKeyCode) {
	$(obj).bind('keydown', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == actionKeyCode) {
			f();
		}
	}); // that...is nice.
}