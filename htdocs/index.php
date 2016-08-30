<?php
require_once 'entry.php';
class frameWork{
	public function __destruct(){
	}


    function StartApp()
    {
        
        $atuhJson['dExpireDate'] = '2016-6-20';
        var_dump(strtotime($atuhJson['dExpireDate']));
        var_dump(strtotime(date('Y-m-d')));exit;
        var_dump(strtotime($atuhJson['dExpireDate'])<strtotime(date('Y-m-d')) ) ;
        //echo date('Y-m-d');
        return;
        if(php_sapi_name() == 'cli'){
            //exit('only use cli visit this file!');
            $options = getopt('m:a:f:p:');
            if (!isset($options['m']) || $options['m'] == '') {
                $this->clioutput(-1, 'use -m "moudle" to input');
            }
            if (!isset($options['a']) || $options['a'] == '') {
                $this->clioutput(-1, 'use -a "action" to input');
            }
            //-p "key1=1&key2=2"
            parse_str($options['p'],$params);
            $params['module'] = $options['m'];
            $params['action'] = $options['a'];
            $params['func'] = $options['f'];
            $goHook = false;
        }else {
            //从前端传入一些相应的参数
            $params = array_merge($_POST, $_GET);//xss过滤 todo
            var_dump($_GET);
            exit;
            //根据serviceType判断页面走向
            if(empty($_GET)){
                $params['module'] = 'common_until';
                $params['action'] = 'no_acl';
            }else if(isset($params['serviceType']) && !empty($params['serviceType'])){
                $_SESSION['serviceType'] = $params['serviceType'];
            }else{
                $params['serviceType'] = $_SESSION['serviceType'];
            }



            // if(isset($params['serviceType']) && !empty($params['serviceType'])){
            //     $_SESSION['serviceType'] = $params['serviceType'];
            // }else if($_SESSION['serviceType'] && $_SESSION['serviceType'] != 'dev' && $_SESSION['serviceType'] != 'd'){
            //     $params['serviceType'] = $_SESSION['serviceType'];
            // }else{
                
            // }
            $goHook = true;
        }
        //定义路由规则，www.oa.com/index.php?module=stat&action=stat
        //加载父类
        $module = (isset($params['module']) && ($params['module']!=='')) ? $params['module'] : 'totalApp';
        $modulePath = ROOT_PATH.'/module/'.$module.'.php';
        if(!file_exists($modulePath)){//业务名以文件夹命名，业务父类与业务名保持一致
            $this->output(-1, 'module does not support');
        }
        include_once($modulePath);
        //加载子类
        $action = (isset($params['action']) && ($params['action'] !=='')) ? $params['action'] :((isset($params['serviceType']) && ($params['serviceType']=='dev' || $params['serviceType']=='d'))?'totalDevAppList':'totalAppList');
        $actionPath = ROOT_PATH.'/module/'.$module.'/'.$action.'.php';
        if(!file_exists($actionPath)){
            $this->output(-1, 'action does not support');
        }
        include_once($actionPath);

        //找执行方法 func
        $func = (isset($params['func'])&&($params['func']!=='')) ? $params['func'] : 'index';
        $login = $params['login'];
        // 根据action来初始化相应的model 来处理
        try {
            //cli 不走hook
            if ($goHook){
                hook($module,$action,$func,$params['type']);
            }
            $model = new $action($params);
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
