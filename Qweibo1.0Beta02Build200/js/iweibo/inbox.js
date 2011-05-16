(function (){
 	popBox.init();
	$('.mailmain').find('.delmail').click(function(){
		UI.delBox.show($(this));
	});
	$('.sendmail').click(function(){
		popBox.show($(this).attr("data-username"));
	});
})();
