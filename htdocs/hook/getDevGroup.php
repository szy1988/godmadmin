<?php

/**
 * 获取所有的业务类型
 * @author jaspersong
 *create time 2015-10-08
 */

class getDevGroup extends hook
{
    public function hook($module,$action,$func,$type='')
    {
        $tbDevGroup = $this->dao->tbDevGroup;
        $devGroupList = $tbDevGroup->getActInfoById();
        $devGroup = array();
        foreach ($devGroupList as $k => $v) {
            $devGroup[$v['id']] = $v['sGroupName'];
        }
        $this->tpl->assign('devGroup', $devGroup);
    }
}