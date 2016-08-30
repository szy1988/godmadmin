<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class newAppUpload extends totalApp
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
        $this->tpl->display('newAppUpload.html');
    }

    public function newAppInsert(){

        $_POST['dtCDate'] = date('Y-m-d H:i:s'); 
        $_POST['dtUDate'] = date('Y-m-d H:i:s'); 
        $selectAuth = $this->m_params['selectAuth'];
        $_POST['sAppOnlyService'] = implode(',', $this->m_params['sAppOnlyService']);
        // $sRoleName = $this->m_params['sRoleName'];
        // $sRoleID = $this->m_params['sRoleID'];
        $iLoginByQQ = $this->m_params['iLoginByQQ'];
        $setSRoleName = $this->m_params['setSRoleName'];
        $sConnectRTX = preg_replace('/(\([\x7f-\xff]+\))/','',$this->m_params['sConnectRTX']);
        $RoleName = implode(';', $setSRoleName);
        $IsToAdmin = '';
        $iIsPlatToService = ($this->m_params['iIsPlatToService'])?1:0;
        for($i=0;$i<count($setSRoleName);$i++){
            $IsToAdmin .= '0;';
        }
        $IsToAdmin = substr($IsToAdmin, 0,strlen($IsToAdmin)-1);

        $ticket = $_SESSION["X_TICKET"];



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
                    $this->tridentJson(1,$curlPostJson['msg'],array());
                }
            }
        }
        
        //清楚不需要插入的post参数
        unset($_POST['selectAuth']);
        unset($_POST['sRoleName']);
        unset($_POST['sRoleID']);
        unset($_POST['setSRoleName']);

        //设置操作人为联系人
        if(!strstr($_POST['sConnectRTX'], $_SESSION['right_user'])){
            $_POST['sConnectRTX'] .= (';'.$_SESSION['right_user']);
        }

        $tbAppInfo = $this->dao->tbAppInfo;
        $res = $tbAppInfo->insertColumn($_POST);
        if($res){
            if($this->m_params['gonext'] == 1){
                $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=appUpdate&func=appDetailShow&iAppId={$res}",'retTimer'=>0));
            }else{

                if($this->m_params['iAppDevGroup'] == 1){
                    $this->sendRTX($_SESSION['right_user'].'提交应用【'.$this->m_params['sAppName'].'】成功，请审核! '."\n".' http://x.ied.com/index.php?module=totalApp&action=devAppList');
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=totalApp&action=devAppList",'retTimer'=>1000));
                }else{
                    $this->tridentJson(0,'提交成功',array('url'=>"index.php?module=devApp&action=devAppList",'retTimer'=>1000));
                }
                
            }
            
        }else{
            $this->tridentJson(1,'提交失败',array());
        }
    }

    public function qr(){
        //http://v_zjzzhu.qq.com/opwebadmin/opwebadmin/htdocs/index.php?module=totalApp&action=newAppUpload&func=qr&sRoleIDList=167:%E6%B5%8B%E8%AF%95;180:%E4%BA%A7%E5%93%81;179:%E5%BC%80%E5%8F%91
        // $s = array();
        // $tbAppInfo = $this->dao->tbAppInfo;
        // $sRoleIDList = $this->m_params['sRoleIDList'];
        // $sRoleIDLists = explode(';', $sRoleIDList);
        // foreach($sRoleIDLists as $v){
        //     $a = explode(':', $v);
        //     $s[$a[0]] = $a[1];
        // }
        // var_dump(serialize($s));
        // $i['sRoleIDList'] = serialize($s);

        // $tbAppInfo->insertColumn($i);
        // exit;

        //$url = 'http://lol.qq.com/act/a20140318sysNew/myApps.html';
        //$url = 'http://go.qq.com/act/godm2016/myApps.html?serviceType=lol';
        //$url = 'http://apps.game.qq.com/fifa/qiyehao/index.php?m=SendMsg';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxab249edd27d57738&redirect_uri=http://apps.game.qq.com/fifa/qiyehao/index.php?m=DoSendMsg&response_type=code&scope=SCOPE&state=STATE#wechat_redirect';
        // $url .= $this->m_params['id'];
        // $url .= '$_t='.time();
        //生成二维码
        $this->qr->png($url,false,'L',6,1);
    }
}