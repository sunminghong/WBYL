window.onload = function(){
	var rs = document.getElementById("rs1");
	var rsselect = rs.getElementsByTagName("select");
	rsselect[0].name = "countrycode";
	rsselect[1].name = "provincecode";
	rsselect[2].name = "citycode";
	for(var i=0;i<rsselect.length;i++){
		var sl = rsselect[i];
		if(sl.length==1){
			sl.style.display="none";
		}
	}
	rsselect[0].onchange = function(){
		rsselect[1].options.length = 0;
		rsselect[2].options.length = 0;
		citylist.sel=["?",this.options[this.selectedIndex].text].join(">");
		if(citylist.getOptions().length==29){//省份为空,直接控制城市
			rsselect[1].style.display="none";			
			rsselect[2].style.display="";
			citylist.sel=["?",this.options[this.selectedIndex].text,"?"].join(">");
			if(citylist.getOptions().length==29){//城市为空
				rsselect[2].style.display="none";
			}else{
				citylist.addOptionObjects(rsselect[2]);
			}
		}else{
			citylist.addOptionObjects(rsselect[1]);
			rsselect[1].style.display="";
			rsselect[1].onchange();
			//rsselect[2].style.display="none";
		}
	};
	//rsselect[0].onchange();
	rsselect[1].onchange = function(){
		rsselect[2].options.length=0;
		citylist.sel=["?",rsselect[0].options[rsselect[0].selectedIndex].text,this.options[this.selectedIndex].text].join(">");
		rsselect[2].style.display="";
		citylist.addOptionObjects(rsselect[2]);
	};
	//日期
	var year = document.getElementById("year"),
		month = document.getElementById("month"),
		day = document.getElementById("day");
	
	year.onchange = month.onchange = function(){
		var daysInMonth = [0,31,28,31,30,31,30,31,31,30,31,30,31],
			yearvalue = year.value,
			num;
		if (((yearvalue%4 == 0)&&(yearvalue%100 != 0)) || (yearvalue%400 == 0)) {
			daysInMonth[2] = 29;
		}
		num=daysInMonth[month.value];
		day.options.length = 0;
		for(var i=1;i<=num;i++){
			try{
				day.add(new Option(i+"日",i));
			}catch(err){
				day.add(new Option(i+"日",i),null);
			}
		}
	}
	//禁用，启用提交按钮
	var enableSubmit = function(){
		var submitbtn = document.getElementById("submitbtn");
			submitbtn.disabled="";
			submitbtn.style.cursor="pointer";
			submitbtn.style.cursor="hand";
	}
	var disableSubmit = function(){
		var submitbtn = document.getElementById("submitbtn");
			submitbtn.disabled="disabled";
	}
	//昵称检查
	var nick = document.getElementById("nick");
	nick.onchange = function(){
		 var nickvalue = this.value.trim(),
		     nicktips = document.getElementById("nicktips"),
		     nickpass = document.getElementById("nickpass"),
		 	 nickerror = null;
		 if (nickvalue.match(/[^\u4e00-\u9fa5\w-]/g)){nickerror='仅支持中文、字母、数字、下划线或减号';}
		 if (nickvalue.length < 1 || nickvalue.length > 12){nickerror='仅支持1-12个中文、字母、数字、下划线或减号';}
		 if (nickvalue.length == 0){nickerror = '这里一定要填';}
		 if(nickerror){
			 nicktips.className = "error";
			 nicktips.innerHTML = nickerror;
			 nickpass.className="";
			 disableSubmit();
			 return false;
		 }else{
			 nicktips.className = "gray";
			 nicktips.innerHTML = "1-12个中文、字母、数字、下划线或减号";
			 //nickpass.className="usebtns pass";
			 document.getElementById("submitbtn").disabled="";
			 enableSubmit();
			 return true;
		 }
	}
	//自我介绍检查
	var intro = document.getElementById("summary");
	intro.onchange = function(){
			var introvalue = this.value.trim(),
				introtips = document.getElementById("introtips");
			if(introvalue.length>140){
				introtips.className  = "error";
				disableSubmit();
				return false;
			}else{
				introtips.className  = "gray";
				enableSubmit();
				return true;
			}
	}
	//
	window.checkform = function(){
		if( nick.onchange() && intro.onchange()){
			return true;
		}
		return false;
	};
	window.saveform = function(){
		if(checkform()){
			$("#setForm").submit();
			return true;
		}
		return false;
	};
};