//上移时传递参数
function getUpScheduleData(e){//{id:1,o:2}
    eval("obj = "+ e+";");
    var curId = obj.id;//当前排期ID
    var index = obj.index;
    if(curId){
        var data = {};
        var $tr_td = $('#id_'+curId).parent().prev().find('td[data-index='+index+']');
        var id = $($tr_td).attr('id');
        var idArr = id.split('_');
        var exId = idArr[1];//交换排期ID
        data['curId'] = curId;
        data['exId'] = exId;
        return data;
    }
}
function getPagerFormData(){
    var data = {};
    $('#pagerForm>input').each(function(){
        data[$(this).attr('name')] = $(this).val();
    });
    return data;
}

//下移时传递参数
function getDownScheduleData(e){
    eval("obj = "+ e+";");
    var curId = obj.id;//当前排期ID
    var index = obj.index;
    if(curId){
        var data = {};
        var $tr_td = $('#id_'+curId).parent().next().find('td[data-index='+index+']');
        var id = $($tr_td).attr('id');
        var idArr = id.split('_');
        var exId = idArr[1];//上一级排期ID
        data['curId'] = curId;
        data['exId'] = exId;
        return data;
    }
}

//post方法（当前url），
function adJustCallBack(data){    
    pageData = getPagerFormData();
    $.post('',pageData,function(e){//e从后端传过来的html
        var obj = $(e).find('.table-responsive');
        obj.find(".td-game-info").toggleClass("td-adjust");
        $('.table-responsive').html(obj);
    });
}
//保存排期，无实际逻辑，提示成功和生成排期按钮
function saveSchedule(){
    //如果调整按钮显示则关闭调整按钮  
    if($(".td-game-info").hasClass("td-adjust")){
        $(".td-game-info").toggleClass("td-adjust");
    }    
    var _html ='<div class="saveSchedule"><span>您已保存成功!</span><a href="javascript:generateSchedule();" class="btn">生成排期</a></div>'
    succeed(_html);
}
//生成排期，检查进度，进度为100%，弹出
function generateSchedule(){
    closeAll();
    //如果调整按钮显示则关闭调整按钮  
    if($(".td-game-info").hasClass("td-adjust")){
        $(".td-game-info").toggleClass("td-adjust");
    }
    $.get('index.php?module=time&action=process_info&func=getProcessPercent&dtDate=2015-10-27',{},function(e){//e从后端传过来的html
        eval("obj = "+ e+";");
        var process = 0;
        if(obj.retCode == 0){
            if(typeof obj.data != 'undefined' && (obj.data.complete>=1) ){
                process = (obj.data.complete/obj.data.total*100).toFixed(2);
            }
        }
        var _html ='<div class="genSchedule"><span>资源申请进度：'+process+'</span><a href="index.php?module=time&action=process_info" target="_blank" class="btn">查看进度</a><a href="javascript:sGenSchedule();" class="btn">生成排期</a></div>';
        var msg = '排期生成成功!';
        succeed(_html);
    });
    
}

function sGenSchedule(){
    closeAll();
    var msg = '排期生成成功!';
    succeed(msg);
}
//查询的时候进行时间比较，开始时间不能大于结束时间，时间段不能超过一个月31天
function timeCompare(e){
    var startDate = $('input[name=startDate]').val();
    var endDate = $('input[name=endDate]').val();
    var startTime = (new Date(startDate)).getTime();
    var endTime = (new Date(endDate)).getTime();
    if(endTime<startTime){
        error('开始日期不能大于结束日期！');
        $('input[name=startDate]').attr('value',getToday());
        $('input[name=endDate]').attr('value',GetDateStr(getToday(),6));
        return false;
    }else{
        var days = daydiff(startDate,endDate);
        if(days>e){
            error('不能查询超过30天的排期数据！');
            $('input[name=startDate]').attr('value',getToday());
            $('input[name=endDate]').attr('value',GetDateStr(getToday(),6));
            return false;
        }
    }
    return true;
}

function saveScheduleList(){
    var dateJudge = timeCompare(30);
    if(!dateJudge){return;}
    var startDate = $('input[name=startDate]').val();
    var endDate = $('input[name=endDate]').val();
    var gameAppStr =$('input[name=gameAppStr]').val(); 
    $('#startDateExport').val(startDate);
    $('#endDateExport').val(endDate);
    $('#gameAppStrExport').val(gameAppStr);
    $('#saveSchedule').submit();
}

