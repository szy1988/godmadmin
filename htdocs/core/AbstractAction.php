<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractAction
 *
 * @author timmu  jaspersong
 */


abstract class AbstractAction {
    protected $m_params;
    protected $m_result;

    public function __construct( $params ) {
        $this->m_params = $params;
    }

    //逻辑执行函数
    abstract public function index();

    protected function outputJson( $iRetcode, $sErrorMsg, $vmResult=array(),$jsonp='')
    {
        $res = array(
            'retCode' => $iRetcode,
            'retInfo' => $sErrorMsg,
            'data' => $vmResult
        );
        if ( $jsonp ){
            echo "var $jsonp = ".json_encode($res);
        }else {
        	echo json_encode($res);
        }
        exit;
    }
    
    protected function tridentJson( $iRetcode, $sErrorMsg, $vmResult=array())
    {
        $res = array(
            'retCode' => $iRetcode,
            'retInfo' => $sErrorMsg,
        );
        foreach($vmResult as $k=>$v){
            $res[$k]=$v;
        }
        echo json_encode($res);
        
        exit;
    }

	protected function generateConfigPHP( $actInfos ){
        $strArr = var_export( $actInfos, true );
        $outputStr = "<?php \n";
        $outputStr .= "return ";
        $outputStr .= $strArr;
        $outputStr .= ";";

        return $outputStr;
    }

    /**
     * 将PHP 数组转化为XML格式的配置文件
     * @author jasper
     * @param type $actInfos
     * @return type
     */
    protected function generateConfigXML2( $actInfos, $firstKey, $secKey){
        $xml = new SimpleXMLElement($firstKey);
        $this->arrayToXML2($actInfos, $xml, $secKey);
        $xmlDoc = new DOMDocument ();
        $xmlDoc->loadXML ( $xml->asXML() );
        $xmlDoc->preserveWhiteSpace = false;
        $xmlDoc->formatOutput = true;
        return $xmlDoc->saveXML();
    }

