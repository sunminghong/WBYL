(function (){
	$( '.umain' ).find( '.flowaction' ).click( function () {
		var obj = $( this ),
			_class = obj.attr( 'class' ),
			_username = obj.attr( 'data-username' );
		if ( _class.indexOf( 'nohave' ) != -1 )
		{
			$.post('./index.php?m=a_follow', { 'name' : _username , 'type' : 1 }, function ( json ){ //1 收听
				eval('var data=' + json);
				if ( data.code == 0 )
				{
					var el = $( '#n' + _username ).find( '.follower' );
					//动画效果 michal
					obj.animate({'background-position': '-59px -55px'},function(){
						obj.removeClass( 'nohave' ).addClass( 'have' );
					});
					obj.attr("title","取消收听");
					el.html( Number( el.html() ) + 1 );
				}
				else
				{
					alert('收听失败');
				}
			});
		}
		else
		{
			$.post('./index.php?m=a_follow', { 'name' : _username , 'type' : 0 }, function ( json ){ //0 取消收听
				eval('var data=' + json);
				if ( data.code == 0 )
				{
					var el = $( '#n' + _username ).find( '.follower' );
					//动画效果 michal
					obj.animate({'background-position': '-59px -32px'},function(){
						obj.removeClass( 'have' ).addClass( 'nohave' );
						obj.attr("title","立即收听");
					});
					el.html( Number( el.html() ) - 1 );
				}
				else
				{
					alert('取消收听失败');
				}
			});
		}
	});
})()
