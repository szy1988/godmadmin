<?php

/**
 * 获取所有应用种类
 * @author v_zjzzhu
 *create time 2016-02-26
 */

class updateAuth extends hook
{
    public function hook($module,$action,$func,$type='')
    {
        $rtx = $_SESSION['right_user'];
        $serviceType = $_SESSION['serviceType'];
        if(!empty($rtx)){
            $tbMyApp = $this->dao->tbMyApp;
            $res = $tbMyApp->queryAuthstate($rtx);
            if($res){
                foreach($res as $v){
                    $checkAclUrl = 'http://auth.ied.com/api/getAuthInfo.php?action=getAuthList&user_name='.$rtx.'&platID='.$v['iPlatID'].'&isPlatToService='.$v['iIsPlatToService'].'&serviceType='.$serviceType;
                    $curlGetRes = doCurlPost($checkAclUrl,array());
                    $curlGetJson = json_decode(substr($curlGetRes,stripos($curlGetRes, '=')+2),TRUE);
                    if($curlGetJson['ret']==1){
                        $tbMyApp->updateAuthstate($v['id']);
                    }
                }
            }
        }

    }
}