    protected function arrayToXML2($arr, &$xml_info, $secKey='key') {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_info->addChild("$key");
                    $this->arrayToXML2($value, $subnode);
                } else {
                    $subnode = $xml_info->addChild($secKey);
                    $this->arrayToXML2($value, $subnode);
                }
            } else {
            	if (!is_numeric($key)){
                    $xml_info->addChild("$key", "$value");
            	}else {
                    $xml_info->addChild($secKey, "$value");
            	}
            }
        }
    }

	//移动到common
    protected function doCurlPost($url, $keysArr, $header = array()) {
//		$header=$this->buildHeader();
    	if (stristr($url,'?')){
    		$lastStr = substr($url,strlen($url)-1,1);
    	    if ($lastStr == '&'){
                $url .= '_t='.time();
            }else {
                $url .='&_t='.time();
            }
    	}else {
    		$url .='?_t='.time();
    	}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		if ($keysArr){//如果不是POST 会报参数错误
			curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($keysArr));
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);
        if($ret === false){
            var_dump(curl_error($ch));exit;
        }
		$error = curl_error($ch);
		curl_close($ch);
		return $error ? $error : $ret;

	}

    public function getThisWeekStart() {
        $week = date("W",time());
        $year = date("Y",time());
        $from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week

        return $from;
    }


    /**
     * 判断是否是ajax请求
     * @author timmu
     * @since 2015年9月2日 16:48:21
     */
    public function is_ajax_request()
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    /**
     * 载入各种配置项
     * @author timmu
     * @since 2015年9月2日 16:48:21
     */
    public function loadConfig()
    {
        $this->config = config::getInstance();
    }

    /**
     * 载入模版类
     * @author timmu
     * @since 2015年9月4日 17:36:48
     */
    public function loadTpl()
    {
        $this->tpl = tpl::getInstance();
    }

    public function loadDb(){
        require_once DIR_PATH.'/dao/base/DAO.php';
        require_once DIR_PATH.'/dao/base/Abstract.dao.php';
        $this->dao = getDao::getInstance();
    }

    public function loadOssLog()
    {
        $this->OssLog = OssLog::getInstance();
    }

    public function loadTof()
    {
        require_once DIR_PATH.'/lib/Tof.class.php';
        $this->qc = new QC();
    }

    public function loadphpqrcode()
    {
        require_once DIR_PATH.'/lib/phpqrcode.class.php';
        $this->qr = new QRcode();
    }

    /**
     * 按需载入系统支持类库
     * $loadFunction存放类名与载入类实例方法的对应map
     * @param  string $param 系统支撑类对象
     * @return object
     * @author timmu
     * @since 2015年9月2日 17:38:14
     */
    public function __get($param)
    {
        $loadFunction = array('config'=>'loadConfig','tpl'=>'loadTpl','dao'=>'loadDb','OssLog'=>'loadOssLog','qc'=>'loadTof','qr'=>'loadphpqrcode');

        if(isset($loadFunction[$param])){
            $this->$loadFunction[$param]();
        }
        return $this->$param;
    }

    
    protected function array_unique_fb($array2D){
                 foreach ($array2D as $v){
                     $v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
                     $temp[] = $v;
                 }
                 $temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
                foreach ($temp as $k => $v){
                    $temp[$k] = explode(",",$v);   //再将拆开的数组重新组装
                }
                return $temp;
            }
   

    /**
     * iframe提交统一的提示
     * @param $iRet
     * @param $msg
     */
    protected function showFrameAlert($iRet, $msg)
    {
        $domain = (strpos($_SERVER['SERVER_NAME'], 'oa.com')) ? 'oa.com' : 'qq.com';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo '<script>';
        echo 'document.domain = "'.$domain.'";';
        echo "parent.ShowCommAlert('".$iRet."','".$msg."');";
        echo "</script>";
        exit;
    }

    public function getLoginUrl()
    {
        $iPos = strpos($_SERVER['REQUEST_URI'], 'index.php');
        if (!$iPos) {
            $url = $_SERVER['REQUEST_URI'].'checkOALogin.php';
        } else {
            $url = substr($_SERVER['REQUEST_URI'], 0, $iPos) . 'checkOALogin.php';
        }

        $headerUrl = 'http://' . $_SERVER['HTTP_HOST'] . $url;
        $loginUrl = "http://passport.oa.com/modules/passport/signin.ashx?title=Mobile Recommend&url=" . $headerUrl;
        return $loginUrl;
    }

    public function getLogoutUrl()
    {
        $iPos = strpos($_SERVER['REQUEST_URI'], 'index.php');
        if (!$iPos) {
            $url = $_SERVER['REQUEST_URI'].'checkOALogin.php';
        } else {
            $url = substr($_SERVER['REQUEST_URI'], 0, $iPos) . 'checkOALogin.php';
        }

        $headerUrl = 'http://' . $_SERVER['HTTP_HOST'] . $url;
        $loginUrl = "http://passport.oa.com/modules/passport/signout.ashx?Nosignin=1&url=" . $headerUrl;
        return $loginUrl;
    }
    
    public function getServiceMap()
    {
        $tbServiceMap = $this->dao->tbServiceMap;
        $serviceMaps = $tbServiceMap->getActInfoById();
        $serviceMap = array();
        foreach ($serviceMaps as $k => $v) {
            $serviceMap[$v['sServiceKey']] = $v['sServiceName'];
        }
        return $serviceMap;
    }

    public function getDevGroup()
    {
        $tbDevGroup = $this->dao->tbDevGroup;
        $devGroupList = $tbDevGroup->getActInfoById();
        $devGroup = array();
        foreach ($devGroupList as $k => $v) {
            $devGroup[$v['id']] = $v['sGroupName'];
        }
        return $devGroup;
    }
    public function getAppKind(){
     $tbAppKind = $this->dao->tbAppKind;
        $res = $tbAppKind->getAppKindByOrder();
        $data = array();
        foreach($res as $k=>$v){
            $data[$v['iGroupKind']][$v['id']] = $v['sKindName'];
        }
        return $data;
    }

    public function sendRTX($MsgInfo){
        $param = array (
                    "Title"=>"营销手册通知",
                    "Sender" => $_SESSION['right_user'],
                    'MsgInfo' => $MsgInfo,
                    'Receiver' => 'rebeccayang;jaspersong;v_zjzzhu'
                );

        $qc = $this->qc;
        $ret = $qc->SendRTX($param);
    }

    public function getGroupByCenter($iDevCenterID){
        include_once(DIR_PATH.'/lib/Tof.class.php');
        $params = array('id'=>$iDevCenterID);
        $url = new URLTOF();
        $rt = $url->get('http://oss.api.tof.oa.com/api/v1/OrgUnit/GetAllChildrenOrgUnitInfos',$params);
        $info = json_decode($rt,true);
        $res = array();
        if($info['Ret'] == 0){
            foreach($info['Data'] as $v){
                $res[$v['ID']] = $v['Name'];
            }
        }
        return $res;
    }

    protected function sync($sitename, $sourceFile, $destFile) {
        $res = false;
        if (!is_file($sourceFile)) {
            return false;
        }
        $sourceFile = str_replace('/data/website_static', '/usr/local', $sourceFile);
        $destFile = str_replace('/data/website_static', '/usr/local', $destFile);
        $destFile = str_replace('/usr/local/' . $sitename, '', $destFile);

        $cmd = "/usr/local/ieod-web/mon/bin/remotersynctool " . OCTOPUSD_IP . " " . OCTOPUSD_PORT . " release " . $sitename . " " . $sourceFile . " " . $destFile;

        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "[reg]sync cmd: " . $cmd . "\r\n");
        $ret = shell_exec($cmd);
        if (strpos($ret, 'fail') !== false) {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "[reg]sync ret: " . $ret . "\r\n");
            $res = false;
        } else {
            //OSS_LOG(__FILE__, __LINE__, LP_DEBUG, "[reg]sync ret: " . $ret . "\r\n");
            $res = true;
        }

        $destFile = '/usr/local/' . $sitename.$destFile;
        $destDir = dirname( $destFile );
        $cmd2 = SHELL_PATH."/remote_cmd.exp \"/usr/local/bin/ssh jaspersong@10.205.2.217#36000 \\\"mkdir -m 777 -p $destDir\\\"\" SzY214815";
        $ret = shell_exec($cmd2);
        //OSS_LOG(__FILE__, __LINE__, LP_ERROR, "mkdir ret: " . $ret . "\r\n");
        $cmd2 = SHELL_PATH."/remote_cmd.exp \"/usr/local/bin/scp $sourceFile jaspersong@10.205.2.217#36000:$destFile\" SzY214815";
        $ret = shell_exec($cmd2);
        //OSS_LOG(__FILE__, __LINE__, LP_ERROR, "scp ret: " . $ret . "\r\n");

        return $res;
    }

    protected function syncTest($sitename, $sourceFile, $destFile) {
        $res = false;
        if (!is_file($sourceFile)) {
            return false;
        }
        $sourceFile = str_replace('/data/website_static', '/usr/local', $sourceFile);
        $destFile = str_replace('/data/website_static', '/usr/local', $destFile);
        $destFile = str_replace('/usr/local/' . $sitename, '', $destFile);

        // 同步到217
        $destFile = '/usr/local/' . $sitename.$destFile;
        $destDir = dirname( $destFile );
        $cmd2 = SHELL_PATH."/remote_cmd.exp \"/usr/local/bin/ssh jaspersong@10.205.2.217#36000 \\\"mkdir -m 777 -p $destDir\\\"\" SzY214815";
        $ret = shell_exec($cmd2);
        //OSS_LOG(__FILE__, __LINE__, LP_ERROR, "mkdir ret: " . $ret . "\r\n");
        $cmd2 = SHELL_PATH."/remote_cmd.exp \"/usr/local/bin/scp $sourceFile jaspersong@10.205.2.217#36000:$destFile\" SzY214815";
        $ret = shell_exec($cmd2);
        //OSS_LOG(__FILE__, __LINE__, LP_ERROR, "scp ret: " . $ret . "\r\n");
        return $ret;
    }

}

?>
