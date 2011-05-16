/*
 * 去除首尾空格
 */
String.prototype.trim = function ()
{
	return this.replace(/(^\s*)|(\s*$)/g, ""); 
}

/*
 * 判断是否有字符
 *
 * @param {String} String 字符
 * @return {Boolean} Boolean 返回布尔?
 * @example
 * 'abc'.hasString( 'a' );
 * 'abc'.hasString( ['a', 'b'] );
 */
String.prototype.hasString = function( o ) {
 if ( typeof o == 'object' ) {
 for ( var i = 0, n = o.length;i < n;i++ ) {
 if ( !this.hasString( o[i] ) ) return false;
 }
 return true;
 }
 else if ( this.indexOf( o ) != -1 ) return true;
}

/*
 * 判断输入内容是否为空
 */
function checkEmpty( obj ){
	if ( (obj.val().trim() == '' && !$('#uploadImg').val().length > 0 ) || obj.val().trim() == '#输入话题标题#' ){//只有图片也允许提交表单 michal
		return false;
	}else{
		return true;
	}
}

/*
 * 初始化图片选择按钮
 */
IS = {
	init : function ( input, text, inputName ){
		var that = this;
		input.val( '' );
		input.change( function(){
			var filename = input.val();
			if ( navigator.appVersion.indexOf( "MSIE" ) != -1 ){
				filename = document.getElementById( inputName ).value;
			}
			if ( checkImgSuffix( filename ) === true ){
				if ( filename.indexOf( '\\' ) != -1 ){
					var filenameArr = filename.split( '\\' );
					filename = filenameArr[filenameArr.length - 1];
				}
				//最长显示12个字符/6个汉字长度，文件名超长…省略显示，扩展名需完整显示，鼠标经过tips显示完整文件名 michal
				var fileNameSP = filename.lastIndexOf(".");
				var fileNameFull= filename.substring(0,fileNameSP);
				var fileNameExt = filename.substring(fileNameSP+1);
				var fileNameShort = fileNameFull;
				if( fileNameFull.length > 12 ){
					fileNameShort = fileNameFull.substring(0,12)+"..";
				}
				text.attr("title",filename);
				//
				text.html( [fileNameShort,fileNameExt].join(".") );
				$( '.zmiddle' ).css( 'display', 'none' );
				$( '.cancelPic' )
					.one( 'click', function (){
						that.cancel( input, text, inputName );
					} )
					.css( 'visibility', 'visible' );
				if ( $( '#msgTxt' ).val() == '' ){
					MSG.txtTopic = '#分享照片#';
					MSG.addTopic();
				}
			}
		} );
		input.mouseover( function (){
			text.css( 'text-decoration', 'underline' );
		}).mouseout( function (){
			text.css( 'text-decoration', 'none' );
		});
	},

	cancel : function ( input, text, inputName ){
		input.val( '' );
		text.html( '照片' );
		$( '.zmiddle' ).css( 'display', 'block' );
		$( '.cancelPic' ).css( 'visibility', 'hidden' );
		if ( $( '#msgTxt' ).val() == '#分享照片#' ){
			MSG.clearTextarea();
			MSG.countTxt();
		}
		if ( navigator.appVersion.indexOf( "MSIE" ) != -1 ){
			var input1 = document.getElementById( inputName );
			var input2 = input1.cloneNode( false );
			input2.onchange = input1.onchange;
			input1.parentNode.replaceChild( input2, input1 );
		}
	}
}

/*
 * 判断上传的内容是否为图片
 */
function checkImgSuffix( filename ){
	var nameArr = filename.split( '.' );
	var suffix = nameArr[ nameArr.length - 1 ].toLowerCase();
	if ( suffix != 'jpg' && suffix != 'jpeg' && suffix != 'gif' && suffix != 'png' ){
		alert( '仅支持jpg、jpeg、gif、png图片文件' );
		return false;
	}else{
		return true;
	}
}

