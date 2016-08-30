(function($){
    $.fn.extend({
        pagination: function(opts){
            var setting = {
                first$:"li.j-first", prev$:"li.j-prev", next$:"li.j-next", last$:"li.j-last", nums$:"li.j-num>a", jumpto$:"li.jumpto",
                pageNumFrag:'<li class="#liClass#"><a href="javascript:;">#pageNum#</a></li>'
            };

            var frag = '<ul><li class="j-first"><a class="first" href="javascript:;"><span>首页</span></a><span class="first"><span>首页</span></span></li>	<li class="j-prev">		<a class="previous" href="javascript:;"><span>上一页</span></a>		<span class="previous"><span>上一页</span></span>	</li>	#pageNumFrag#	<li class="j-next">		<a class="next" href="javascript:;"><span>下一页</span></a>		<span class="next"><span>下一页</span></span>	</li>	<li class="j-last">		<a class="last" href="javascript:;"><span>末页</span></a>		<span class="last"><span>末页</span></span>	</li>	<li class="jumpto"><input class="textInput" type="text" size="4" value="#currentPage#" /><input class="goto" type="button" value="确定" /></li></ul>';
            if($('#pagerForm').length == 0){
                $(body).append('<form id="pagerForm" action="' + location.href + '" ></form>');
            }

            if(opts.numPerPage){
                var perPage = opts.numPerPage;
            }else{
                var perPage = 15;
            }
            if(opts.currentPage){
                var inputPageNum = opts.currentPage;
            }else{
                var inputPageNum = 1;
            }
            $('#pagerForm').append('<input type="hidden" name="numPerPage" value="' + perPage + '">');
            $('#pagerForm').append('<input type="hidden" name="pageNum" value="' + inputPageNum + '">');
            if(opts.totalCount==0){
                return false;
            }
            return this.each(function(){
                var $this = $(this);
                var pc = new Pagination(opts);
                var interval = pc.getInterval();

                var pageNumFrag = '';
                for (var i=interval.start; i<interval.end;i++){
                    pageNumFrag += setting.pageNumFrag.replaceAll("#pageNum#", i).replaceAll("#liClass#", i==pc.getCurrentPage() ? 'selected j-num' : 'j-num');
                }

                $this.html(frag.replaceAll("#pageNumFrag#", pageNumFrag).replaceAll("#currentPage#", pc.getCurrentPage()));

                var $first = $this.find(setting.first$);
                var $prev = $this.find(setting.prev$);
                var $next = $this.find(setting.next$);
                var $last = $this.find(setting.last$);

                if (pc.hasPrev()){
                    $first.add($prev).find(">span").hide();
                    _bindEvent($prev, pc.getCurrentPage()-1, pc.rel());
                    _bindEvent($first, 1, pc.rel());
                } else {
                    $first.add($prev).addClass("disabled").find(">a").hide();
                }

                if (pc.hasNext()) {
                    $next.add($last).find(">span").hide();
                    _bindEvent($next, pc.getCurrentPage()+1, pc.rel());
                    _bindEvent($last, pc.numPages(), pc.rel());
                } else {
                    $next.add($last).addClass("disabled").find(">a").hide();
                }

                $this.find(setting.nums$).each(function(i){
                    _bindEvent($(this), i+interval.start, pc.rel());
                });
                $this.find(setting.jumpto$).each(function(){
                    var $this = $(this);
                    var $inputBox = $this.find(":text");
                    var $button = $this.find(":button");
                    $button.click(function(event){
                        var pageNum = $inputBox.val();
                        if (pageNum && pageNum.isPositiveInteger()) {
                            lyPageLoad({rel:pc.rel(), data: {pageNum:pageNum}});
                        }
                    });
                    $inputBox.keyup(function(event){
                        if (event.keyCode == 13) $button.click();
                    });
                });
            });

            function _bindEvent($target, pageNum, rel){
                $target.bind("click", {pageNum:pageNum}, function(event){
                    lyPageLoad({rel:rel, data:{pageNum:event.data.pageNum}});
                    event.preventDefault();
                });
            }
        },

        orderBy: function(options){
            var op = $.extend({rel:"", asc:"asc", desc:"desc"}, options);
            return this.each(function(){
                var $this = $(this).css({cursor:"pointer"}).click(function(){
                    var orderField = $this.attr("orderField");
                    var orderDirection = $this.hasClass(op.asc) ? op.desc : op.asc;
                    lyPageLoad({rel:op.rel, data:{orderField: orderField, orderDirection: orderDirection}});
                });

            });
        }
    });

    var Pagination = function(opts) {
        this.opts = $.extend({
            rel:"", //用于局部刷新div id号
            totalCount:0,
            numPerPage:10,
            pageNumShown:10,
            currentPage:1,
            callback:function(){return false;}
        }, opts);
    }

    $.extend(Pagination.prototype, {
        rel:function(){return this.opts.rel},
        numPages:function() {
            return Math.ceil(this.opts.totalCount/this.opts.numPerPage);
        },
        getInterval:function(){
            var ne_half = Math.ceil(this.opts.pageNumShown/2);
            var np = this.numPages();
            var upper_limit = np - this.opts.pageNumShown;
            var start = this.getCurrentPage() > ne_half ? Math.max( Math.min(this.getCurrentPage() - ne_half, upper_limit), 0 ) : 0;
            var end = this.getCurrentPage() > ne_half ? Math.min(this.getCurrentPage()+ne_half, np) : Math.min(this.opts.pageNumShown, np);
            return {start:start+1, end:end+1};
        },
        getCurrentPage:function(){
            var currentPage = parseInt(this.opts.currentPage);
            if (isNaN(currentPage)) return 1;
            return currentPage;
        },
        hasPrev:function(){
            return this.getCurrentPage() > 1;
        },
        hasNext:function(){
            return this.getCurrentPage() < this.numPages();
        }
    });
})(jQuery);

