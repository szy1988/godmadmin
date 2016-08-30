/**
 * Created by zhaojingsi on 2015/9/21.
 * 生成form类
 */

var CreateForm = {
    'info' : {}
};

/**
 * 获得
 * @constructor
 */
CreateForm.GetResCfg = function(iResId){
    return FORMCFG.FORM_CFG['RES_' + iResId];
};

/**
 * 生成资源的列表
 * @constructor
 */
CreateForm.CResList = function(){
    var _resList = '', resCfg = FORMCFG.RES_CFG, _resId = 0;
    var _resId = (typeof CreateForm.info.iResId != 'undefined') ? parseInt( CreateForm.info.iResId ) : 0;

    _resList += '<option value="0">请选择</option>';

    for (var i in resCfg) {
        if (i == _resId) {
            _resList += '<option value="'+i+'" selected>'+resCfg[i]+'</option>';
        } else {
            _resList += '<option value="'+i+'">'+resCfg[i]+'</option>';
        }
    }
    return _resList;
};

/**
 * 生成素材类型
 * @returns {string}
 * @constructor
 */
CreateForm.CMaterailList = function(resId){
    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">素材类型：</label>\
    <div class="col-sm-4" style="margin-top:6px;">';

    var _materailId = (typeof CreateForm.info.iMaterialId != 'undefined') ? CreateForm.info.iMaterialId : '';
    var _mateCfg = FORMCFG.MATE_CFG['MATE_' + resId];
    
    for (var i in _mateCfg) {
        var _selectStr = '';
        if (_materailId != '') {
            _selectStr = (i == _materailId) ? 'checked' : '';    
        } else {
            if (i == 1) {
                _selectStr = 'checked';
            }
        }
        var _disabled = '';
        if(CreateForm.info.disabled){
            _disabled = 'disabled';
        }
        _html += '<input type="radio" name="iMaterialId" value="'+i+'" '+_selectStr+'  '+_disabled+'/>' + _mateCfg[i] + '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    
    _html += '</div></div>';

    return _html;
};

/**
 * 生成红点申请类型
 * @constructor
 */
CreateForm.CApplyType = function(){
    var _html = '<label class="col-sm-1 control-label text-right">红点类型：</label>';
    _html += '<div class="col-sm-4">';
    _html += '<select class="form-control" style="margin-left: 30px;" name="iApplyType" ';
    _html += CreateForm.info.disabled?'disabled':'';
    _html += '>';

    var _applyType = FORMCFG.APPLYTYPE_CFG;
    var _applyTypeId = (typeof CreateForm.info.iApplyType != 'undefined') ? parseInt( CreateForm.info.iApplyType ) : 0;

    for (var i in _applyType) {
        if (_applyTypeId == i) {
            _html += '<option value="'+i+'" selected>'+_applyType[i]+'</option>';
        } else {
            _html += '<option value="'+i+'">'+_applyType[i]+'</option>';
        }
    }

    _html += '</select>';
    _html += '</div>';

    return _html;
};

CreateForm.getToday = function(){
    var _now = new Date();
    var year = _now.getFullYear();
    var month = _now.getMonth() + 1;
    month = (month < 10) ? "0"+month : month;
    var today = _now.getDate();
    today = (today < 10) ? "0"+today : today;

    return year + '-' + month + '-' + today;
};

/**
 * 生成投放日期
 * @constructor
 */
CreateForm.CResTime = function(){
//    <input type="input" name="dtResTime" id="dtResTime" value="'+_resDate+'" class="form-control validate[required] text-input" data-errormessage-value-missing="投放日期不能为空" onFocus="var data=wp();WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:data[\'min\'],maxDate:data[\'max\'],disabledDates:[data[\'disa\']]})">\
    var _resDate = (typeof CreateForm.info.dtResTime != 'undefined') ? CreateForm.info.dtResTime : $("#canResTime").val();
    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">投放日期：</label>\
    <div class="col-sm-4">\
    <input type="input" name="dtResTime" id="dtResTime" value="'+_resDate+'" class="form-control validate[required,funcCall[valiateResTime]] text-input" data-errormessage-value-missing="投放日期不能为空" onFocus="var date=wp();WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:date[\'min\'],maxDate:date[\'max\']})">\
    </div>\
    </div>';
};

/**
 * 生成投放区间日期
 * @constructor
 */
CreateForm.CResTimeLong = function(){

    var _resDate = (typeof CreateForm.info.dtResTime != 'undefined') ? CreateForm.info.dtResTime : $("#canResTime").val();
    var _resEndDate = (typeof CreateForm.info.dtResEndTime != 'undefined') ? CreateForm.info.dtResEndTime : $("#canResEndTime").val();
    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">投放日期：</label>\
    <div class="col-sm-4">\
    <input type="input" name="dtResTime" id="dtResTime" value="'+_resDate+'" class="form-control-junay validate[required,funcCall[valiateResTime]] text-input" data-errormessage-value-missing="投放日期不能为空" onFocus="var date=wp();WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:date[\'min\'],maxDate:\'#F{$dp.$D(\\\'dtResEndTime\\\')}\'})">\
    --  <input type="input" name="dtResEndTime" id="dtResEndTime" value="'+_resEndDate+'" class="form-control-junay validate[required,funcCall[valiateResTime]] text-input" data-errormessage-value-missing="投放日期不能为空" onFocus="var date=wp();WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:\'#F{$dp.$D(\\\'dtResTime\\\')}\',maxDate:date[\'max\']})">\
    </div>\
    </div>';
};

/**
 * 生成游戏名称
 * @constructor
 */
CreateForm.CGame = function(){
    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">游戏名称：</label>\
    <div class="col-sm-4">\
    <select class="" name="iAppId" id="iAppId" style="width: 270px;margin-top: 4px;">\
    <option value="0">请选择游戏</option>';

    var _appId = (typeof CreateForm.info.iAppId != 'undefined') ? CreateForm.info.iAppId : '';
    var _gameCfg = FORMCFG.GAME_CFG;
    for (var i in _gameCfg) {
        if (i == _appId) {
            _html += '<option value="'+i+'" selected>'+_gameCfg[i]+'</option>';
        } else {
            _html += '<option value="'+i+'">'+_gameCfg[i]+'</option>';
        }
    }
    _html += '</select>\
    </div>\
    </div>';

    return _html;
};

/**
 * 生成活动名称
 * @constructor
 */
CreateForm.CActName = function(){

    var _actName = (typeof CreateForm.info.sActName != 'undefined' && CreateForm.info.sActName != '') ? CreateForm.info.sActName : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动名称：</label>\
    <div class="col-sm-4">\
    <input type="input" value="'+_actName+'" name="sActName" class="form-control validate[required,funcCall[valiateSpeChar]] text-input" data-errormessage-value-missing="活动名称不能为空">\
    </div>\
    </div>';
};

/**
 * 生成活动时间
 * @constructor
 */
CreateForm.CActTime = function(){

    var _startTime = (typeof CreateForm.info.dtActStartTime != 'undefined' && CreateForm.info.dtActStartTime != '0000-00-00 00:00:00') ? CreateForm.info.dtActStartTime.substring(0, 10) : '';
    var _endTime = (typeof CreateForm.info.dtActStartTime != 'undefined' && CreateForm.info.dtActEndTime != '0000-00-00 00:00:00') ? CreateForm.info.dtActEndTime.substring(0, 10) : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动时间：</label>\
    <div class="col-sm-2">\
    <input type="input" name="dtActStartTime" value="'+_startTime+'" class="form-control validate[required] text-input" data-errormessage-value-missing="开始时间不能为空" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\'})">\
    </div>\
    <div class="col-sm-2">\
    <input type="input" name="dtActEndTime" value="'+_endTime+'" class="form-control validate[required] text-input" data-errormessage-value-missing="结束时间不能为空" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\'})">\
    </div>\
    </div>';
};

/**
 * 生成活动类型
 * @constructor
 */
CreateForm.CActType = function(){
    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动类型：</label>\
    <div class="col-sm-4">\
    <select class="form-control " name="iActType">';

    var _actTypeId = (typeof CreateForm.info.iActType != 'undefined') ? parseInt( CreateForm.info.iActType ) : 0;

    var _actType = FORMCFG.ACTTYPE_CFG;
    for (var i in _actType) {
        if (i == _actTypeId) {
            _html += '<option value="'+i+'" selected>'+_actType[i]+'</option>';
        } else {
            _html += '<option value="'+i+'">'+_actType[i]+'</option>';
        }
    }
    _html += '</select>\
    </div>\
    </div>';
    return _html;
};

/**
 * 生成活动接口人
 * @returns {string}
 * @constructor
 */
CreateForm.CActPerson = function(){

    var _actPerson = (typeof CreateForm.info.sActPerson != 'undefined' && CreateForm.info.sActPerson != '') ? CreateForm.info.sActPerson : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动接口人：</label>\
    <div class="col-sm-4">\
    <input type="input" value="'+_actPerson+'" name="sActPerson" class="form-control oc_common oc_userchooser validate[required] text-input" data-errormessage-value-missing="接口人不能为空" id="ops" onchooser_blur="myBlur(target,data);" multiple="true" hideicon="true">\
    </div>\
    </div>';
};

/**
 * 生成投放平台
 * @constructor
 */
CreateForm.CPlat = function(){
    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">投放平台：</label>';
    var _plat = FORMCFG.PLAT_CFG;

    var _platArr = new Array();
    if (typeof CreateForm.info.sPlat != 'undefined' && CreateForm.info.sPlat != '') {
        _platArr = CreateForm.info.sPlat.split(':');
    }

    for (var i in _plat) {
        _html += '<div class="col-sm-2">';
        if (_platArr.indexOf(i) >= 0) {
            _html += '<input type="checkbox" name="sPlat[]" checked value="'+i+'" class="validate[minCheckbox[1]] checkbox"> <label for="Android" class="control-label">'+_plat[i]+'</label>';
        } else {
            _html += '<input type="checkbox" name="sPlat[]" value="'+i+'" class="validate[minCheckbox[1]] checkbox"> <label for="Android" class="control-label">'+_plat[i]+'</label>';
        }

        _html += '</div>';
    }
    _html += '</div>';
    return _html;
};

/**
 * 可使用目标用户数
 * @constructor
 */
CreateForm.CCanQuota = function(){
    return '<div class="form-group col-sm-12" id="quotaDiv">\
        <label class="col-sm-3 control-label text-right">可使用目标用户数（千万）：</label>\
    <div class="col-sm-4" style="margin-top: 5px;">\
    <span id="quotaNum">0</span>(千万)\
    </div>\
    </div>';
};

/**
 * 实际投放量（千万）
 * @constructor
 */
CreateForm.CApplyNum = function(){

    var _fApplyNum = (typeof CreateForm.info.fApplyNum != 'undefined' && CreateForm.info.fApplyNum != '0.00') ? CreateForm.info.fApplyNum : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">实际投放量（千万）：</label>\
    <div class="col-sm-4">\
    <input type="input" name="fApplyNum" id="fApplyNum" value="'+_fApplyNum+'" class="form-control validate[required,funcCall[valiateApplyNum]] text-input" data-errormessage-value-missing="投放量不能为空">\
    </div>\
    </div>';
};

/**
 * 生成投放位置
 * @returns {string}
 * @constructor
 */
CreateForm.CPos = function(){
    var _html = '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">投放位置：</label>\
    <div class="col-sm-4">\
    <select class="form-control " name="iPos">';

    var _posId = (typeof CreateForm.info.iPos != 'undefined') ? parseInt( CreateForm.info.iPos ) : 0;

    var _pos = FORMCFG.POS_CFG;
    for (var i in _pos) {
        if (i == _posId) {
            _html += '<option value="'+i+'" selected>'+_pos[i]+'</option>';
        } else {
            _html += '<option value="'+i+'">'+_pos[i]+'</option>';
        }
    }
    _html += '</select>\
    </div>\
    </div>';
    return _html;
};

/**
 * 号码包类型
 * @returns {string}
 * @constructor
 */
CreateForm.CNumPackage = function(){
    var _html = '<div class="form-group col-sm-12" id="numPackageDiv">\
        <label class="col-sm-3 control-label text-right">号码包类型：</label>\
    <div class="col-sm-4">\
    <select class="form-control " name="sNumPackage" id="sNumPackage">';

    var _packId = (typeof CreateForm.info.sNumPackage != 'undefined' && CreateForm.info.sNumPackage != '') ? CreateForm.info.sNumPackage : '';

    var _pos = FORMCFG.NUMPACKAGE_CFG;
    for (var i in _pos) {
        if (i  == _packId) {
            _html += '<option value="'+_pos[i]['sNumPackage']+'" selected isQuery="'+_pos[i]['isQuery']+'">'+_pos[i]['sPackageNum']+'</option>';
        } else {
            _html += '<option value="'+_pos[i]['sNumPackage']+'" isQuery="'+_pos[i]['isQuery']+'">'+_pos[i]['sPackageNum']+'</option>';
        }
    }
    _html += '</select>\
    </div>\
    </div>';
    return _html;
};

/**
 * 申请理由
 */
CreateForm.CNumPackageReason = function(){
    var _sPackagCustomReason = (typeof CreateForm.info.sPackagCustomReason != 'undefined' && CreateForm.info.sPackagCustomReason != '') ? CreateForm.info.sPackagCustomReason : '';
    var _html = '<div class="form-group col-sm-12" id="sNumPackageCustomReasonDiv">\
        <label class="col-sm-3 control-label text-right">申请理由：</label>\
    <div class="col-sm-4">\
    <input type="input" name="sPackagCustomReason" id="sPackagCustomReason" value="'+_sPackagCustomReason+'" class="form-control validate[required] text-input" data-errormessage-value-missing="申请理由理由不能为空">\
    </div>\
    </div>';
    $("#numPackageDiv").after(_html);
};

/**
 * 生成自定义号码包上传
 */
CreateForm.appendNumPackageCustom = function(){
    var _sNumPackageCustom = (typeof CreateForm.info.sNumPackageCustom != 'undefined' && CreateForm.info.sNumPackageCustom != '') ? CreateForm.info.sNumPackageCustom : '';

    var _html = '<div class="form-group col-sm-12" id="sNumPackageCustomDiv">\
        <label class="col-sm-3 control-label text-right">自定义号码包纬度：</label>\
    <div class="col-sm-4">\
    <input type="input" name="sNumPackageCustom" id="sNumPackageCustom" value="'+_sNumPackageCustom+'" class="form-control validate[required,funcCall[valiateSpeChar]] text-input" data-errormessage-value-missing="自定义号码包纬度不能为空">\
    </div>\
    </div>';

    $("#numPackageDiv").after(_html);
};

/**
 * 移除自定义号码包上传理由
 * @constructor
 */
CreateForm.RemoveCNumPackageReason = function(){
    $("#sNumPackageCustomReasonDiv").remove();
};

/**
 * 移除自定义号码包纬度
 * @constructor
 */
CreateForm.RemoveNumPackageCustom = function(){
    $("#sNumPackageCustomDiv").remove();
};

/**
 * 生成活动详情
 * @constructor
 */
CreateForm.CActInfo = function(){

    var _actInfo = (typeof CreateForm.info.sActInfo != 'undefined' && CreateForm.info.sActInfo != '') ? CreateForm.info.sActInfo : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动详情：</label>\
    <div class="col-sm-4">\
    <input type="input" value="'+_actInfo+'" name="sActInfo" class="form-control validate[required,funcCall[valiateSpeChar]] text-input" data-errormessage-value-missing="活动详情不能为空">\
    </div>\
    </div>';
};

/**
 * 生成活动奖励
 * @returns {string}
 * @constructor
 */
CreateForm.CActReward = function(){
    var _actReward = (typeof CreateForm.info.sActReward != 'undefined' && CreateForm.info.sActReward != '') ? CreateForm.info.sActReward : '';

    return '<div class="form-group col-sm-12">\
        <label class="col-sm-3 control-label text-right">活动奖励：</label>\
    <div class="col-sm-4">\
    <input type="input" value="'+_actReward+'" name="sActReward" class="form-control validate[required,funcCall[valiateSpeChar]] text-input" data-errormessage-value-missing="活动奖励不能为空">\
    </div>\
    </div>';
};

/**
 * 生成html
 * @constructor
 */
CreateForm.CHtml = function(resId){
    //获得配置
    var _resCFG = CreateForm.GetResCfg(resId);

    //小红点类型
    if (typeof _resCFG.iApplyType != 'undefined' && _resCFG.iApplyType == '1') {
        $("#RedPoint").html(CreateForm.CApplyType());
    } else {
        $("#RedPoint").html('');
    }

    var _lastHtml = '';

    //素材类型
    if (typeof _resCFG.iMaterialId != 'undefined' && _resCFG.iMaterialId == '1') {
        _lastHtml += CreateForm.CMaterailList(resId);
    }

    //投入日期
    if (typeof _resCFG.dtResTime != 'undefined' && _resCFG.dtResTime == '1') {
        if(typeof _resCFG.dtResTimeKind != 'undefined' && _resCFG.dtResTimeKind == '1'){
            _lastHtml += CreateForm.CResTime();
        }else{
            _lastHtml += CreateForm.CResTimeLong();
        }
        
    }

    //游戏名称
    if (typeof _resCFG.iAppId != 'undefined' && _resCFG.iAppId == '1') {
        _lastHtml += CreateForm.CGame();
    }

    //活动名称
    if (typeof _resCFG.sActName != 'undefined' && _resCFG.sActName == '1') {
        _lastHtml += CreateForm.CActName();
    }

    //活动时间
    if (typeof _resCFG.ActTime != 'undefined' && _resCFG.ActTime == '1') {
        _lastHtml += CreateForm.CActTime();
    }

    //活动类型
    if (typeof _resCFG.iActType != 'undefined' && _resCFG.iActType == '1') {
        _lastHtml += CreateForm.CActType();
    }

    //活动接口人
    if (typeof _resCFG.sActPerson != 'undefined' && _resCFG.sActPerson == '1') {
        _lastHtml += CreateForm.CActPerson();

        setTimeout(function () { initOACommon() }, 200);
    }

    //投入平台
    if (typeof _resCFG.sPlat != 'undefined' && _resCFG.sPlat == '1') {
        _lastHtml += CreateForm.CPlat();
    }

    //号码包类型
    if (typeof _resCFG.sNumPackage != 'undefined' && _resCFG.sNumPackage == '1') {
        _lastHtml += CreateForm.CNumPackage();
    }

    //可使用目标用户数
    if (typeof _resCFG.GoalNum != 'undefined' && _resCFG.GoalNum == '1') {
        _lastHtml += CreateForm.CCanQuota();
    }

    //实际投放量
    if (typeof _resCFG.fApplyNum != 'undefined' && _resCFG.fApplyNum == '1') {
        _lastHtml += CreateForm.CApplyNum();
    }

    //投入位置
    if (typeof _resCFG.iPos != 'undefined' && _resCFG.iPos == '1') {
        _lastHtml += CreateForm.CPos();
    }

    //活动详情
    if (typeof _resCFG.sActInfo != 'undefined' && _resCFG.sActInfo == '1') {
        _lastHtml += CreateForm.CActInfo();
    }

    //活动奖励
    if (typeof _resCFG.sActReward != 'undefined' && _resCFG.sActReward == '1') {
        _lastHtml += CreateForm.CActReward();
    }

    $("#formContent").html(_lastHtml);
};

/**
 * 获得配额
 * @param _numPackage
 * @constructor
 */
CreateForm.GetNumPackageQuota = function(_numPackage, _appid){
    var _url = "index.php?module=act&action=quota&package="+_numPackage+"&appid="+_appid;
    var _lastQuota = '';
    $.get(_url, function(d) {
        if (d.retCode == '0') {
            _lastQuota = d.data.fquota;
            $("#quotaNum").html(_lastQuota);
        }
    }, 'json');
};

/**
 * 创建请选择的选项
 * @returns {string}
 * @constructor
 */
CreateForm.CreateZeroRes = function() {
    var _lastHtml = '';

    $("#RedPoint").html('');

    _lastHtml += CreateForm.CResTime();

    _lastHtml += CreateForm.CGame();

    $("#formContent").html(_lastHtml);
};

/**
 * 初始化
 */
CreateForm.init = function(optInfo){

    var opt = {};
    $.extend(opt, optInfo);

    //来自于前端的数据
    if (typeof opt.data != 'undefined') {
        CreateForm.info = opt.data;
    }

    $("#iResId").html(CreateForm.CResList());

    $("#iResId").change(function(){
        var _resId = parseInt($(this).val());

        if (_resId == 0) {
            CreateForm.CreateZeroRes();
        } else {
            CreateForm.CHtml(_resId);

            //绑定号码包类型
            if ($("#sNumPackage").length > 0) {
                $("#sNumPackage").bind('change', function(){
                    var _val = $(this).val(), _isQuery = $(this).find("option:selected").attr('isQuery'), _appId = $("#iAppId").val();
                    if ($("#quotaDiv").length == 0) {
                        $(CreateForm.CCanQuota()).insertAfter("#numPackageDiv");
                    }
                    //alert(_canQuota);

                    //如果是自定义类型，需要显示自定义号码包纬度
                    if (_val == 'zdy') {
                        CreateForm.CNumPackageReason();
                        CreateForm.appendNumPackageCustom();
                    } else {
                        CreateForm.RemoveCNumPackageReason();
                        CreateForm.RemoveNumPackageCustom();
                    }

                    CreateForm.GetNumPackageQuota(_val, _appId);
                });

                $("#sNumPackage").change();
            }
        }

        $("#iAppId").select2().on('change', function(){
            $("#sNumPackage").change();
        });
    });

    $("#iResId").change();

    $("#applyForm").validationEngine('attach',{
        //validationEventTrigger : 'keyup',
        autoHidePrompt: true,
        autoHideDelay : 5000,
        //promptPosition : 'inline',
        onValidationComplete : function(form, status) {
            if (status) {
                document.applyform.submit();
            }
        }
    });
};

function valiateApplyNum(field, rules, i, options) {
    var _canApply = $("#fApplyNum").val(), _actlyNum = $("#quotaNum").html();

    if (/^\d*\.\d+$/.test(_canApply) || /^\d+$/.test(_canApply)) {

    } else {
        return "填写正确的格式";
    }

    var _canApplyAct = _canApply * 10000000, _actlyNumAct = _actlyNum * 10000000;
    if (_canApplyAct > _actlyNumAct) {
        return "超出最大配额";
    }
}

function valiateResTime(field, rules, i, options) {
    if($('#aclGroup').val()=='admin'){
        return true;
    }
    var input_date = field.val();
    var canResTime = $('#canResTime').val();
    var canResEndTime = $('#canResEndTime').val();

    var canspeceilResTime = $('#canspeceilResTime').val();
    var canspeceilResEndTime = $('#canspeceilResEndTime').val();
    if(canspeceilResTime != 0 && canspeceilResEndTime != 0){
        if(canResTime<canspeceilResTime && canResEndTime<canspeceilResTime){
            if(input_date>canResEndTime && input_date<canspeceilResTime){
                return('该日期不在可选范围内，请查看时间管理确认');
            }
            
            if($('#dtResEndTime').length>0 && $('#dtResTime').val()<canResEndTime && $('#dtResEndTime').val()>canspeceilResTime){
                return('十分抱歉，'+canResEndTime+'--'+canspeceilResTime+'（不包含）为不可选区域，请查看时间管理确认');
            }

        }else if(canResTime>canspeceilResTime && canspeceilResEndTime<canResTime){
            if(input_date>canspeceilResEndTime && input_date<canResTime){
                return('该日期不在可选范围内，请查看时间管理确认');
            }
            if($('#dtResEndTime').length>0 && $('#dtResTime').val()<canspeceilResEndTime && $('#dtResEndTime').val()>canResTime){
                return('十分抱歉，'+canspeceilResEndTime+'--'+canResTime+'（不包含）为不可选区域，请查看时间管理确认');
            }
        }
    }
    
    
    
    
    
}

function valiateCloseTime(field, rules, i, options) {
    var _closeTime = $("#canResTime").val(), _userCloseTime = $("#dtResTime").val();
    if (_userCloseTime < _closeTime) {
        return '投放日期已超过可投放日期';
    }
}

function valiateRes(field, rules, i, options) {
    var _resId = parseInt($("#iResId").val());
    if (_resId <= 0) {
        return '请选择资源类型';
    }
}

function valiateSpeChar(field, rules, i, options) {
    var _val = field.val();
    var containSpecial = RegExp(/[(\ )(\~)(\!)(\@)(\#)(\$)(\%)(\^)(\&)(\*)(\()(\))(\-)(\_)(\+)(\=)(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\<)(\>)(\?)(\)]+/);
    if (containSpecial.test(_val)) {
        return '不能包含特殊字符';
    }
}

function wp(){
        
        var res = {};

        var canResTime = $('#canResTime').val();
        var canResEndTime = $('#canResEndTime').val();
        
        var canspeceilResTime = $('#canspeceilResTime').val();
        var canspeceilResEndTime = $('#canspeceilResEndTime').val();
        if($('#aclGroup').val()=='admin'){
            res['min'] = 'yyyy-MM-dd ';
            res['max'] = '';
        }else if(canspeceilResTime == 0 || canspeceilResEndTime == 0){
            res['min'] = canResTime;
            res['max'] = canResEndTime;
        }else{
            if(canResTime<canspeceilResTime){
                if(canResEndTime<canspeceilResTime){
                    res['min'] = canResTime;
                    res['max'] = canspeceilResEndTime;
                }else{
                    res['min'] = canResTime;
                    res['max'] = canResEndTime>canspeceilResEndTime?canResEndTime:canspeceilResEndTime;
                }
                
            }else if(canResTime>canspeceilResTime){
                if(canspeceilResEndTime<canResTime){
                    res['min'] = canspeceilResTime;
                    res['max'] = canResEndTime;

                }else{
                    res['min'] = canspeceilResTime;
                    res['max'] = canResEndTime>canspeceilResEndTime?canResEndTime:canspeceilResEndTime;

                }
                
            }else{
                res['min'] = canResTime;
                res['max'] = canResEndTime>canspeceilResEndTime?canResEndTime:canspeceilResEndTime;

            }
            
        }
        return res;
//        WdatePicker({dateFmt:'yyyy-MM-dd',minDate:res['min'],maxDate:res['max']});
    }