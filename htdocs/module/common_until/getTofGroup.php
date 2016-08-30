<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/14
 * Time: 16:43
 */
// ini_set( 'display_errors', 'on');
// error_reporting(E_ALL);
class getTofGroup extends common_until
{

    public function __construct($params)
    {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index()
    {
        include_once(DIR_PATH.'/lib/Tof.class.php');
        $params = array('id'=>0,'typeId'=>1);
        $url = new URLTOF();
        $rt = $url->get('http://oss.api.tof.oa.com/api/v1/OrgUnit/DeptsByTypeID',$params);

        $info = json_decode($rt,true);
        // $curlPostRes = $this->doCurlPost('http://oss.api.tof.oa.com/api/v1/OrgUnit/DeptsByTypeID?id=1&typeId=1');
        // $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
        var_dump($info);

    }

    public function zxlist()
    {
        include_once(DIR_PATH.'/lib/Tof.class.php');
        $params = array('id'=>17649);
        $url = new URLTOF();
        $rt = $url->get('http://oss.api.tof.oa.com/api/v1/OrgUnit/GetAllChildrenOrgUnitInfos',$params);

        $info = json_decode($rt,true);
        // $curlPostRes = $this->doCurlPost('http://oss.api.tof.oa.com/api/v1/OrgUnit/DeptsByTypeID?id=1&typeId=1');
        // $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
        print_r($info);

    }

}