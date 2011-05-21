if($('#main').height()<=639){
	$('#main').css('height',619);
}else{
	$('#main').css('height','auto');
}
fHideFocus('a');


function fHideFocus(tName){
	var aTag=document.getElementsByTagName(tName);
	var arrlen = aTag.length;
	for(var i=0;i<arrlen;i++){
		aTag[i].hideFocus=true;
	}
}

var $id = function(id){
	return document.getElementById(id);
}


var data = {
	'logined':false,
	'pre' : {
		//pre键可选，不存在表示第一次浏览页面
		'score':9.5,
		'pnum':35,
		'img':['http://asset3-cdn.hotornot.com/photos/017/868/345/17868345/large_2011-04-10_09-57-08_328.jpg']
	},
	'now' : {
		'img':['小图','http://hotornot.com/photos/017/868/166/17868166/large_190270_601767266736_32501001_33982732_3173825.jpg']	

	},
	'next' : {
		'img':['http://asset3-cdn.hotornot.com/photos/017/868/345/17868345/large_2011-04-10_09-57-08_328.jpg','大图']	
	}
};

$(document).ready(function(){
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});	
});


function fmtData(j){
	if(!j.pre){
		if(j.logined==false){
			$id('left').className = 'first';	
			$('#left').css({'background':'url(images/leftbg2.png) no-repeat left 35px'});
		}else if(j.logined==true){
			$id('left').className = 'first';	
			$('#left').css({'background':'url(images/leftbg3.png) no-repeat left 35px'});
			$('#loginbtn').hide();
		}
	}else{
		$id('left').className = '';
		$('.loginfalse').hide();
		$('.logintrue').show();
		
		//上一个
		$('#prescore').html(j.pre.score);
		$('#prepnum').html(j.pre.pnum);
		$('#preimg').attr('src',j.pre.img[0]);
		//当前
		$('#photodiv').css({'background-image':'url('+ j.now.img[1] +')'});
		//下一个
		$('#nextimg').attr('src',j.next.img[0]);
	}
}

fmtData(data);