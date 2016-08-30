/**
* 消息提示
*/
var message = (function () {
    if(window.parent && window.parent != window && window.parent.idata && (window.parent.message || window.parent.idata.message)) {
        return window.parent.message || window.parent.idata.message;
    }
    var msg = function () {
        this.cache = {};
    }

    /**
    * 弹出提示信息
    **/
    msg.prototype.show = function (msg, key, css, option) {
        this.option = option || {};
        var box = this.option.auto ? (this.cache[key] || (this.cache[key] = $('<div class="msg-box"></div>'))) : $('<div class="msg-box"></div>');        
       
        //如果为模式窗口
        if (option.modal) {
            if (!box.bg) box.bg = $('<div class="msg-box-modal" />');
            box.bg.remove();
            box.bg.appendTo('body').show();
        }

        box.appendTo('body');
        box.toggleClass(css,true);
        if(this.option.position) {
            box.css(this.option.position);
        }
        box.html(msg);
        if(option.canClose) {
            $('<a href="#" class="msg-box-close">X</a>').appendTo(box).click(function() {
                this.parentElement.style.display='none';
                return false;
            });
        }
        
        /*
        *关闭当前弹框
        */
        box.close = function () {
            this.hide();
            if (this.bg) this.bg.remove();
        };
        box.show();
        var onsize = function () {
            (message || idata.message).position(box);
            if (box.bg) {
                var w = Math.max($(window).width(), $(window.document).width());
                var h = Math.max($(window).height(), $(window.document).height());
                box.bg.width(w);
                box.bg.height(h);
            }
        };
        onsize();
        $(window).bind('resize', onsize);
        if (this.option.auto != false) {
            setTimeout(function () {
                box.animate({
                    //'top': 0,
                    'opacity': 0
                }, 2000, function () {
                    $(window).unbind('resize', onsize);
                    box.close();
                })
            }, 5000);
        }
        //iflow.log(msg);//调试日志
        return box;
    };

    /**
     * 隐藏
     */
    msg.prototype.close = function (key) {
        var box = this.cache[key];
        if (box) {
            box.hide();
            if (box.bg) box.bg.remove();
        }
    }

    //定位消息框
    msg.prototype.position=function (box) {
        var pos = this.option.position || {};
        var l = pos.left || ($(window.document).width() - box.width()) / 2;
        box.css({ 'left': l, 'opacity': 1, 'top': pos.top || 10 });
    };

    /**
    * 出错信息
    **/
    msg.prototype.error= function (msg, option) {   
        if(typeof option != 'object') {
            option = {auto:option,canClose:true};
        }
        option = $.extend({auto:true,canClose:true},option);   
        this.show(msg, 'idata.error', 'msg-box-error', option);
    };

    /**
    * 警告信息
    **/
    msg.prototype.warning= function (msg, option) {
        if(typeof option != 'object') {
            option = {auto:option,canClose:true};
        }    
        option = $.extend({auto:true,canClose:true},option); 
        //todo
        if($('.msg-box-warning').css('display') != 'block')
            this.show(msg, 'idata.warning', 'msg-box-warning', option,false,true);
    }

    /**
    * 成功信息
    **/
    msg.prototype.success = function (msg, option) {
        if(typeof option != 'object') {
            option = {auto:option,canClose:true};
        }
        option = $.extend({auto:true,canClose:true},option); 
        this.show(msg, 'idata.warning', 'msg-box-success', option,false,true);
    }

    /**
    * 提示信息
    **/
    msg.prototype.tip= function (msg, option) {
        if(typeof option != 'object') {
            option = {auto:option,canClose:true};
        }    
        option = $.extend({auto:true,canClose:true},option); 
        return this.show(msg, 'idata.tooltip', 'msg-box-info', option,false,true);
    }

    /*
    * 弹出等待框
    */
    msg.prototype.showWaiting = function (msg,option){
        if(typeof option != 'object') {
            option = {canClose:false};
        }    
        option = $.extend({auto:false,canClose:true,modal:true},option); 
        return this.show('<div class="loading">'+msg+'</div>', 'idata.waiting', 'msg-box-info', option, true,false);
    }
    return new msg();
})();