// JavaScript Document
$('.condicionesTextArea').on("paste", function(){
	var that = $(this);
	setTimeout(function(){
		//var $content = that.text();
		var content = that.text().replace(/<(?:.|\n)*?>/gm, '');
		that.empty();
		that.text(content);
	},10);
});	