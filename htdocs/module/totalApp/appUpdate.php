<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class appUpdate extends totalApp
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
            $appInfo[0]['sRoleIDList'] = (unserialize(htmlspecialchars_decode($appInfo[0]['sRoleIDList'])));
            
            $this->tpl->assign('appInfo',$appInfo[0]);
        }
        $devGroup = $this->getGroupByCenter($appInfo[0]['iDevCenter']);
        $colorArray = array();
        for($i=1;$i<=16;$i++){
            $colorArray[] = $i;
        }
        $this->tpl->assign('colorArray',$colorArray);
        $this->tpl->assign('appKind',$appKind);
        $this->tpl->assign('DevCenter',$DevCenter);
        $this->tpl->assign('devGroup',$devGroup);
        $this->tpl->display('appUpdate.html');
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
        $this->tpl->display('newAppDetailUpdate.html');
    }


    public function appUpdate(){
        if(empty($this->m_params['iAppId'])){
            $this->tridentJson(1,'参数错误',array());
        }else{
            $iAppId = $this->m_params['iAppId'];
        }
        $_POST['dtUDate'] = date('Y-m-d H:i:s'); 
        $_POST['iState'] = 0;

        $selectAuth = $this->m_params['selectAuth'];
        // $sRoleName = $this->m_params['sRoleName'];
        // $sRoleID = $this->m_params['sRoleID'];
        $iLoginByQQ = $this->m_params['iLoginByQQ'];
        $setSRoleName = $this->m_params['setSRoleName'];
        $_POST['sAppOnlyService'] = implode(',', $this->m_params['sAppOnlyService']);
        $sConnectRTX = preg_replace('/(\([\x7f-\xff]+\))/','',$this->m_params['sConnectRTX']);
        $RoleName = implode(';', $setSRoleName);
        $iIsPlatToService = ($this->m_params['iIsPlatToService'])?1:0;
        $IsToAdmin = '';
        $ticket = $_SESSION["X_TICKET"];
        for($i=0;$i<count($setSRoleName);$i++){
            $IsToAdmin .= '0;';
        }
        $IsToAdmin = substr($IsToAdmin, 0,strlen($IsToAdmin)-1);
        $sRoleIDLists = array();
        if($iLoginByQQ != 1 && $selectAuth != 2){
            if($selectAuth){
                $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/getAuthInfo.php?action=getPlatRoleList&platID='.$this->m_params['iPlatID'],array());
                $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
                if($curlPostJson['ret'] == 1){
                    foreach($curlPostJson['data'] as $v){
                    $sRoleIDLists[$v['iId']] = $v['sRoleName'];
                    }
                    $_POST['sRoleIDList'] = serialize($sRoleIDLists);
                    
                }else{
                    $this->tridentJson(1,'您输入的平台ID查询不到信息',array());
                }
            }else{
                //申请平台
                $params = array('PlatName'=>($this->m_params['sAppName']),
                        'PlatURL'=>($this->m_params['sAppUrl']),
                        'PlatPrincipal'=>($sConnectRTX.';jaspersong'),
                        'PlatDesc'=>($this->m_params['sAppIntroduction']),
                        'IsApprover'=>0,
                        'PlatToService'=>$iIsPlatToService,
                        'IsOpen'=>0,
                        'RoleName'=>($RoleName),
                        'IsToAdmin'=>$IsToAdmin,
                        );

                $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/commapi.php?action=CommAddPlatform&ticket='.$ticket,$params);
                $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
                if($curlPostJson['ret'] == 1){
                    $_POST['iPlatID'] = $curlPostJson['data']['PlatId'];
                    $RoleId = explode(';', $curlPostJson['data']['RoleId']);
                    $RoleName = explode(';', $curlPostJson['data']['RoleName']);
                    $sRoleIDList = array();
                    foreach($RoleId as $key=>$val){
                        $sRoleIDList[$val] = $RoleName[$key];
                    }
                    $_POST['sRoleIDList'] = serialize($sRoleIDList);
                    
                }else{
                    $this->tridentJson(0,$curlPostJson['msg'],array());
                }
            }
        }else{
            $_POST['iPlatID'] = '';
            $_POST['sRoleIDList'] = '';
        }
        
        //清楚不需要插入的post参数
        unset($_POST['selectAuth']);
        unset($_POST['sRoleName']);
        unset($_POST['sRoleID']);
        unset($_POST['setSRoleName']);
        if(!strstr($_POST['sConnectRTX'], $_SESSION['right_user'])){
            $_POST['sConnectRTX'] .= (';'.$_SESSION['right_user']);
        }

        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->updateColumnByIds(array('iAppId',$iAppId),$_POST);
        if($res){
            if($this->m_params['gonext'] == 1){
                $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=appUpdate&func=appDetailShow&iAppId={$iAppId}",'retTimer'=>0,'retNotice'=>0));
            }else{
                if($this->m_params['iAppDevGroup'] == 1){
                    $this->sendRTX($_SESSION['right_user'].'更新应用【'.$this->m_params['sAppName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=totalApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=devAppList",'retTimer'=>1000));
                }else{
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=devApp&action=devAppList",'retTimer'=>1000));
                }
            }
        }else{
            $this->tridentJson(1,'更新失败',array());
        }
    }
}