var urlbase="?app=ilike";
$(document).ready(function(){
	var rateid=location.hash.replace('#','&');

	$.get(urlbase+"&op=next&rateid="+rateid,function(res){
		eval('var users='+res);

		$('#photodiv').css('background','url() no-repeat');
	});

});