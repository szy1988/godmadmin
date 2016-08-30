<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class cgiShow extends devApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $iCgiId = $this->m_params['iCgiId'];
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
        $appInfo = array();
        //兼容查询
        if(empty($iCgiId)) $iCgiId=-1;
        $condition = array();
        $condition['iCgiId'] = $iCgiId;
        $condition['sUserRtx'] = $_SESSION['right_user'];
        $tbCgiInfo = $this->dao->tbCgiInfo;
        $appInfo = $tbCgiInfo->getListByCondition($condition);
        $this->tpl->assign('appInfo',$appInfo[0]);


        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('cgiInfoShow.html');
    }
}