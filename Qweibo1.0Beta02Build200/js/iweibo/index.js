(function (){
	IS.init( $('#uploadImg'), $('.zhaopiantxt'), 'uploadImg' );
	$('.sendbtn').click(function(){
		MSG.checkPostData();
	});
	$( '#msgTxt' ).keypress( function ( event ){
		UI.isCtrlEnter( event, function (){
			MSG.checkPostData();
		});
	});
	$('#newTopic').click(function(){
		MSG.txtTopic = '#输入话题标题#';
		MSG.addTopic();
	});
	$('#msgTxt').keyup(function(){
		MSG.countTxt();
	});
	//微博输入框发送按钮
	$( '#sendbtn' ).mouseover(function(){
		$(this).css( { 'background-position' : '0px -31px'} );
	}).mouseout(function(){
		$(this).css( { 'background-position' : '0px 0px'} );
	});
})();
