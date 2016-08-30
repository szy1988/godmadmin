<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class appShow extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $iAppId = $this->m_params['iAppId'];
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
        $appInfo = array();
        //兼容查询
        if(empty($iAppId)) $iAppId=-1;
        $condition = array();
        $condition['iAppId'] = $iAppId;
        $condition['sUserRtx'] = $_SESSION['right_user'];
        $tbAppInfo = $this->dao->tbAppInfo;
        $appInfo = $tbAppInfo->getListByCondition($condition);
        if(!empty($appInfo[0])){
            $appInfo[0]['sRoleIDList'] = (unserialize(htmlspecialchars_decode($appInfo[0]['sRoleIDList'])));
            $appInfo[0]['sPublicityImg'] = (unserialize(htmlspecialchars_decode($appInfo[0]['sPublicityImg'])));
            $appInfo[0]['sOperationManualName'] = substr($appInfo[0]['sOperationManual'], strrpos($appInfo[0]['sOperationManual'], '/')+12);
            foreach($appInfo[0]['sPublicityImg'] as $key=>$val){
            	if(empty($val)){unset($appInfo[0]['sPublicityImg'][$key]);}
            }
            
            $this->tpl->assign('appInfo',$appInfo[0]);
        }

        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('appInfoShow.html');
    }
}