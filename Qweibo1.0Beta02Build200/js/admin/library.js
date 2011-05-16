/*
 * 去除首尾空格
 */
String.prototype.trim = function () 
{ 
	return this.replace(/(^\s*)|(\s*$)/g, ""); 
} 

/*
 * 判断输入内容是否为空
 */
function checkNull(obj){
	obj.each(function(){
		if ( $(this).val() == '' )
		{
			alert('该输入框不能为空');
			$(this).focus();
			goon = false;
			return false;
		}
	})
}

/*
 * 判断输入的日期是否合法
 * 传入的time必须是一个对象，中间包含_year, _month, _day, _hour, _minute等属性
 */
function checkDate(time){
	var _year = time.year.val();
	var _month = time.month.val();
	var _day = time.day.val();
	var _hour = time.hour.val();
	var _minute = time.minute.val();
	var monthType = {
		1 : 'type1',
		2 : 'type3',
		3 : 'type1',
		4 : 'type2',
		5 : 'type1',
		6 : 'type2',
		7 : 'type1',
		8 : 'type1',
		9 : 'type2',
		10: 'type1',
		11: 'type2',
		12: 'type1'
	};
	var __date = '';

	if ( _year < year )
	{
		alert('你输入的年份太小，请重新输入');
		time.year.select();
		goon = false;
		return false;
	}
	if ( _month < 1 || _month > 12 )
	{
		alert('你输入的月份不合法，请重新输入');
		time.month.select();
		goon = false;
		return false;
	}
	if ( _day < 1 || ( monthType[_month] = 'type1' && _day > 31 ) || ( monthType[_month] = 'type2' && _day > 30 ))
	{
		alert('你输入的日期不合法，请重新输入');
		time.day.select();
		goon = false;
		return false;
	}
	if ( ( _year % 4 == 0 && _year % 100 != 0 ) || ( _year % 400 == 0 && _year % 100 == 0 ) )
	{
		if ( monthType[_month] = 'type3' && _day > 29 )
		{
			alert('你输入的日期不合法，请重新输入');
			time.day.select();
			goon = false;
			return false;
		}
	}
	else
	{
		if ( monthType[_month] = 'type3' && _day > 28 )
		{
			alert('你输入的日期不合法，请重新输入');
			time.day.select();
			goon = false;
			return false;
		}
	}
	if ( _hour > 23 || _hour < 0 )
	{
		alert('你输入的小时不合法，请重新输入');
		time.hour.select();
		goon = false;
		return false;
	}
	if ( _minute > 59 || _minute < 0 )
	{
		alert('你输入的分钟不合法，请重新输入');
		time.minute.select();
		goon = false;
		return false;
	}
	__date += String( _year );
	__date += String( _month );
	__date += String( _day );
	__date += String( _hour );
	__date += String( _minute );
	if ( __date < _date )
	{
		alert('你输入的时间小于当前的时间，请重新输入');
		time.month.select();
		goon = false;
		return false;
	}
}

/*
 * 判断是否是数字
 */
function checkNum(obj){
	obj.each(function(){
		if ( isNaN( $(this).val() ))
		{
			alert('请输入一个有效的数字');
			$(this).select();
			goon = false;
			return false;
		}
	})
}
