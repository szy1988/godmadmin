<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class cgiUpdate extends devApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $iCgiId = $this->m_params['iCgiId'];
        $appKind = $this->getAppKind();
        $DevCenter = $this->getDevGroup();
        $appInfo = array();

        $tbCgiInfo = $this->dao->tbCgiInfo;
        $appInfo = $tbCgiInfo->getActInfoById(array('iCgiId',$iCgiId));
        $colorArray = array();
        for($i=21;$i<=36;$i++){
            $colorArray[] = $i;
        }
        $devGroup = $this->getGroupByCenter($appInfo[0]['iDevCenter']);
        $this->tpl->assign('colorArray',$colorArray);
        $this->tpl->assign('appInfo',$appInfo[0]);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('DevCenter',$DevCenter);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('dev/cgiUpdate.html');
    }

    public function cgiUpdate(){

        $_POST['dtUDate'] = date('Y-m-d H:i:s'); 
        $_POST['iState'] = 0;
        $tbCgiInfo = $this->dao->tbCgiInfo;
        if(empty($this->m_params['iCgiId'])){
            $_POST['dtCDate'] = date('Y-m-d H:i:s'); 
            $res = $tbCgiInfo->insertColumn($_POST);
            if($res){
                $this->sendRTX($_SESSION['right_user'].'提交组件【'.$this->m_params['sCgiName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=devApp&action=devAppList&func=cgiList');
                $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=devApp&action=devAppList&func=cgiList",'retTimer'=>1000));
            }else{
                $this->tridentJson(1,'提交失败',array());
            }
        }else{
            $iCgiId = $this->m_params['iCgiId'];
            $res = $tbCgiInfo->updateColumnByIds(array('iCgiId',$iCgiId),$_POST);
            if($res){
                $this->sendRTX($_SESSION['right_user'].'更新组件【'.$this->m_params['sCgiName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=devApp&action=devAppList&func=cgiList');
                $this->tridentJson(0,'更新成功',array('url'=>"index.php?module=devApp&action=devAppList&func=cgiList",'retTimer'=>1000));
            }else{
                $this->tridentJson(1,'更新失败',array());
            }
        }

    }
   
    public function qr(){
        $url = 'http://lol.qq.com/act/a20140318sysNew/myApps.html';
        // $url .= $this->m_params['id'];
        // $url .= '$_t='.time();
        //生成二维码
        $this->qr->png($url,false,'L',6,1);
    }
}