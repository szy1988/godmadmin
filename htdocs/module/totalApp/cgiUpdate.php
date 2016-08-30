<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class cgiUpdate extends totalApp
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

        $tbCgiInfo = $this->dao->tbCgiInfo;
        $appInfo = $tbCgiInfo->getActInfoById(array('iCgiId',$iCgiId));

        $this->tpl->assign('appInfo',$appInfo[0]);
        $this->tpl->assign('appKind',$appKind);
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
                $this->tridentJson(0,'提交成功',array());
            }else{
                $this->tridentJson(1,'提交失败',array());
            }
        }else{
            $iAppId = $this->m_params['iAppId'];
            $res = $tbCgiInfo->updateColumnByIds(array('iAppId',$iAppId),$_POST);
            if($res){
                $this->tridentJson(0,'更新成功',array());
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