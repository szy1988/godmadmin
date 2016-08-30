<?php

/**
 * 获取所有的业务类型
 * @author jaspersong
 *create time 2015-10-08
 */

class getServiceMap extends hook
{
    public function hook($module,$action,$func,$type='')
    {

        $tbServiceMap = $this->dao->tbServiceMap;

        $serviceMaps = $tbServiceMap->getActInfoById();
        $serviceMap = array();
        foreach ($serviceMaps as $k => $v) {
            $serviceMap[$v['sServiceKey']] = $v['sServiceName'];
        }
        $this->tpl->assign('serviceMap', $serviceMap);
    }
}