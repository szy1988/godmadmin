<?php
require_once 'entry.php';
class frameWork{
	public function __destruct(){
	}


    function StartApp()
    {

        //从前端传入一些相应的参数
        $params = array_merge($_POST, $_GET);//xss过滤 todo
        //设置业务cookie
        if(!isset($params['serviceType']) || empty($params['serviceType'])){
            $params['serviceType'] = 'lol';
        }

        $modulePath = ROOT_PATH.'/module/common_until.php';
        if(!file_exists($modulePath)){//业务名以文件夹命名，业务父类与业务名保持一致
            $this->output(-1, 'module does not support');
        }
        include_once($modulePath);
        //加载子类
        $actionPath = ROOT_PATH.'/module/common_until/otherPlatHeader.php';
        if(!file_exists($actionPath)){
            $this->output(-1, 'action does not support');
        }
        include_once($actionPath);

        //找执行方法 func
        $func = (isset($params['func'])&&($params['func']!=='')) ? $params['func'] : 'index';

        // 根据action来初始化相应的model 来处理
        try {
            
            $model = new otherPlatHeader($params);
            $res = $model->$func();

        } catch (Exception $e) {
            $this->output(-1, 'function does not support'.$e);
        }
    }

    protected function output($iRetcode, $sErrorMsg, $vmResult=array()) {
        $res = array(
            'retCode' => $iRetcode,
            'retInfo' => $sErrorMsg,
            'data' => $vmResult,
        );

        $res = GBKtoUTF8($res);
        echo json_encode($res);
		exit;
    }

    protected function clioutput($iRet, $msg)
    {
        echo '-------------------------'.chr(10);
        echo 'please check error_info:' . $msg . chr(10);
        echo '-------------------------'.chr(10);
        exit;
    }


}
$app = new frameWork();
$app->StartApp();
?>
