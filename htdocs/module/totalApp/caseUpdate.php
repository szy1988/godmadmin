<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class caseUpdate extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $iCaseId = $this->m_params['iCaseId'];
        $appKind = $this->getAppKind();
        $tbAppInfo = $this->dao->tbAppInfo;
        //应用条件筛选
        $appConditon = array();
        $appConditon['iState'] = 1;
        $appConditon['iAppDevGroup'] = 1;
        
        $appInfo = $tbAppInfo -> getListByConditionTable($appConditon);

        //查询case信息
        
        if($iCaseId){
            $tbCaseInfo = $this->dao->tbCaseInfo;
            $caseInfo = $tbCaseInfo->getActInfoById(array('iCaseId',$iCaseId));
            if($caseInfo){
                $this->tpl->assign('caseInfo',$caseInfo[0]);
            }
        }
        // var_dump($appInfo);
        // exit;
        $this->tpl->assign('appInfo',$appInfo);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->display('caseUpdate.html');
    }

    public function caseUpdate(){
        //判断是否选择业务
        if(empty($_POST)){
            header("Content-type: text/html; charset=utf-8"); 
            echo '你好，请去掉HOSTS里面ossweb-img.qq.com的绑定';
            exit;
        }
        $sDisplayService_control = $this->m_params['sDisplayService_control'];
        if($sDisplayService_control){
            $_POST['sDisplayService'] = '';
            
        }else{
            $_POST['sDisplayService'] = implode(',', $this->m_params['sDisplayService']);
        }
        unset($_POST['sDisplayService_control']);
        $_POST['dtDate'] = date('Y-m-d H:i:s'); 
        $_POST['iState'] = 0;
        
        $tbCaseInfo = $this->dao->tbCaseInfo;
        if(empty($this->m_params['iCaseId'])){
            $_POST['sSubmitRtx'] = $_SESSION['right_user'];
            $res = $tbCaseInfo->insertColumn($_POST);
            if($res){
                $this->sendRTXWhenUDCase('default',$_SESSION['right_user'].'提交案例【'.$this->m_params['sCaseName'].'】成功，请上传图片并审核! '."\n".' http://x.ied.com/index.php?module=totalApp&action=caseControlList');
                $this->tridentJson(0,'提交成功',array('url'=>'index.php?module=totalApp&action=caseControlListForAction','retTimer'=>1000));
                // $this->tridentJson(0,'提交成功',array());
            }else{
                $this->tridentJson(1,'提交失败',array());
            }
        }else{
            $iCaseId = $this->m_params['iCaseId'];
            $res = $tbCaseInfo->updateColumnByIds(array('iCaseId',$iCaseId),$_POST);
            if($res){
                $this->sendRTXWhenUDCase('default',$_SESSION['right_user'].'更新案例【'.$this->m_params['sCaseName'].'】成功，请上传图片并审核! '."\n".' http://x.ied.com/index.php?module=totalApp&action=caseControlList');
                $this->tridentJson(0,'更新成功',array('url'=>'index.php?module=totalApp&action=caseControlListForAction','retTimer'=>1000));
            }else{
                $this->tridentJson(1,'更新失败',array());
            }
        }

    }
}