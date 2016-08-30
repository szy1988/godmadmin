<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 *   为业务应用
 */

class devAppList extends devApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {

        $right_user = $_SESSION["right_user"];
        $easyApply = $this->config->acl['easyApply'];

        if(in_array($right_user, $easyApply)){
            $isAdmin = 1;
        }else{
            $condition['sConnectRTX'] = $right_user;
        }

        $acl = Acl::getInstance($this->config->acl);

        //判断用户是否有发布等敏感权限
        $aclGroup = $acl->getUserModule($sRtx);

        $condition['sAppName'] = $this->m_params['sAppName'];
        $condition['iDevCenter'] = $this->m_params['iDevCenter'];
        $condition['iAppGroup'] = $this->m_params['iAppGroup'];
        $condition['iAppDevGroup'] = 2;

        $tbAppInfo = $this->dao->tbAppInfo;
        $total = $tbAppInfo->getSumByConditionTable($condition);
        

        // 每页展示数据条数
        $pageSize = 10;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myApps = $tbAppInfo->getListByConditionTable($condition);
    	$putData = array();
    	if($myApps){
            foreach($myApps as &$v){
                $v['sRoleIDList'] = (unserialize(htmlspecialchars_decode($v['sRoleIDList'])));
                if($v['sRoleIDList'] == false){
                    $v['sRoleIDList'] = array();
                }
            }
    		$putData = $myApps;
    	}
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
        $this->tpl->assign('isAdmin',$isAdmin);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/devAppListOnlyDev.html');
    }

    public function cgiList()
    {
        $right_user = $_SESSION["right_user"];
        $easyApply = $this->config->acl['easyApply'];

        if(in_array($right_user, $easyApply)){
            $isAdmin = 1;
        }else{
            $condition['sConnectRTX'] = $right_user;
        }
        $acl = Acl::getInstance($this->config->acl);


        $condition['sCgiName'] = $this->m_params['sCgiName'];
        $condition['iDevCenter'] = $this->m_params['iDevCenter'];
        $condition['iCgiGroup'] = $this->m_params['iCgiGroup'];

        $tbCgiInfo = $this->dao->tbCgiInfo;
        $total = $tbCgiInfo->getSumByCondition($condition);
        

        // 每页展示数据条数
        $pageSize = 10;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

        $myApps = $tbCgiInfo->getListByConditionOneTable($condition);
        $putData = array();
        if($myApps){
            $putData = $myApps;
        }
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
        $this->tpl->assign('isAdmin',$isAdmin);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/devCgiListOnlyDev.html');
    }

    public function passApp(){
        if(!empty($this->m_params['iAppId'])){
            $iAppId = $this->m_params['iAppId'];

            $tbAppInfo = $this->dao->tbAppInfo;
            $res = $tbAppInfo->batchDeal($iAppId,1,0);
            if($res){
                $this->tridentJson(0,'审核通过',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array());
            }
        }elseif(!empty($this->m_params['iCgiId'])){
            $iCgiId = $this->m_params['iCgiId'];

            $tbCgiInfo = $this->dao->tbCgiInfo;
            $res = $tbCgiInfo->batchDeal($iCgiId,1,0);
            if($res){
                $this->tridentJson(0,'审核通过',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array());
            }
        }else{
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        
    }

    public function refuseApp(){

        if(!empty($this->m_params['iAppId'])){
            $iAppId = $this->m_params['iAppId'];

            $tbAppInfo = $this->dao->tbAppInfo;
            $res = $tbAppInfo->batchDeal($iAppId,2,0);
            if($res){
                $this->tridentJson(0,'驳回成功',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array());
            }
        }elseif(!empty($this->m_params['iCgiId'])){
            $iCgiId = $this->m_params['iCgiId'];

            $tbCgiInfo = $this->dao->tbCgiInfo;
            $res = $tbCgiInfo->batchDeal($iCgiId,2,0,$this->m_params['sRefuseReason']);
            if($res){
                $this->tridentJson(0,'驳回成功',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array('callBack'=>'afterBatch'));
            }
        }else{
            $this->tridentJson(1,'请选择需要操作的应用',array('callBack'=>'afterBatch'));
        }
    }

    public function downLineApp(){
        if(!empty($this->m_params['iAppId'])){
            $iAppId = $this->m_params['iAppId'];

            $tbAppInfo = $this->dao->tbAppInfo;
            $res = $tbAppInfo->batchDeal($iAppId,3,1);
            if($res){
                $this->tridentJson(0,'下架成功',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array());
            }
        }elseif(!empty($this->m_params['iCgiId'])){
            $iCgiId = $this->m_params['iCgiId'];

            $tbCgiInfo = $this->dao->tbCgiInfo;
            $res = $tbCgiInfo->batchDeal($iCgiId,3,1);
            if($res){
                $this->tridentJson(0,'下架成功',array('callBack'=>'afterBatch'));
            }else{
                $this->tridentJson(1,'数据更新失败',array());
            }
        }else{
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
    }
}