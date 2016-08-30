/**
 * Created by zhaojingsi on 2015/9/29.
 */

var CreateInfo = {
    info : ''
};

//生成资源类型
CreateInfo.doRes = function(){
    var _resId = (typeof CreateInfo.info.iResId != 'undefined') ? parseInt( CreateInfo.info.iResId ) : 0;
    var resCfg = FORMCFG.RES_CFG, _resName = resCfg[_resId];

    var _applyType = FORMCFG.APPLYTYPE_CFG;
    var _applyTypeId = (typeof CreateInfo.info.iApplyType != 'undefined') ? parseInt( CreateInfo.info.iApplyType ) : 0;

    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">资源类型：</label>\
    <div class="col-sm-4">\
    <p class="text">'+_resName+'</p>\
    </div>';

    if (_applyTypeId > 0) {
        _html += '';
    }

    _html += '</div>';
};

CreateInfo.init = function(optInfo){
    var opt = {};
    $.extend(opt, optInfo);

    //来自于前端的数据
    if (typeof opt.data != 'undefined') {
        CreateInfo.info = opt.data;
    }


};