/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-14 12:13
 * Filename: relatedSelection.js
 * Description: 关联选择
******************************************************************************/
//relatedSelection 库
var relatedSelection = function( data,initsel){
	this.data = data;//源数据
	this.sel = initsel;//当前选择
	this.uid = 0;//元素唯一ID
};
//
String.prototype.trim = function(){
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
};
//
relatedSelection.each = function(enumerateable,func){
	for( var i=0,l=enumerateable.length;i<l;i++ ){
		func(i,enumerateable[i]);
	}
};
//
relatedSelection.walk = function( data ){
	for( var i=0,l=data.length;i<l;i++ ){
		if(data[i].data && data[i].data.length > 0 ){
			relatedSelection.walk(data[i].data);
		}
	}
};
relatedSelection.cache = [];
relatedSelection.uid = 0;
//
relatedSelection.getOptions = function( data,selectItem,selectItemIsText ){
	var option1="",
		options="";
	relatedSelection.each(data,function(k,v){
		if(v){
			option = "<option value=\""+v.value+"\""+" "+(selectItemIsText?(v.text===selectItem?"selected":""):(v.value===selectItem?"selected":""))+">"+v.text+"</option>";
			options+=option;
		}
	});
	return options;
};
relatedSelection.getSelectBox = function( data,selectItem,selectItemIsText ){
	var startSelect = "<select id=\"selectbox"+(++relatedSelection.uid)+"\">",
		endSelect = "</select>";
	return startSelect + relatedSelection.getOptions(data,selectItem,selectItemIsText) + endSelect;
};
//
relatedSelection.getData = function( data,text,isByText ){
	var result;
	relatedSelection.each(data,function(k,v){
		if((isByText && v && v.text == text) || (!isByText && v && v.value == text)){
			result = v;
		}
	});
	return result;
};
//
relatedSelection.enumerateGetData = function( data,textArr,pointer,queryByText ){
	pointer = pointer || 0;
	if(textArr[pointer]){
		var resultData = relatedSelection.getData( data,textArr[pointer].trim(),queryByText );
		if( resultData && resultData.data && resultData.data.length > 0 ){
			relatedSelection.cache.push(resultData);
			return relatedSelection.enumerateGetData( resultData.data,textArr,++pointer,queryByText );
		}else{
			return data;
		}
  	}else{
		return data;
  	}
};
//
relatedSelection.prototype.getData = function( context ){
	relatedSelection.cache=[];
	if(context.split){//text is string
		context = context.split(">");
		return relatedSelection.enumerateGetData(this.data,context,0,true);
	}else if(context.text && context.text.split){
		context = context.text.split(">");
		return relatedSelection.enumerateGetData(this.data,context,0,true);
	}
	else if(context.value && context.value.split){//text is object text.value is string
		context=context.value.split(">");
		return relatedSelection.enumerateGetData(this.data,context,0,false);
	}
};
relatedSelection.prototype.getOptions = function(){
	var htmlstring="";
	htmlstring+=relatedSelection.getOptions(this.getData(this.sel)," ",false);
	return htmlstring;
};
//
relatedSelection.prototype.addOptionObjects = function( selectelement ){
	relatedSelection.each(this.getData(this.sel),function(k,v){
		if( v && v.text && v.value){
			var option = document.createElement("option");
				option.text = v?v.text:"";
				option.value = v?v.value:"";
			try{
				selectelement.add(option);
			}catch(err){
				selectelement.add(option,null);
			}
		}
	});
};
//
relatedSelection.prototype.getHTML = function(){
	this.getData(this.sel);
	var htmlstring="<span id=\"rs"+(++this.uid)+"\">",
		sel=null;
	if(this.sel.split){
		sel = this.sel.split(">");
		relatedSelection.each(relatedSelection.cache,function(k,v){
			htmlstring+=relatedSelection.getSelectBox( v.data,sel[k+1],true);
		});
	}else if(this.sel.text && this.sel.text.split){
		sel = this.sel.text.split(">");
		relatedSelection.each(relatedSelection.cache,function(k,v){
			htmlstring+=relatedSelection.getSelectBox( v.data,sel[k+1],true);
		});
	}else if(this.sel.value && this.sel.value.split){
		sel = this.sel.value.split(">");
		relatedSelection.each(relatedSelection.cache,function(k,v){
			htmlstring+=relatedSelection.getSelectBox( v.data,sel[k+1],false);
		});
	}
	return htmlstring+"</span>";
};
