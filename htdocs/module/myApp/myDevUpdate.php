<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class myApp extends myApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
    	
    	//$right_user = $_COOKIE["right_user"];
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
    	$this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('hello.html');
    }

    //展示修改页面
    public function updateApp(){
        $appId = $this->params['appId'];
        $tbAppInfo = $this->dao->tbAppInfo;
        $appInfo = $tbAppInfo->getActInfoById(array('iAppId',$appId));
        $appKind = $this->getAppKind();
        $devGroup = $this->getDevGroup();
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('myApp.html');
    }

   
}