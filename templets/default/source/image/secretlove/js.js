var inputval = '直接输入好友的账号或点选好友';

$(document).ready(function(){
	$('#toname').focus(function(){
		$(this).removeClass('first');
		if($(this).val()==inputval){
			$(this).val('');
		}
	});
	
	$("#cfbut").click(function(e) {
		getFans(0,185,295,e.pageY,e.pageX);
		stopBubble(e);
	});
	
	$('#content').click(function(){
		if(document.getElementById('fansDiv') && document.getElementById('fansDiv').style.display!='none'){
			$('#fansDiv').hide();	
		}
	});
	
	$('#oksure').click(function(){
		var zb = (document.getElementById('okzb').checked?1:0);
		var st = (document.getElementById('okst').checked?1:0);
		if((zb+st)*1>0){
			var url = myurl()+'&act=over&zb='+ zb +'&st='+ st +'&to='+ encodeURIComponent(peiDui);
			ajax(url,function(){});
		}
		$('.task').hide();
		$('.ok').hide();
	});
});

var peiDui='';
function submit(){
	var toval = $('#toname').val().trim();
	if(inputval==toval || toval.length==0){
		alert('请先输入或点击【选好友】选择你暗恋的对象呀');
		return;
	}
	var url = myurl()+"&act=submit&touid="+encodeURIComponent(toval);
	ajax(url,function(res){
		eval(res);
		$('#content').animate({ 
			height:520
		  },1000);
		$('#submita').html('发布成功');
	});
}

function peiduiover(name,nick){
	peiDui = name;
	$('.okname').html(nick);
	$('.task').show();
	$('.ok').css({'width':733,'height':572}).show();
	
}

function clickFriend(nick,name){
	$('#toname').val(name);
	$('#fansDiv').hide();
}

function post(){
	var con = $('#zhuanbocontent').val();
	var url = myurl()+"&act=post";
	ajax(url);		
}