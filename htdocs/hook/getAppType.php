<?php

/**
 * 获取所有的业务类型
 * @author jaspersong
 *create time 2015-10-08
 */

class getAppKind extends hook
{
    public function hook($module,$action,$func,$type='')
    {
        $tbAppKind = $this->dao->tbAppKind;
        $appKindList = $tbAppKind->getAppKindByOrder();
        $appKind = array();
        foreach ($appKindList as $k => $v) {
            $appKind[$v['id']] = $v['sKindName'];
        }
        $this->tpl->assign('appKind', $appKind);
    }
}