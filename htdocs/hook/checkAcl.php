<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/9/22
 * Time: 14:49
 * 判断权限
 */

class checkAcl extends hook
{
    public function hook($module,$action,$func,$type='')
    {

        $aclArr = $this->config->acl;
        
        if(in_array($func, $aclArr['no_login'][$module][$action])){
            return true;
        }

        if (!in_array($func, $aclArr['no_acl'][$module][$action]) || $type=='post') {
            $rtx = $_SESSION['right_user'];
            $iPos = strpos($_SERVER['REQUEST_URI'], 'index.php');
            if (!$iPos) {
                $url = $_SERVER['REQUEST_URI'].'checkOALogin.php';
            } else {
                $url = substr($_SERVER['REQUEST_URI'], 0, $iPos) . 'checkOALogin.php';
            }
            $acl = Acl::getInstance($aclArr);
            $isAcl = $acl->CheckUserAcl($rtx, $module, $action, $func);
            //说明没有权限
            if (!$isAcl) {
                if($type=='post'){
                    $this->tridentJson('1','没有权限',array('retNotice'=>0));
                    exit;
                }else{
                    header("location:index.php?module=common_until&action=no_acl");
                exit;
                }

                
            }
        }

        return true;
    }

    private function showAlert($iRet, $msg, $url = '')
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo '<script>';
        echo "alert('".$msg."');";
        if ($url != '') {
            echo "location.href = '{$url}';";
        }
        echo "</script>";
        exit;
    }
}