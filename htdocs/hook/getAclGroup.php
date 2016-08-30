<?php

/**
 * 获取权限组
 * @author v_zjzzhu
 *create time 2016-03-08
 */

class getAclGroup extends hook
{
    public function hook($module,$action,$func,$type)
    {
        // if(ENV != ''){
        //     return true;//测试环境直接跳过
        // }
        //获取用户的权限组
        $sRtx = $_SESSION['right_user'];
        $acl = Acl::getInstance($this->config->acl);
        $aclGroup = $acl->getUserModule($sRtx);
        if($aclGroup['ret'] == 1){
        	$this->tpl->assign('aclGroup',$aclGroup['data']);
        }
        
    }
}