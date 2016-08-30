<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 *   为业务应用
 */

class devAppList extends totalApp
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
        // $aclGroup = $acl->getUserModule($sRtx);

        // if(strpos($aclGroup['data']['modules'], '2066')){
        //     $isAdmin = 1;
        // }else{
        //     $condition['sConnectRTX'] = $right_user;
        // }
        
        $condition['sAppName'] = $this->m_params['sAppName'];
        $condition['iDevCenter'] = $this->m_params['iDevCenter'];
        $condition['iAppGroup'] = $this->m_params['iAppGroup'];
        $condition['sAppOnlyService'] = $this->m_params['sAppOnlyService'];
        // var_dump($condition);
        // exit;
        $condition['iAppDevGroup'] = 1;
        // $condition['isDifferentService'] = 1;

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
        $serviceMap = $this->getServiceMap();
        $this->tpl->assign('serviceMap',$serviceMap);
        $this->tpl->assign('isAdmin',$isAdmin);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('devAppList.html');
    }

    public function passApp(){
        $iAppId = $this->m_params['iAppId'];
        if(empty($iAppId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->batchDeal($iAppId,1,0);
        if($res){

            $curlPostRes = $this->doCurlPost('http://x.ied.com/lol/index.php?module=totalApp&action=syncAllApps&publishType=1&curlType=1',array());
            $curlPostJson = json_decode($curlPostRes,TRUE);
            if($curlPostJson['retCode'] != 0){           
                $param = array (
                    "Title"=>"营销手册通知",
                    "Sender" => $_SESSION['right_user'],
                    'MsgInfo' => $curlPostJson['retInfo'],
                    'Receiver' => 'jaspersong',
                );

                $qc = $this->qc;
                $ret = $qc->SendRTX($param);
            }

            $this->tridentJson(0,'审核通过',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
        
    }

    public function refuseApp(){
        $iAppId = $this->m_params['iAppId'];
        if(empty($iAppId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->batchDeal($iAppId,2,0);
        if($res){
            $this->tridentJson(0,'驳回成功',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
    }

    public function downLineApp(){
        $iAppId = $this->m_params['iAppId'];
        if(empty($iAppId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->batchDeal($iAppId,3,1);
        if($res){
            $this->tridentJson(0,'下架成功',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
    }
}