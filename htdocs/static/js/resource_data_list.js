//增加资源统计业务
function addResourceApp(e){
    var iAppId  = $('#selectApp').val();
    var iAppType = $('#selectAppType').val();
    var data = {};
    data['iAppId'] = iAppId;
    data['iAppType'] = iAppType;
    return data;
}
//添加业务配额是传的配额类型参数
function getQuotaNum(e){
    var quotaNum = $('#'+e).val();
    //参数验证
    if(isNaN(quotaNum) || quotaNum=='' || typeof quotaNum==undefined || quotaNum<0){
        error('请输入正确的配额参数！');
        return false;
    }
    var data = {};
    data['quotaNum'] = quotaNum;
    return data;
}


function saveQuotaErrorTips(e){
    if(e.retCode !=0 ){
        var error = e.retInfo;
        $('#exportDateTips').html(error); 
    }else{   
        //succeed(e.retInfo);//这里不需要提示
        $('#exportModal').modal( 'hide' );
        $('#saveSchedule').submit();
    }           
}

function saveQuotaList(){
    var startDate = $('input[name=startDate]').val();
    var endDate = $('input[name=endDate]').val(); 
    if(!startDate || !endDate){        
        error('请输入导出的起始日期！');
        return false;
    }
    return true;
}