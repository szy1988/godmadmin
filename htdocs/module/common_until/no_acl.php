<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/14
 * Time: 16:43
 */

class no_acl extends common_until
{

    public function __construct($params)
    {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index()
    {
        $serviceMap = $this->getServiceMap();
        $right_user = $_SESSION["right_user"];
        $x_service_type = $_COOKIE['x_service_type'];
        $this->tpl->assign('auto',$_COOKIE['auto']);
        $this->tpl->assign('x_service_type',$x_service_type);
        $this->tpl->assign('right_user',$right_user);
        $this->tpl->assign('serviceMap',$serviceMap);
        $this->tpl->display('index.html');
    }

    //用于首页判断所选业务是否有权限
    public function entrySystem(){
        $serviceType = $this->m_params['serviceType'];
        setcookie('x_service_type',$serviceType);
        //判断是否登录
        $right_user = $_SESSION["right_user"];
        $X_TICKET = $_SESSION['X_TICKET'];
        if(empty($right_user)){
            $this->tridentJson(1,'登录失效',array('callBack'=>'showDialog','serviceType'=>$serviceType));
        }else{
            $checkAclUrl = 'http://auth.ied.com/api/getAuthInfo.php?action=getAuthList&user_name='.$right_user.'&platID=55&isPlatToService=1&serviceType='.$serviceType;
            $curlGetRes = doCurlPost($checkAclUrl,array());
            $curlGetJson = json_decode(substr($curlGetRes,stripos($curlGetRes, '=')+2),TRUE);
            if($curlGetJson['ret'] == 1){
                $right_user = $_SESSION["right_user"];
                $condition['sUserRtx'] = $right_user;
                $condition['iCommonUse'] = 1;
                $condition['serviceType'] = $serviceType;
                //统计total
                $tbMyApp = $this->dao->tbMyApp;
                $total = $tbMyApp->getAppListSumByCondition($condition);
                if($total[0]['sum']>0){
                    $this->tridentJson(0,'成功',array('callBack'=>'showDialog','serviceType'=>$serviceType,'hasCollect'=>1));
                }else{
                    $this->tridentJson(0,'成功',array('callBack'=>'showDialog','serviceType'=>$serviceType,'hasCollect'=>0));
                }
                
            }elseif($curlGetJson['ret'] == -2){
                if(stripos($right_user,'_')){
                    $this->tridentJson(2,'无权限',array('callBack'=>'showDialog','serviceType'=>$serviceType,'redirect'=>1));
                }else{
                    $this->tridentJson(2,'无权限',array('callBack'=>'showDialog','serviceType'=>$serviceType));
                }
            }else{
                $this->tridentJson(3,$curlGetJson['msg'],array('callBack'=>'showDialog'));
            }
            // return $curlGetJson;
        }
        

    }

    public function logout(){
        session_destroy();
        header('LOCATION: http://passport.oa.com/modules/passport/signout.ashx?Nosignin=1&url='.urlencode('http://x.ied.com'));
    }

    public function login(){
        // $curUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $curUrl = 'http://x.ied.com/'.$_SESSION['serviceType'].'/index.php';

        $redirect = 'http://login.oa.com/modules/passport/signin.ashx?appkey=67967952b77041269c4a1b4e5914244a&url='.urlencode($curUrl);
        //判断请求方式，防止在表单请求时出现跨域错误，无法提示错误信息
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            header('LOCATION:'.$redirect);
        }else{
            exit(json_encode(array('retCode'=>-1,'retInfo'=>'跳转登录','url'=>$redirect)));
        }
    }

     function applyAuth(){
        $sRoleID = $this->m_params['sRoleID'];
        $right_user = $_SESSION["right_user"];
        $serviceType = $this->m_params['serviceType'];
        setcookie('x_service_type',$serviceType);
        $X_TICKET = $_SESSION['X_TICKET'];
        $authDesc = '权限申请';
        $PlatID = 55;
        $easyApply = $this->config->acl['easyApply'];

        $params = array('AuthRtx'=>$right_user,
                        'ServiceType'=>$serviceType,
                        'PlatID'=>$PlatID,
                        'RoleID'=>$sRoleID,
                        'AuthDesc'=>$authDesc,
                        // 'OATicket'=>$X_TICKET,
                        //'RoleType'=>'admin'
                        );

        if(in_array($right_user, $easyApply)){
            $authComplete = 1;
            $params = array_merge($params,array('RoleType'=>'admin'));
        }

        $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/commapi.php?action=CommAddAuth&ticket='.$X_TICKET,$params);
        $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);

        if($curlPostJson['ret'] > 0){
            if($curlPostJson['ret'] == 1){
                $this->tridentJson(0,'权限已开通',array('callBack'=>'afterApply','serviceType'=>$serviceType));
            }else{
                $this->tridentJson(1,'权限已申请，请联系Rebeccayang审核',array('callBack'=>'afterApply','serviceType'=>$serviceType));
            }
        }else{
            if($curlPostJson['ret'] == -10){
                $curUrl = 'http://x.ied.com/index.php';
                $redirect = 'http://login.oa.com/modules/passport/signin.ashx?appkey=67967952b77041269c4a1b4e5914244a&url='.urlencode($curUrl);
                $this->tridentJson(1,'十分抱歉，你的登录态失效，请重新登录！',array('url'=>$redirect,'retTimer'=>1));
            }else{
                $this->tridentJson(1,'权限已申请,如果没有权限请联系Rebeccayang开通！');
            }
            
        }
        
    }

    public function entryLogin(){
        $serviceType = $this->m_params['serviceType'];
        setcookie('x_service_type',$serviceType);
        setcookie('auto',1);
        $curUrl = 'http://x.ied.com/index.php';

        $redirect = 'http://login.oa.com/modules/passport/signin.ashx?appkey=67967952b77041269c4a1b4e5914244a&url='.urlencode($curUrl);

        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            header('LOCATION:'.$redirect);
        }else{
            exit(json_encode(array('retCode'=>-1,'retInfo'=>'跳转登录','url'=>$redirect)));
        }
    }

}