/**
 * 写入pagerFrom分页参数
 */
function lyPageLoad(opts){
    var op = $.extend({rel:"", data:{pageNum:"", numPerPage:"", orderField:"", orderDirection:""}, callback:null}, opts);
    $pagerForm = $('#pagerForm');
    $pagerForm.append('<input type="hidden" name="pageNum" value="' + op.data.pageNum + '">');
    pageLoad(op.rel);
}

/**
 * 载入页面
 */
function pageLoad(rel){
    var url = $('#pagerForm').attr('action');
    if(url == '' || typeof(url) == 'undefined'){
        url = location.href;
        $('#pagerForm').attr('action',url);
    }
    if(rel && $('#'+rel).length > 0){
        $.post(url,$('#pagerForm').serialize(),function(e){
            $('#'+rel).html(e);
        });
    }else{
        $('#pagerForm').submit();
    }
}

//搜索事件绑定
function handleSearch(){
    //弹框加载html
    $('.searchBtn').on('click', function (e) {
        //调用需要的验证的函数
        var preFunc = $(this).attr('data-preData');
        var preParams = $(this).attr('data-preParams');
        if(preFunc != '' && typeof(preFunc) != 'undefined'){
            if(preParams == '' || typeof(preParams) == 'undefined'){
                data = window[preFunc]();
            }else{
                data = window[preFunc](preParams);
            }
            if(!data){
                return false;
            }
        }    
        
        //搜索时将分页置为1
        var pageNumObj = $('#pagerForm').find('input[name="pageNum"]');
        pageNumObj.val(1);
        var pageCurrentObj = $('#pagerForm').find('input[name="numPerPage"]');
        $('#pagerForm').html('');
        $('#pagerForm').append(pageCurrentObj);
        $('#pagerForm').append(pageNumObj);
        var formObj = $(this).parentsUntil($('body'),'form');
        var data = formObj.serialize();
        if(data != ''){
            var dataArray = data.split('&')
            for(var i =0 ; i < dataArray.length;i++){
                var keyValue = dataArray[i];
                console.log(keyValue);
                var tmp = keyValue.split('=');
                var key = tmp[0];
                var value = tmp[1];
                $('#pagerForm').append('<input type="hidden" name="'+ key +'" value="'+ value +'"/>');
            }
        }
        pageLoad();
        e.preventDefault();
    });
}handleSearch();
