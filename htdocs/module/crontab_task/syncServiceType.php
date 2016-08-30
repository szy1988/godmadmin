<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/14
 * Time: 16:43
 */

class syncServiceType extends task
{

    public function __construct($params)
    {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index()
    {
    	$service_content = file_get_contents('http://webplat.ied.com/js/DOMAIN_INFO_DATA.js');

        $service_str = (substr($service_content,21));

        $service_str=mb_convert_encoding($service_str,'UTF-8','GBK');

        $service_array = (json_decode($service_str));

        $insert_array = array();

        foreach($service_array as $v){
            if($v->s_ccname && $v->s_ccname != 'null'){
                $insert_array[$v->s_service_type] = $v->s_service_description_new;
            }  
        }
        $tbServiceMap = $this->dao->tbServiceMap;
        $res = $tbServiceMap->updateService($insert_array);
        echo $res;
    }
}