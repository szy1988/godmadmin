<?php

/**
 * 判断用户是否登录
 * @author v_zjzzhu
 *create time 2015-10-08
 */

class checkUserOALogin extends hook
{
    public function hook($module,$action,$func,$type='')
    {
        $userName = $_SESSION['right_user'];
        if( empty($userName) ){
            $this->tpl->assign( 'isLogin', 0 );
        }
        else{
            $this->tpl->assign( 'userName', $userName );
            $this->tpl->assign( 'isLogin', 1 );
        }
        $GODMInit = '<script type="text/javascript">
                        EAS.GODMInit({
                        "user": "'.$userName.'",
                        "serviceType": "'.$_SESSION['serviceType'].'",
                        "sysName": "55.xiedcom"
                    })
                    </script>';
        $this->tpl->assign( 'GODMInit', $GODMInit );
        return;
    }
}