<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class newAppUpload extends devApp
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
        if($iAppId){
            $tbAppInfo = $this->dao->tbAppInfo;
            $appInfo = $tbAppInfo->getActInfoById(array('iAppId',$iAppId));
        }
        $colorArray = array();
        for($i=1;$i<=16;$i++){
            $colorArray[] = $i;
        }
        $this->tpl->assign('colorArray',$colorArray);
        $this->tpl->assign('appInfo',$appInfo[0]);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('dev/newAppUpload.html');
    }

    public function newAppInsert(){

        $_POST['dtCDate'] = date('Y-m-d H:i:s'); 
        $_POST['dtUDate'] = date('Y-m-d H:i:s'); 

        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->insertColumn($_POST);
        if($res){
            if($this->m_params['gonext'] == 1){
                $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=appUpdate&func=appDetailShow&iAppId={$res}",'retTimer'=>0));
            }else{

                if($this->m_params['iAppDevGroup'] == 1){ 
                    $this->sendRTX($_SESSION['right_user'].'提交应用【'.$this->m_params['sAppName'].'】成功，请审核! \n http://x.ied.com/index.php?module=totalApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=devAppList",'retTimer'=>1000));
                }else{
                    $this->sendRTX($_SESSION['right_user'].'提交工具【'.$this->m_params['sAppName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=devApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=devApp&action=devAppList",'retTimer'=>1000));
                }  
            
            }
            
        }else{
            $this->tridentJson(1,'提交失败',array());
        }
    }
}