<?php

/**
 * 设置导航
 * @author v_zjzzhu
 *create time 2015-10-08
 */

class setNavigation extends hook
{
    public function hook($module,$action,$func,$type='')
    {
    	$this->tpl->assign('serviceType',$_SESSION['serviceType']);
        $this->tpl->assign('navigation',$module);
        $this->tpl->assign('navigation_action',$action);
    }
}