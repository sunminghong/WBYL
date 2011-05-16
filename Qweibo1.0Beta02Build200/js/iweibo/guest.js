//michal,客人页收听、取消收听逻辑改变
(function(){
	var followBtnActions = function(){
		$("#dofollow").click(function(){
			$.post('./index.php?m=a_follow', { 'name' : $('#username').val() , 'type' : 1 }, function ( data ){
				var json;
				try{
					var json = $.parseJSON(data);
				}catch(err){
					
				}
				if(json){
					if(json.code==0 || json.code=="0"){
						$("#followinfo").html("<div class=\"followed\">已收听&nbsp;&nbsp;<a id=\"dounfollow\" href=\"javascript:;\" title=\"取消收听\">取消收听</a></div>");
						if($('#username').val() != "t" && $('#username').val() != "QQgenius"){
							$( '#follower').html( parseInt( $( '#follower' ).html(), 10 ) + 1 );
						}
						followBtnActions();
					}else{
						alert('收听失败');
					}
				}else{
					alert("服务器返回的数据不正确");
				}
			});
		});
		$("#dounfollow").click(function(){
			$.post('./index.php?m=a_follow', { 'name' : $('#username').val() , 'type' : 0 }, function ( data ){
				var json;
				try{
					var json = $.parseJSON(data);
				}catch(err){
					
				}
				if(json){
					if(json.code==0 || json.code=="0"){
						$("#followinfo").html("<a id=\"dofollow\" class=\"usebtns flowaction nohave\" href=\"javascript:;\" title=\"立即收听\"></a>");
						if($('#username').val() != "t" && $('#username').val() != "QQgenius" ){
							$( '#follower' ).html( Math.max( parseInt( $( '#follower' ).html(), 10 ) - 1, 0 ));
						}
						followBtnActions();
					}else{
						alert('取消收听失败');
					}
				}else{
					alert("服务器返回的数据不正确");
				}
			});
		});
	};
	followBtnActions();
	chatBox.init();
})();
