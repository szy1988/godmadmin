<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class appUpdate extends devApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $iAppId = $this->m_params['iAppId'];
        $appKind = $this->getAppKind();
        $DevCenter = $this->getDevGroup();
        $appInfo = array();

        $tbAppInfo = $this->dao->tbAppInfo;
        $appInfo = $tbAppInfo->getActInfoById(array('iAppId',$iAppId));
        if(!empty($appInfo[0])){
            $appInfo[0]['sPublicityImg'] = (unserialize(htmlspecialchars_decode($appInfo[0]['sPublicityImg'])));
            $appInfo[0]['sOperationManualName'] = substr($appInfo[0]['sOperationManual'], strrpos($appInfo[0]['sOperationManual'], '/')+12);
            $this->tpl->assign('appInfo',$appInfo[0]);
        }
        $colorArray = array();
        for($i=1;$i<=16;$i++){
            $colorArray[] = $i;
        }
        $devGroup = $this->getGroupByCenter($appInfo[0]['iDevCenter']);
        $this->tpl->assign('colorArray',$colorArray);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('DevCenter',$DevCenter);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('dev/appUpdate.html');
    }

    public function appDetailShow()
    {
        $iAppId = $this->m_params['iAppId'];
        $appInfo = array();

        $tbAppInfo = $this->dao->tbAppInfo;
        $appInfo = $tbAppInfo->getActInfoById(array('iAppId',$iAppId));
        if(!empty($appInfo[0])){
            $appInfo[0]['sPublicityImg'] = (unserialize(htmlspecialchars_decode($appInfo[0]['sPublicityImg'])));
            $appInfo[0]['sOperationManualName'] = substr($appInfo[0]['sOperationManual'], strrpos($appInfo[0]['sOperationManual'], '/')+12);
            if(empty($appInfo[0]['sPublicityImg'])){
                $appInfo[0]['sPublicityImg'] = array(1=>'',2=>'',3=>'',4=>'');
            }
            $this->tpl->assign('appInfo',$appInfo[0]);
        }
        $this->tpl->display('dev/newAppDetailUpdate.html');
    }


    public function appUpdate(){
        $_POST['sPublicityImg'] = serialize($this->m_params['sPublicityImgs']); 
        if(empty($this->m_params['iAppId'])){
            $this->tridentJson(1,'参数错误',array());
        }else{
            $iAppId = $this->m_params['iAppId'];
        }
        $_POST['dtUDate'] = date('Y-m-d H:i:s'); 
        $_POST['iState'] = 0;
        unset($_POST['sPublicityImgs']);

        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->updateColumnByIds(array('iAppId',$iAppId),$_POST);
        if($res){
            if($this->m_params['gonext'] == 1){
                $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=appUpdate&func=appDetailShow&iAppId={$iAppId}",'retTimer'=>0,'retNotice'=>0));
            }else{
                if($this->m_params['iAppDevGroup'] == 1){
                    $this->sendRTX($_SESSION['right_user'].'更新应用【'.$this->m_params['sAppName'].'】成功，请审核! \n http://x.ied.com/index.php?module=totalApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=devAppList",'retTimer'=>1000));
                }else{
                    $this->sendRTX($_SESSION['right_user'].'更新工具【'.$this->m_params['sAppName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=devApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=devApp&action=devAppList",'retTimer'=>1000));
                }
            }
            
        }else{
            $this->tridentJson(1,'更新失败',array());
        }
    }
}