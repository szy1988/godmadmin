<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/14
 * Time: 16:43
 */

class otherPlatHeader extends common_until
{

    public function __construct($params)
    {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index()
    {
    	$right_user = $this->m_params['right_user'];
    	$plat_param = $this->m_params['plat_param'];
    	$serviceType = $this->m_params['serviceType'];
        
    	$ServiceMaps = $this->getServiceMap();
        if(array_key_exists($serviceType, $ServiceMaps)){
            $serviceTypeName = $ServiceMaps[$serviceType];
        }else{
            $serviceTypeName = $this->m_params['serviceTypeName'];
        }
    	$plat_name = $this->m_params['plat_name'];
    	$this->tpl->assign('right_user',$right_user);
    	$this->tpl->assign('plat_param',$plat_param);
    	$this->tpl->assign('plat_name',$plat_name);
    	$this->tpl->assign('serviceType',$serviceTypeName);
        $this->tpl->display('otherPlatHeader.html');

    }
}