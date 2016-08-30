<?php

/**
 * 判断用户是否登录
 * @author timmu
 * @since 2015年12月4日 17:31:17
 */

class checkTof extends hook
{
    public function hook($module,$action,$func)
    {



        $aclArr = $this->config->acl;
        if(ENV == 'test'){
            return true;
        }
        if(isset($_SESSION['right_user']) && isset($_SESSION['X_TICKET']) && !empty($_SESSION['X_TICKET_EXPIRATION']) && $_SESSION['X_TICKET_EXPIRATION']<time()){
            return true;
        }else{
            $ticket = $_GET['ticket']?$_GET['ticket']:$_SESSION['X_TICKET'];
            if(!empty($ticket)){

                include_once(DIR_PATH.'/lib/Tof.class.php');
                // $ticket = $_GET['ticket'];

                $params = array('appkey'=>'67967952b77041269c4a1b4e5914244a','browseIP'=>$_SERVER['HTTP_X_REAL_IP'],'encryptedTicket'=>$ticket);
                $url = new URLTOF();

                $rt = $url->get('http://oss.api.tof.oa.com/api/v1/Passport/DecryptTicketWithClientIP',$params);

                $info = json_decode($rt,true);

                if($info['Ret'] == 0){
                    $_SESSION['right_user'] = $info['Data']['LoginName'];
                    $_SESSION['X_TICKET'] = $ticket;
                    $_SESSION['X_TICKET_EXPIRATION'] = time()+1800;
                    return true;
                }else{
                    // print_r($info);exit;
                }
            }

            //判断不需要登录的方法
            if(in_array($func, $aclArr['no_login'][$module][$action])){

                return true;//不需要登录的直接跳过
            }

            //获取当前url

            if($_SERVER['QUERY_STRING'] == 'module=totalApp&action=caseUpdate' || strstr($_SERVER['QUERY_STRING'],'module=totalApp&action=caseShowForAction')){
                $curUrl = 'http://x.ied.com/index.php?'.$_SERVER['QUERY_STRING'];
            }else{
                $curUrl = 'http://x.ied.com/'.$_SESSION['serviceType'].'/index.php';
            }
            
            // $curUrl = 'http://x.ied.com/';
            $redirect = 'http://login.oa.com/modules/passport/signin.ashx?appkey=67967952b77041269c4a1b4e5914244a&url='.urlencode($curUrl);
            //判断请求方式，防止在表单请求时出现跨域错误，无法提示错误信息
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                header('LOCATION:'.$redirect);
                exit;
            }else{

                $retJs = '{literal}<script>window.location.href = "'+$redirect+'";</script>{/literal}';

                exit($retJs);
                // exit(json_encode(array('retCode'=>-1,'retInfo'=>'not login','url'=>$redirect)));
            }

        }
    }

}