MSG = {
	_txt : document.getElementById( 'msgTxt' ),
	serverAnswer:0,//帖子发表成功+1
	txtTopic : '#输入话题标题#',
	overflow : false,
	/*
	 * 选取文本框中的文本
	 *
	 * @param {Object} Object Dom对象
	 * @param {Number} Number 开始位置
	 * @param {Number} Number 结束位置
	 * @param {Number} Number 当前位置
	 * @example
	 * MSG.selectTxt( el, 1, 2, 2 );
	 */
	selectTxt : function( el, start, end, curPosition ){
		var range;
		if ( document.createRange ) {
			el.setSelectionRange( start, end );
		}
		else {
			range = el.createTextRange();
			range.collapse( 1 );
			range.moveStart( 'character', start );
			range.moveEnd( 'character', end - start );
			range.select();
		}
	},

	insertTxt : function( el, text, cursorX, del ){
		if ( del == undefined ) {
			del = 0;
		}
		el.focus();
		if ( document.selection ) {
			var range = document.selection.createRange()
			range.moveStart( 'character', -del );
			range.text = text;
		}
		else {
			var textTmp = el.value,
				cursor = cursorX + text.length - del;
			el.value = textTmp.substring( 0, cursorX - del ) + text + textTmp.substring( cursorX, textTmp.length );
			MSG.selectTxt( el, cursor - 7, cursor - 1, cursor - 7 );
		}
	},

	/*
	 * 获取光标位置
	 *
	 * @param {Object} Object Dom对象
	 * @return {Number} Number 光标位置
	 * @example
	 * MSG.cursorX( el );
	 */
	cursorX : function( el ){
		if ( document.selection ){

			var range = document.selection.createRange(), position = 0, txt;
			range.moveStart ( 'character', -el.value.length );
			txt = range.text.split( '\001' );
			position = txt[txt.length - 1].replace(/\r/g,'').length;
			return position;

		}
		else return el.selectionStart;
	},

	addTopic : function(){
		this._txt.focus();

		if ( !this._txt.value.hasString( this.txtTopic ) ) {
			MSG.insertTxt( this._txt, this.txtTopic, MSG.cursorX( this._txt ) );
		}
		//Add Topic
		var txt = this._txt.value,
			indexOf = txt.replace(/\r/g,'').indexOf(this.txtTopic),len;
		if ( indexOf == -1 ) {
			indexOf = 0
		}
		len = this.txtTopic.length;
		MSG.selectTxt( this._txt, indexOf + 1, indexOf + len - 1, indexOf );
		this._txt.scrollTop = 999; //Textarea Scroll To End

		this.countTxt();
	},

	/*
	 * 判断输入字符的数量
	 *
	 */
	countTxt : function( id ){
		var id = id || 'msgTxt';
		var len = UI.countTxt( $( '#' + id ), id );
		var countVal = Math.floor( 140 - len );

		if ( countVal >= 0 ){
			$( '.count' ).html( countVal );
			if ( this.overflow ){
				$( '.count' ).css( 'color', '#999999' );
				$( '#countHint' ).html( '还能输入' );
				this.overflow = false;
			}
		}else{
			if ( countVal < -110 ){
				countVal = '很多';
				$( '.count' ).html( countVal )
			}else{
				$( '.count' ).html( -1 * countVal )
			}
			$( '#countHint' ).html( '超出' );
			$( '.count' ).css( 'color', '#E56C0A' );
			this.overflow = true;
		}
	},
	
	/*
	 * 清除Teatarea内容
	 *
	 */
	clearTextarea : function( id ){
		id = id || 'msgTxt';
		$( '#' + id ).val( '' ).focus();
	},

	/*
	 * 消息提示
	 *
	 * @param { String } 提示的类型
	 *
	 */
	hint : function ( type, id ){
		var overflowHint = $( '#overflowHint' ),
			tmpContent = '<span id="countHint">还能输入</span><span class="count">140</span>字',
			tmpMailContent = '<span id="countHint">还能输入</span><em class="count">140</em>字',
			id = id || 'msgTxt';
		if ( type == 'empty' ){
			overflowHint.html( '<span id="countHint">请输入内容</span>' );
		} else if ( type == 'onlyTitle' ){
			overflowHint.html( '<span id="countHint">再说点什么</span>' );
		} else if ( type == 'success' ){
			overflowHint.html( '<span id="countHint">广播成功</span>' );
		} else if ( type == 'submitting' ){
			overflowHint.html( '<span id="countHint">提交还未完成</span>' );
		} else if( type == 'timeout' ){
			overflowHint.html( '<span id="countHint">连接超时，请检查网络</span>' );
		}else if( type == 'mailfail' ){
			overflowHint.html( '<span id="countHint">私信发送失败</span>' );
		}else if ( type == 'notyourfans' ){
			overflowHint.html( '<span id="countHint">他/她还没有收听你，暂时不能发私信</span>' );
		} else if( type == 'accountnotvalid' ){
			overflowHint.html( '<span id="countHint">此用户不存在，请输入你好友当前使用的微博帐号</span>' );
		}else{
			overflowHint.html( '<span id="countHint">'+type+'</span>' );
		}
		$( '#countHint' ).css( 'color', '#E56C0A' );
		overflowHint.fadeOut( "normal", function(){
			overflowHint.fadeIn( "normal", function (){
				if ( type == 'success' ){
					setTimeout( function (){
						overflowHint.stop().html( tmpContent );
					}, 1500 );
				}
			} );
		} );
		$( '#' + id ).one( 'keyup', function(){
			if ( id == 'userName' || id == 'mailTxt' ){
				overflowHint.stop().html( tmpMailContent ).css( 'opacity', 100 );
				MSG.countTxt( 'mailTxt' );
			}
			else if ( id == 'chatTxt' ){
				overflowHint.stop().html( tmpMailContent ).css( 'opacity', 100 );
				MSG.countTxt( 'chatTxt' );
			}else{
				overflowHint.stop().html( tmpContent ).css( 'opacity', 100 );
				MSG.countTxt();
			}
		} ).blur();
		setTimeout( function (){
			$( '#' + id ).focus();
		}, 500 );
	},

	/*
	 * 发送广播过程中的处理
	 *
	 */
	submitProcess : function (){
		$( '#sendbtn' ).css( { 'background-position' : '0 -62px', 'cursor' : 'default' } ).attr( 'disable', 'yes' );
	},

	/*
	 * 检测输入的内容是否合法
	 *
	 */
	checkPostData : function (){
		if ( checkEmpty( $('.holder textarea') ) === true && $('#sendbtn').attr( 'disable' ) != 'yes' ) {
			if ( UI.checkOnlyTitle( $('.holder textarea').val() ) === true && !$('#uploadImg').val().length > 0 ){
				MSG.hint( 'onlyTitle' );
				return false;
			} else if ( !MSG.overflow ){ //提交表单
				//提交成功
				$('input[name="callback"]').val( 'parent.MSG.submitSuccess' );
				$('.holder textarea').val( $('.holder textarea').val().replace( /#输入话题标题#/, '' ) );
				MSG.submitProcess();
				$('#sendTweet').submit();
				var oldServerAnswer = MSG.serverAnswer;
				setTimeout(function(){
					if(!(MSG.serverAnswer > oldServerAnswer)){
						MSG.hint("timeout");//连接超时
						$( '#sendbtn' ).css( { 'background-position' : '0 0', 'cursor' : 'pointer' } ).attr( 'disable', 'no' );
						setTimeout(function(){
							$("#msgTxt").trigger("keyup");
						},2500);
					}
				},5*1000/*5秒后检查发送是否收到服务器应答*/);
			} else {
				$("#overflowHint").fadeOut("normal", function(){
					$("#overflowHint").fadeIn("normal");
				}); 
			}
		} else if ( $('#sendbtn').attr( 'disable' ) == 'yes' ) {
			MSG.hint( 'submitting' );
			return false;
		} else {
			MSG.hint( 'empty' );
		}
	},

	/*
	 * 发表微博成功后处理函数
	 *
	 * @param { Object } 返回的Json对象
	 *
	 */
	submitSuccess : function( json ){
		MSG.serverAnswer++;
		wbfuncs.fn.updateTime.update(json.timestamp);
		var input = $( '#uploadImg' ),
			text = $( '.zhaopiantxt' ),
			inputName = 'uploadImg',
			content = '';
		if ( json.code == 0 ){
			this.hint( 'success' );
			$( '#sendbtn' ).css( { 'background-position' : '0 0', 'cursor' : 'pointer' } ).attr( 'disable', 'no' );
			$( '#msgTxt' ).val( '' );
			IS.cancel( input, text, inputName );
			this.countTxt();
			
			if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
				content = json.data;
				var contentobj = $( content );
				contentobj.addClass("needremove");
				$(".tmain").first().before( contentobj );
				wbfuncs.fn.updateEvents( contentobj );
				var obj = $( $( '.tmain' )[0] );
				var height = obj.height();
				obj.css( { 'overflow' : 'hidden', 'background' : '#FFFAEA', 'height' : 0, 'display' : 'block' } );
				obj.find( 'li' ).css( 'background', '#FFFAEA' );
				obj.animate( {
					'height' : height + 'px'
				}, 'slow', function(){
					obj.css( { 'overflow' : 'auto', 'height' : 'auto' } );
					if ( json.data.image ){
						content = '';
						content += '<a href="';
						content += json.data.image;
						content += '" class="tupian"><img src="';
						content += json.data.image;
						content += '"></a>';
						obj.find( '.tbottom' ).before( content );
					}
				} );
				setTimeout( function (){
					obj.find( 'li' ).css( 'background', '#FFFFFF' );
				}, 3000 );
			}
		} else {
			MSG.hint(json.msg);
			$( '#sendbtn' ).css( { 'background-position' : '0 0', 'cursor' : 'pointer' } ).attr( 'disable', 'no' );
			setTimeout(function(){
				$("#msgTxt").trigger("keyup");
			},2500);
		}
	}
}

/*
 * 私信弹出框
 *
 */
popBox = {
	init : function (){
		var D = $( '.D' ),
			that = this;
		$( 'body' ).append( D );
	},

	show : function ( name ){
		var that = this,
			mailContent = '<div style="" class="letterBg" id="talkBoxMsg">\
								<table cellspacing="0" cellpadding="0" border="0">\
									<tbody><tr>\
										<th>收信人</th><td><div class="txtWrap"><input type="text" autocomplete="off" value="" class="msgTo inputTxt noAutoCmt" id="userName" name="userName"><div class="txtShadow"><span></span><b>|</b><span></span></div>&nbsp;<span class="cNote">请输入你的听众的微博帐号</span></div></td>\
									</tr>\
									<tr>\
										<th>内　容</th><td><div class="txtWrap"><textarea id="mailTxt" class="inputArea noAutoCmt" type="text"></textarea></div></td>\
									</tr>\
									<tr>\
										<th></th>\
										<td>\
										<a href="javascript:;" title="快捷键 Ctrl+Enter" class="sendBtn">发 送</a>\
										<span class="countTxt" id="overflowHint"><span id="countHint">还能输入</span><em class="count">140</em>字</span></td>\
									</tr>\
								</tbody></table>\
							</div>';
		$( '.DClose' ).css( 'display', 'block' );
		if ( navigator.appVersion.indexOf( "MSIE" ) != -1 ){
			UI.getTopForIE( {
				el : '.CR',
				marginLeft : '-285px'
			} );
		} else {
			$( '.CR' ).css( { 'margin-top' : '-86px', 'margin-left' : '-285px' } );
		}
		$( '.DCont' ).html( mailContent );
		if ( name ){
			$( '#userName' ).val( name );
		}else{
			$( '#userName' ).val( '' );
		}
		$( '#mailTxt' ).val( '' );
		MSG.countTxt( 'mailTxt' );
		$( '.DClose' ).one( 'click', function (){
			that.close();
		} );
		UI.setBgHeight();
		$( '.D' ).css( 'display', 'block' );
		$( '.sendBtn' ).unbind().bind( 'click', function (){
			that.submit();
		} );
		
		$( '#mailTxt' ).unbind().bind( 'keyup', function (){
			MSG.countTxt( 'mailTxt' );
		}).bind( 'keydown', function ( event ){
			UI.isCtrlEnter( event, function (){
				that.submit();
			});
		});
		$( '#userName' ).unbind( 'keydown' ).bind( 'keydown', function ( event ){
			UI.isCtrlEnter( event, function (){
				that.submit();
			});
		});
		$('#userName').blur(function(){
			var _this = $(this);
			if(_this.val().length <=0 ){return;}
			$.ajax({
				type:"GET",
				url:"./index.php?m=a_checkfriend",
				dataType:"json",
				data:"type=0&name="+encodeURIComponent(_this.val()),
				success:function( data ){
					if( data.code == 0 ){
						$("#overflowHint").html("<span id=\"countHint\">还能输入</span><em class=\"count\">140</em>字</span>");
						$("#overflowHint").removeClass("disabled");
						MSG.countTxt( 'mailTxt' );
					}else{
						MSG.hint(data.msg);
						$("#overflowHint").addClass("disabled");
					}
				},
				error : function( reqst, status, error ){
					//
				}
			})
		});
		$('#userName').trigger("blur");
		if ( name ){
			setTimeout( function (){
				$( '#mailTxt' ).focus();
			}, 0 );
		}else{
			setTimeout( function (){
				$( '#userName' ).focus();
			}, 0 );
		}
	},

	close : function (){
		$( '.D' ).css( 'display', 'none' );
	},

	submit : function (){
		if ( MSG.overflow || $("#overflowHint").hasClass("disabled") ){
			var overflowHint = $( '#overflowHint' );
			overflowHint.fadeOut( "normal", function(){
				overflowHint.fadeIn( "normal" );
			} );
			$( '#chatTxt' ).focus();
			return;
		}
		var userName, mailC;
		if ( $( '.inputArea' ).val().trim() == '' || $( '#userName' ).val().trim() == '' ){
			if ( $( '#userName' ).val().trim() == '' ){
				MSG.hint( 'empty', 'userName' );
				$( '#userName' ).blur().one( 'keyup', function (){
					MSG.countTxt( 'mailTxt' );
				} );
				setTimeout( function (){
					$( '#userName' ).focus();
				}, 500 );
			}else{
				MSG.hint( 'empty', 'mailTxt' );
				$( '.inputArea' ).blur();
				setTimeout( function (){
					$( '.inputArea' ).focus();
				}, 500 );
			}
		}else{
			if( $( '.sendBtn' ).hasClass("disabled") ){
				MSG.hint( 'submitting' );
				return;
			}
			$( '.sendBtn' ).addClass("disabled");
			$.post( './index.php?m=a_boxadd', { 'content' : $( '.inputArea' ).val() , 'name' : $( '#userName' ).val() }, function ( json ){
				eval( 'var data=' + json );
				if ( data ){
					if ( data.code == 0 ){
						$( '.DClose' ).css( 'display', 'none' );
						userName = $( '#userName' ).val();
						mailC = $( '.inputArea' ).val();
						$( '.sendBtn' ).removeClass("disabled");
						$( '.DCont' ).html( '<div class="popBox"><span class="ico_tsW"><span class="ico_ts"></span></span><h2>发送成功!</h2><p></p></div>' );
					}else{
						MSG.hint(data.msg);
						setTimeout(function(){
							$("#overflowHint").html("<span id=\"countHint\">还能输入</span><em class=\"count\">140</em>字</span>");
							$( '.sendBtn' ).removeClass("disabled");
							MSG.countTxt( 'mailTxt' );
						},2000);
						return;
						//$( '.DCont' ).html( '<div class="popBox"><span class="ico_tsW"><span class="ico_ts" style="background-position : -29px 0"></span></span><h2>发送失败!</h2><p></p></div>' );
					}
					$( '.CR' ).css( { 'margin-top' : '-39px', 'margin-left' : '-107px' } );
					setTimeout( function (){
						$( '.D' ).css( 'display', 'none' );
						if ( data.code == 0 && $( '#boxType' ).val() != 'inbox' ){
							var content = '<li class="mailcell style="display : none"><div class="mailbody"><div class="title">发送给&nbsp;<a href="./index.php?m=guest&u='+userName+'" class="nickname">';
								content += data.data.nick;
								content += '</a><span class="comma">:</span></div><div>';
								content += mailC;
								content = content + '</div><div class="bodybottom">刚刚</div></div><div class="mailaction"><a href=\"javascript:;\" class=\"sendmail bigger first\" data-username=\"'+userName+'\">再写一封</a><a href=\"javascript:;\" class=\"smaller delmail last\" id=\"'+data.data.id+'\">删除</a></div></li>';
							$( '.mailmain' ).prepend( content );
							var obj = $( '.mailmain li:first-child' );
							var height = obj.height();
							obj.find('.sendmail').click(function(){
								popBox.show($(this).attr("data-username"));
							});
							obj.find( '.delmail' ).click( function(){
								UI.delBox.show( $( this ) );
							} );
							obj.css( { 'overflow' : 'hidden', 'background' : '#FFFAEA', 'height' : 0, 'display' : 'block' } );
							obj.animate( {
								'height' : height + 'px'
							}, 'slow', function(){
								obj.css( { 'overflow' : 'auto', 'height' : 'auto' } );
							} );
							setTimeout( function (){
								$( '.mailmain li:first-child' ).css( 'background', '#FFFFFF' );
							}, 3000 );
						}
					}, 2000 );
				}
			} );
		}
	}
}

/*
 * 对话弹出框
 *
 */
chatBox = {
	init : function (){
		var D = $( '.D' ),
			that = this;
		$( 'body' ).append( D );
		$( '#chatBtn' ).click( function (){
			that.show();
		} );
	},

	show : function ( name ){
		var that = this,
			mailContent = '<div style="" id="reply" class="talkWrap">\
								<div class="SA">\
									<em>◆</em>\
									<span>◆</span>\
								</div>\
								<div class="top">\
									<span class="left">\
										<span class="replyTitle">对 <b id="_username"></b> 说:</span>\
									</span>\
								</div>\
								<div class="cont">\
									<textarea id="chatTxt" class="inputTxt noAutoComplete "></textarea>\
									<div class="txtShadow">\
										<span></span><b>|</b><span></span>\
									</div>\
								</div>\
								<div style="margin:5px 0pt 7px;" class="bot">\
									<input type="button" title="快捷键 Ctrl+Enter" class="inputBtn sendBtn"><span class="countTxt" id="overflowHint"><span id="countHint">还能输入</span><em class="count">140</em>字</span>\
								</div>\
								<div class="talkSuc" style="display : none;">\
									<span class="ico_tsW">\
										<span class="ico_ts"></span>\
									</span>\
									<span id="msg" class="msg"></span>\
								</div>\
							</div>';
		$( '.DClose' ).css( 'display', 'block' );
		if ( navigator.appVersion.indexOf( "MSIE" ) != -1 ){
			UI.getTopForIE( {
				el : '.CR',
				marginLeft : '-250px'
			} );
		} else {
			$( '.CR' ).css( { 'margin-top' : '-69px', 'margin-left' : '-250px' } );
		}
		$( '.DCont' ).html( mailContent );
		$( '#_username' ).html( $( '.profile' ).find(".nick").first().text() );
		$( '#chatTxt' ).val( '' );
		MSG.countTxt( 'chatTxt' );
		$( '.DClose' ).one( 'click', function (){
			that.close();
		} );
		UI.setBgHeight();
		$( '.D' ).css( 'display', 'block' );
		$( '.sendBtn' ).unbind().bind( 'click', function (){
			that.submit();
		} );
		$( '#chatTxt' ).unbind().bind( 'keyup', function (){
			MSG.countTxt( 'chatTxt' );
		} ).bind( 'keydown', function ( event ){
			UI.isCtrlEnter( event, function (){
				that.submit();
			});
		});
		setTimeout( function (){
			$( '#chatTxt' ).focus();
		}, 0 );
	},

	close : function (){
		$( '.D' ).css( 'display', 'none' );
	},

	submit : function (){
		if ( MSG.overflow ){
			var overflowHint = $( '#overflowHint' );
			overflowHint.fadeOut( "normal", function(){
				overflowHint.fadeIn( "normal" );
			} );
			$( '#chatTxt' ).focus();
			return;
		}
		if ( $( '#chatTxt' ).val().trim() == '' ){
			MSG.hint( 'empty', 'chatTxt' );
			$( '#chatTxt' ).blur().one( 'keyup', function (){
				MSG.countTxt( 'chatTxt' );
			} );
			setTimeout( function (){
				$( '#chatTxt' ).focus();
			}, 500 );
		}else{
			$( '.sendBtn' ).unbind().bind( 'click', function (){
				MSG.hint( 'submitting' );
			} );
			var chatContent = '@';
			chatContent += $( '#username' ).val();
			chatContent += ' ';
			chatContent += $( '#chatTxt' ).val();
			$.post( './index.php?m=a_addt', { 'content' : chatContent , 'type' : 1 }, function ( json ){
				eval( 'var data=' + json );
				if ( data ){
					$( '.DClose' ).css( 'display', 'none' );
					if ( data.code == 0 ){
						$( '.DCont' ).html( '<div class="popBox"><span class="ico_tsW"><span class="ico_ts"></span></span><h2>发送成功!</h2><p></p></div>' );
					}else{
						$( '.DCont' ).html( '<div class="popBox"><span class="ico_tsW"><span class="ico_ts" style="background-position:-29px 0"></span></span><h2>发送失败!</h2><p></p></div>' );
					}
					$( '.CR' ).css( { 'margin-top' : '-39px', 'margin-left' : '-107px' } );
					setTimeout( function (){
						$( '.D' ).css( 'display', 'none' );
					}, 2000 );
				}
			} );
		}
	}
}

/*
 * 部分共用函数
 *
 */
UI = window.UI || {
	/**
	 * 去掉头尾空格
	 *
	 * @param {String} String 字符串
	 * @return {String} String 字符串
	 * @example
	 * UI.trim( ' = ' );
	 */
	trim : function( o ) {
		return o.replace(/^\s+|\s+$/g,'');
	},
	/**
	 * 遍历数组
	 *
	 * @param {Array} Array 数组
	 * @param {Function} Function 函数
	 * @example
	 * UI.each( [1, 2, 3], function( o, i ){ //o为数组中的项，i为索引
	 *
	 * } );
	 */
	each : function( o, f ) {
		if ( UI.isUndefined( o[0] ) ){
			for ( var key in o ){
				if ( !UI.isFunction( o[key] ) ) f( key, o[key] );
			}
		}else{
			for( var i = 0, n = o.length;i < n;i++ ){
				if ( !UI.isFunction( o[i] ) ) f( o[i], i );
			}
		}
	},
	/**
	 * 判断是否为Undefined
	 *
	 * @param {Object} Object 对象
	 * @return {Boolean} Boolean 是否为Undefined
	 * @example
	 * UI.isUndefined( obj );
	 */
	isUndefined : function( o ) {
		return typeof o == 'undefined';
	},
	/**
	 * 判断是否为函数对象
	 *
	 * @param {Object} Object 对象
	 * @return {Boolean} Boolean 是否为函数对象
	 * @example
	 * UI.isFunction( obj );
	 */
	isFunction : function( o ) {
		return this.getType( o ) == 'Function';
	},
	getType : function( o ){
		return Object.prototype.toString.call( o ).slice( 8, -1 );
	},
	/**
	 * 字符串相关方法
	 *
	 * @type {Number}
	 * @example
	 * MI.string.length( '中en' ); //获取中英文总字符长度
	 * MI.string.html( '<div>' ); //替换<和>
	 * MI.string.cut( '我是xhlv', 4, '' ); //按字符长度裁剪字符串
	 */
	string : {
		length : function( str ){
			var arr = str.match( /[^\x00-\x80]/g );
			return str.length + ( arr ? arr.length : 0 );
		},
		escape : function( str ){
			return MI.string.html( str ).replace( /'/g, "\\'" );
		},
		escapeReg : function( str ){
			var buf = [];
			for( var i = 0;i < str.length;i++ ){
				var c = str.charAt( i );
				switch ( c ) {
					case '.' : buf.push( '\\x2E' );break;
					case '$' : buf.push( '\\x24' );break;
					case '^' : buf.push( '\\x5E' );break;
					case '{' : buf.push( '\\x7B' );break;
					case '[' : buf.push( '\\x5B' );break;
					case '( ' : buf.push( '\\x28' );break;
					case '|' : buf.push( '\\x28' );break;
					case ' )' : buf.push( '\\x29' );break;
					case '*' : buf.push( '\\x2A' );break;
					case '+' : buf.push( '\\x2B' );break;
					case '?' : buf.push( '\\x3F' );break;
					case '\\' : buf.push( '\\x5C' );break;
					default : buf.push( c );
				}
			}
			return buf.join( '' );
		},
		html : function( str ){
			return str.replace( /</g, "&lt;" ).replace( />/g, "&gt;" );
		},
		cut : function( str, num, replace ){
			replace = UI.isUndefined( replace ) ? '...' : replace;
			var arrNew = [],
				strNew = '',
				arr,
				length = MI.string.length( str );
			if ( length > num ) {
				arr = str.split( '' );
				for ( var i = 0, len = arr.length;i < len;i++ ) {
					if ( num > 0 ) {
						arrNew.push( arr[i] );
						num -= MI.string.length( arr[i] );
					} else {
						break;
					}
				}
				strNew = arrNew.join( '' ) + replace;
			}
			else {
				strNew = str;
			}
			return strNew;
		},
		id : function( str ){
			return str.match( /[^\/]+$/g )[0].replace( '#M', '' );
		}
	},
	countUrl : 1,
	txtTopic : '#输入话题标题#',
	countTxt : function( obj, id ){
		var Self = this,
			value = obj.val(),
			talkTip,
			length,
			len,
			url = value.match(new RegExp('((news|telnet|nttp|file|http|ftp|https)://){1}(([-A-Za-z0-9]+(\\.[-A-Za-z0-9]+)*(\\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\\.[0-9]{1,3}){3}))(:[0-9]*)?(/[-A-Za-z0-9_\\$\\.\\+\\!\\*\\(\\),;:@&=\\?/~\\#\\%]*)*','gi')) || [],
			urlExceed = 0,
			urlNum = 0;
		if ( id == 'chatTxt' ){
			value += '@' + $( '#username' ).val() + ' ';
		}
		Self.length = length = UI.string.length( value );
		if ( length < 500 ) {
			if ( Self.countUrl ) {
				UI.each( url, function( o ){ //Dont's Match Small Url And Big Url
					value = value.replace( o, '_' );
					urlNum++;
					len = o.length;
					if ( len > 256 ) { //o.length > 20 &&
						urlExceed += len - 256;
					}
				} );
			}
			Self.length = length = Math.ceil(( UI.string.length( UI.trim( value ).replace( new RegExp( Self.txtTopic, 'g' ), '' ) ) + urlNum * 20 + urlExceed ) / 2 );
		}
		return length;
	},

	delBox : {
		show : function ( obj ){
			var htmlC = '<div class="delChose"><div class=\"tiptext\">确定删除这条私信？</div><input type="button" value="确定" class="ok">&nbsp;&nbsp;<input type="button" value="取消" class="cancel"></div>',
				p = obj.offset(),
				that = this;
			if ( $( '.delChose' ).length < 1 ){
				$( 'body' ).append( htmlC );
			}
			$( '.delChose' ).find( '.ok' ).unbind().bind( 'click', function(){
				$.post( './index.php?m=a_boxdel', { 'tid' : obj.attr( 'id' ) }, function ( json ){
					var data;
					try{
						data = $.parseJSON(json);
						if ( data.code == 0 ){
							obj.parent().parent().fadeOut( 'slow');
						}else{
							alert("删除失败\n错误码:"+data.code+"\n消息:"+data.msg);
						}
					}catch(err){
						alert("服务器返回的数据不正确");
					};
				} );
				that.close();
			} );
			$( '.delChose' ).find( '.cancel' ).unbind().bind( 'click', function(){
				that.close();
			} );
			$( '.delChose' ).css( { 'left' : p.left-15, 'top' : p.top-35 } );
		},

		close : function (){
			$( '.delChose' ).remove();
		}
	},

	/*
	 * 针对IE获取浮出层的顶部位置
	 *
	 */
	getTopForIE : function ( obj ){
		var windowHeight,
			_scrollTop,
			_top;
		if ( self.innerHeight ) { //all except Explorer
			windowHeight = self.innerHeight;
		} else if ( document.documentElement && document.documentElement.clientHeight ) { //Explorer 6 Strict Mode
			windowHeight = document.documentElement.clientHeight;
		} else if ( document.body ) { //other Explorers
			windowHeight = document.body.clientHeight;
		}
		_scrollTop = $( window ).scrollTop();
		_top = windowHeight / 2 - 86 + _scrollTop;
		$( obj.el ).css( { 'margin-top' : 0, 'top' : _top + 'px', 'margin-left' : obj.marginLeft } );
	},
	
	/*
	 * 获取body的高度
	 *
	 */
	getBodyHeight : function (){
		var yScroll;  
		if (window.innerHeight && window.scrollMaxY) {  
		  yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		  yScroll = document.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		  yScroll = document.body.offsetHeight;
		}

		var windowHeight;
		if (self.innerHeight) {  // all except Explorer
		  windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		  windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
		  windowHeight = document.body.clientHeight;
		}  

		// for small pages with total height less then height of the viewport
		if (yScroll < windowHeight){
		  pageHeight = windowHeight;
		} else {
		  pageHeight = yScroll;
		}
		return pageHeight;
	},

	setBgHeight : function (){
		$( '.D' ).find( '.bg' ).css( 'height', this.getBodyHeight() + 'px' );
	},

	checkOnlyTitle : function ( str ) {
		if ( str.search( /^#.*#$/ ) == -1 ){
			return false;
		} else {
			var countSingle = str.split( '#' ).length - 1,
				countDouble = str.split( '##' ).length - 1;
			if ( countDouble == ( countSingle - 2 ) / 2 ){
				return true;
			} else {
				return false;
			}
		}
	},

	isCtrlEnter : function ( e, func ){
		var e = e || window.event;
		if (e.keyCode == 13 || e.keyCode == 10/* IE8在同时按下ctrl+enter时 keycode为10 */) {
			if(e.ctrlKey){
				func();
			}
		}
	}
}
