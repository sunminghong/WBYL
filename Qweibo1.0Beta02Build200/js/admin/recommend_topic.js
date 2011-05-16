var goon;		
(function (){
	$('#preview').click(function (){
		goon = true;	
		checkNull($('.createNewTopic').find('input[type="text"]')); //判断输入框内容是否为空
		if (goon)
		{
			checkNum($('.time').find('input[type="text"]')); 判断输入的内容是不是数值
		}
		if (goon)
		{
			checkDate({
				year : $('#year'),
				month : $('#month'), 
				day : $('#day'),
				hour : $('#hour'),
				minute : $('#minute')
			}); 判断日期是否正确
		}
		if (goon)
		{
			$('input[name="topicName"]').val($('input[name="topicName"]').val().trim());
			$('#form1').submit();
		}
	});
})()
