<?php

/**
 * 获取所有应用种类
 * @author v_zjzzhu
 *create time 2016-02-26
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