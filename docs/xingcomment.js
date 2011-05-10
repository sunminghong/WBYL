$(function()
{
    $('#msg').click(function()
    {
       if($('#msg').attr('class')=='area01'&&$('#msg').val()!='')   
       {
           $(this).val('');
       }
       $(this).attr('class','area02');
    });
});

var f_comment=false;
var f_support=false;

function addcomment()
{
    if(f_comment)
    {   
        alert('你已经评论过了!');
        return;
    }
    if($('#msg').attr('class')=='area01'&&$('#msg').val()!='')
    {
        $('#msg').attr('class','area02');
        $('#msg').val('');
        return;
    }
    if($('#msg').val().length<4||$('#msg').val().length>500)
    {
        alert('评论字数在3-500之间!');
        $('#msg').focus();
        return;
    }
    $.ajax(
    {
        type: "POST",
        url: '/comment/xingcomment.php',
        data: 'aid='+aid+'&arctitle='+arctitle+'&username='+$('#username').val()+'&msg='+$('#msg').val()+'&op=addcomment',
        success: function(result)
        {
            if(result.indexOf('内容')!=-1)
            {
                alert(result);
                return;
            }
            f_comment=true;
            $('.commlist').html(result);
            $('#em_num').text(parseInt($('#em_num').text())+1);
            $('#msg').val('');
            if(typeof show_game == 'function')
            {
                alert('发图片才能参加活动!');
            }
        }
    });
}

function addcommentbypic(pic)
{
    if(f_comment)
    {   
        alert('你已经评论过了!');
        return;
    }
    if($('#msg').attr('class')=='area01'&&$('#msg').val()!='')
    {
        $('#msg').attr('class','area02');
        $('#msg').val('');
        return;
    }
    if($('#msg').val().length<4||$('#msg').val().length>500)
    {
        alert('评论字数在3-500之间!');
        $('#msg').focus();
        return;
    }
    $.ajax(
    {
        type: "POST",
        url: '/comment/xingcomment.php',
        data: 'aid='+aid+'&arctitle='+arctitle+'&username='+$('#username').val()+'&msg='+$('#msg').val()+'&pic='+pic+'&op=addcomment',
        success: function(result)
        {
            if(result.indexOf('内容')!=-1)
            {
                alert(result);
                return;
            }
            f_comment=true;
            $('.commlist').html(result);
            $('#em_num').text(parseInt($('#em_num').text())+1);
            $('#msg').val('');
            setpic();
            if(typeof show_game == 'function')
            {
                show_game();
            }
        }
    });
}

function support(id)
{
    if(f_support)
    {
        alert('你已经支持过了!');
        return;
    }
    $.ajax(
    {
        type: "POST",
        url: '/comment/xingcomment.php',
        data: 'aid='+aid+'&id='+id+'&op=support',
        success: function(result)
        {
            f_support=true;
            $('.commlist').html(result);
            if(typeof setpic == 'function')
            {
                setpic();
            }
        }
    });
}

function recomment(id,floor)
{
    if(f_comment)
    {   
        alert('你已经评论过了!');
        return;
    }
    if($('#remsg').val().length<4||$('#remsg').val().length>500)
    {
        alert('评论字数在3-500之间!');
        $('#remsg').focus();
        return;
    }
    $.ajax(
    {
        type: "POST",
        url: '/comment/xingcomment.php',
        data: 'aid='+aid+'&arctitle='+arctitle+'&msg='+$('#remsg').val()+'&id='+id+'&floor='+floor+'&op=recomment',
        success: function(result)
        {
            if(result.indexOf('内容')!=-1)
            {
                alert(result);
                return;
            }
            f_comment=true;
            $('.commlist').html(result);
            $('#em_num').text(parseInt($('#em_num').text())+1);
        }
    });
}

function back(obj,id,floor)
{
    if($('.re_area').length==0)
    {
        var html='<div class="re_area"><a href="javascript:void(0)" title="关闭" class="com_colse">X</a><div><textarea id="remsg" cols="" rows=""></textarea></div><input type="button" class="btn_sent" onclick="recomment('+id+','+floor+')"/></div>';
        $(obj).parent().parent().parent().append(html);
    }
}

$(function()
{
    $('.com_colse').live('click',function()
    {
        $('.re_area').remove();
    });
});

function showlist(p)
{
    var t;
    if(typeof(type)=="undefined")
    {
        t=0;
    }
    else
    {
        t=type;
    }
    $.ajax(
    {
        type: "POST",
        url: '/comment/xingcomment.php',
        data: 'aid='+aid+'&page='+p+'&type='+t+'&op=showlist',
        success: function(result)
        {
            $('.commlist').html(result);
            if(typeof setpic == 'function')
            {
                setpic();
            }
            location='#a_comment';
        }
    });
}

$('body').keydown(function(e)
{
    if(e.keyCode==13&&e.ctrlKey)
    {
        addcomment();
    }
});

var f_xq=false;

$(function()
{
    $('.pagebreak input').live('click',function()
    {
        if(f_xq)
        {
            alert('你已经选择过了!');
            return;
        }
        var xqnum=$(this).val();
        $.ajax(
        {
            type: "POST",
             url: '/comment/xingcomment.php',
            data: 'aid='+aid+'&xqnum='+xqnum+'&op=xq',
            success: function(result)
            {
                $('.pagebreak').html(result);
                f_xq=true;
            }
        });
    }); 
});

function setpic()
{
    $('.commlist img').each(function(i,n)
    {
        var myimg,oldwidth; 
        var maxwidth=700;
        myimg = $(n); 
        if(myimg.width()>maxwidth) 
        { 
            oldwidth = myimg.width(); 
            myimg.width(maxwidth); 
            myimg.height(myimg.height()*(maxwidth/oldwidth)); 
        } 
    });
}