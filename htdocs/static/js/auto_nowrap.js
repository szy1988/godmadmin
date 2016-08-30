/**
 * Created by zhaojingsi on 2015/9/30.
 */
(function($) {
    $.fn.AutoNowrap = function(){
        $(this).on('mouseover', function(e) {
            var _div = $("#show_wap_msg");
            if (_div.length == 0) {
                $('<div id="show_wap_msg"></div>').appendTo('body');
            }
            var _left = parseInt($(this).offset().left);
            _div.css('top',$(this).offset().top);

            //alert($(this).css('width'));

            var _divWidth = parseInt(_div.css('width'));

            //获得可视区域的宽度
            var _displayWidth = parseInt($(window).width());

            if (_divWidth > (_displayWidth - _left)) {
                _left = _displayWidth - _divWidth;
            }
            _div.css('left',_left);

            //alert($(this).children().width());

            //_div.css('width', $(this).css('width'));
            //重新设置宽度

            _div.html($(this).html()).show();
        });

        $(document).on('mouseleave', '#show_wap_msg', function(e) {
            $(this).hide();
        });
    };
})(jQuery);
