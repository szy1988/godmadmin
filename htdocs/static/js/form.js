
function handleAtags(){
    $(document).on('click','a',function (e) {   
        var url = $(this).attr('href');
        var title = $(this).attr('title');
        var preFunc = $(this).attr('data-preData');
        var preParams = $(this).attr('data-preParams');
        var confirm = $(this).attr('data-confirm');//是否发出提醒
        var target = $(this).attr('target');
        var aftFunc = $(this).attr('data-aftFunc');
        var aftParams = $(this).attr('data-aftParams');
        var isAction = true;      
        if(preFunc != '' && typeof(preFunc) != 'undefined'){
            if(preParams == '' || typeof(preParams) == 'undefined'){
                data = window[preFunc]();
            }else{
                data = window[preFunc](preParams);
            }
            if(!data){
                return false;
            }
        }else{
            data = {};
        }

        switch(target){
            case 'dialog':
                if(title == '' || typeof(title) == 'undefined'){
                    title = '窗口';
                }
                $.post(url,data,function(e){
                    art.dialog({
                        title: title,
                        content: e,
                        lock: true
                        });
                    if(typeof(aftFunc) != 'undefined'){
                        window[aftFunc](aftParams);
                    }
                });
                break;
            case 'subform':
                var form = $(this).parentsUntil('body','form');
                var formData = form.serialize();
                if(typeof(data) == 'object'){
                    formData = formData + '&' + parseParam(data);
                }else{
                    formData = formData + '&' + data;
                }
                $.post(url,formData,function(e){
                    if(typeof(aftFunc) != 'undefined'){
                        window[aftFunc](aftParams);
                    }
                    ajaxDone(e);
                },'json');
                break;
            case 'ajax':
                if(confirm == 1 || typeof(confirm) == 'undefined'){
                    if(title == '' || typeof(title) == 'undefined'){
                        title = '您确定要执行这边操作吗？';
                    }
                    art.dialog({
                        title: '提示',
                        lock: true,
                        zIndex:9999,
                        //background: '#438eb9', // 背景色
                        opacity: 0.30,  // 透明度
                        content: title,
                        ok: function () {
                            closeAll();
                            $.post(url,data,function(e){
                                if(typeof(aftFunc) != 'undefined'){
                                    window[aftFunc](aftParams);
                                }
                                ajaxDone(e);
                            },'json');
                            return false;
                        },
                        cancelVal: '关闭',
                        cancel: true 
                    });
                }else{
                    $.post(url,data,function(e){
                        if(typeof(aftFunc) != 'undefined'){
                            window[aftFunc](aftParams);
                        }
                        ajaxDone(e);
                    },'json');
                    return false;
                }
                break;
            default:
                isAction = false;
        }

        if(isAction){
            e.preventDefault();
        }
    });

}handleAtags();

/**
 * 延时跳转
 * @param  {[type]} e   [description]
 * @param  {[type]} url [description]
 * @return {[type]}     [description]
 */
function timeDelay(e,url){
    //5秒后跳转
    var timer;
    art.dialog({
        content: e,
        init: function () {
            var that = this, i = 2;
            var fn = function () {
                that.title(i + '秒后跳转');
                !i && that.close();
                i --;
            };
            timer = setInterval(fn, 1000);
            fn();
            if(url == 'self'){
                if($('#pagerForm').length > 0){
                    setTimeout(function(){$('#pagerForm').submit();},2000);
                }else{
                    setTimeout(function(){location.reload();},2000);
                }
            }else{
                setTimeout(function(){window.location= url;},2000);
            }
            
        },
        lock: true,
        icon: 'succeed',
        close: function () {
            clearInterval(timer);
        }
        })
}

/**
 * ajax回调函数
 */
function ajaxDone(ajax){
    //更新页面元素
    //TODO:加入前端反馈效果
    if(typeof ajax.data.retRep != 'undefined'){
        for (var item in ajax.data.retRep) {
            $(item).html(ajax.data.retRep[item]);
        }
    }
    if(typeof ajax.data.url != 'undefined'){
        timeDelay(ajax.retInfo,ajax.data.url);
        return;
    }
    if(typeof ajax.data.callBack != 'undefined'){
        if(ajax.data.callBack != 'nothing'){
            window[ajax.data.callBack](ajax);
        }
        return;
    }
    if(typeof(ajax.data.retNotice) == 'undefined' || ajax.data.retNotice == 1){
        if(ajax.data.retChoose){
            var content = '<p style="  text-align: center;padding: 10px;font-size: 14px;">'+ ajax.retInfo +'</p>';
            var button = '';
            for(var i in ajax.data.retChoose){
                var iClass = typeof(ajax.data.retChoose[i].class) == 'undefined' ? '':ajax.data.retChoose[i].class;
                button = button + '<a style="margin-left: 10px;" class="btns '+ iClass +'" href="'+ ajax.data.retChoose[i].href +'">'+ ajax.data.retChoose[i].name +'</a>';
            }
            var content = content + '<p>'+button+'</p>';
        }else{
            content = ajax.retInfo;
        }
        if(ajax.retCode == '0'){
            succeed(content);
            return;
        }else{
            error(content);
            return;
        }
    }
}


/**
 * 使用pagerForm重载页面
 */
function lyReload(e){
    timeDelay(e.retInfo,'self');
}

//succeed error warning 三种状态
function succeed(e){
    art.dialog({
        icon: 'succeed',
        content: e,  
        lock: true
    });
};

function error(e){
    art.dialog({
        icon: 'error',
        content: e,  
        lock: true
    });
};

function warning(e){
    art.dialog({
        icon: 'warning',
        content: e,  
        lock: true
    });
};


//关闭所有窗口
function closeAll(){
    var list = art.dialog.list;
    for (var i in list) {
        list[i].close();
    }
}

//扩展js方法
var parseParam=function(param, key){
    var paramStr="";
    if(param instanceof String||param instanceof Number||param instanceof Boolean){
        paramStr+="&"+key+"="+encodeURIComponent(param);
    }else{
        $.each(param,function(i){
            var k=key==null?i:key+(param instanceof Array?"["+i+"]":"."+i);
            paramStr+='&'+parseParam(this, k);
        });
    }
    return paramStr.substr(1);
};