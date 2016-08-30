<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 *   为业务应用
 */

class caseControlListForAction extends totalApp
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

        $condition['sCaseName'] = $this->m_params['sCaseName'];
        $condition['iCaseKindID'] = $this->m_params['iCaseKindID'];

        $tbCaseInfo = $this->dao->tbCaseInfo;

        // 每页展示数据条数
        $pageSize = 10;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myApps = $tbCaseInfo->getListByCondition($condition);
        if($myApps){
            $putData = $myApps;
        }
        
        $condition['sum'] = 1;
        $total = $tbCaseInfo->getListByCondition($condition);
        $appKind = $this->getAppKind();

        $this->tpl->assign('isAdmin',$isAdmin);
        $this->tpl->assign('appKind',$appKind);

    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/caseControlList.html');
    }

    public function passApp(){
        $iCaseId = $this->m_params['iCaseId'];
        if(empty($iCaseId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbCaseInfo = $this->dao->tbCaseInfo;
        $res = $tbCaseInfo->batchDeal($iCaseId,1,0);
        if($res){

            // $curlPostRes = $this->doCurlPost('http://x.ied.com/lol/index.php?module=totalApp&action=syncAllApps&publishType=1&curlType=1',array());
            // $curlPostJson = json_decode($curlPostRes,TRUE);
            // if($curlPostJson['retCode'] != 0){           
            //     $param = array (
            //         "Title"=>"营销手册通知",
            //         "Sender" => $_SESSION['right_user'],
            //         'MsgInfo' => $curlPostJson['retInfo'],
            //         'Receiver' => 'jaspersong',
            //     );

            //     $qc = $this->qc;
            //     $ret = $qc->SendRTX($param);
            // }

            $this->tridentJson(0,'审核通过',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
        
    }

    public function refuseApp(){
        $iCaseId = $this->m_params['iCaseId'];
        if(empty($iCaseId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbCaseInfo = $this->dao->tbCaseInfo;
        $res = $tbCaseInfo->batchDeal($iCaseId,2,0);
        if($res){
            $this->tridentJson(0,'驳回成功',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
    }

    public function downLineApp(){
        $iCaseId = $this->m_params['iCaseId'];
        if(empty($iCaseId)){
            $this->tridentJson(1,'请选择需要操作的应用',array());
        }
        $tbCaseInfo = $this->dao->tbCaseInfo;
        $res = $tbCaseInfo->batchDeal($iCaseId,3,1);
        if($res){
            $this->tridentJson(0,'下架成功',array('callBack'=>'afterBatch'));
        }else{
            $this->tridentJson(1,'数据更新失败',array());
        }
    }
}