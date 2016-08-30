<?php

/*
  公共函数
 */

function GetGlobalConfig() {
    global $g_config;
    if (!isset($g_config)) {
        $g_config = parse_ini_file(CONFIG_PATH, true);
    }
    return $g_config;
}

function GetCommonConfig() {
    $g_config = parse_ini_file("/usr/local/commweb/cfg/CommConfig/commconf.cfg", true);
    return $g_config;
}

function GetIP() {
//if(!empty($_SERVER["HTTP_CLIENT_IP"]))
//   $cip = $_SERVER["HTTP_CLIENT_IP"];
//else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
//   $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
//else if(!empty($_SERVER["REMOTE_ADDR"]))
    $cip = $_SERVER["REMOTE_ADDR"];
//else
//   $cip = "";
    return $cip;
}

/**
 * GBK转UTF8，传入的数据可为数组或字符串
 * 数组则继续解析到字符串
 * @param $str
 * @return unknown_type
 */
function GBKtoUTF8($str) {
    if (is_array($str)) {
        foreach ($str as &$value) {
            $value = GBKtoUTF8($value);
        }
        return $str;
    } elseif (is_string($str)) {
        $str = iconv("GBK", "UTF-8//IGNORE", $str);
        return $str;
    } else {
        return $str;
    }
}

/**
 * UTF8转GBK，传入的数据可为数组或字符串
 * 数组则继续解析到字符串
 * @param $str
 * @return unknown_type
 */
function UTF8toGBK(&$str) {
    if (is_array($str)) {
        foreach ($str as &$value) {
            $value = UTF8toGBK($value);
        }
        return $str;
    } elseif (is_string($str)) {
        $str = iconv("UTF-8", "GBK//IGNORE", $str);
        return $str;
    } else {
        return $str;
    }
}

function jsEncode($str) {
    $trans = array(
        '<' => '&#60;',
        '>' => '&#62;',
        "'" => '&#39;',
        '"' => '&#34;',
        ',' => '&#44;',
        '(' => '&#40;',
        ')' => '&#41;',
        '?' => '&#63;',
        '\\' => '&#92;',
    );
    return strtr($str, $trans);
}

function MyUrlEncode($str) {
    if (is_array($str)) {
        foreach ($str as &$value) {
            $value = MyUrlEncode($value);
        }
        return $str;
    } elseif (is_string($str)) {
        $str = urlencode($str);
        return $str;
    } else {
        return $str;
    }
}

function MyUrlDecode($str) {
    if (is_array($str)) {
        foreach ($str as &$value) {
            $value = MyUrlDecode($value);
        }
        return $str;
    } elseif (is_string($str)) {
        $str = urldecode($str);
        return $str;
    } else {
        return $str;
    }
}

/**
 * javascript redirection
 * @param <type> $url
 */
function js_redirect($url) {
    echo "<script type=\"text/javascript\">setTimeout(\"redirect()\",1000);function redirect(){window.location = \"$url\";}</script>";
}

//遍历全部目录下的指定文件，并加载
function autoLoadFile($path, $para) {
    $filelist = glob($path . $para);

    $filelistnew = array();
    foreach ($filelist as $v) {
        $namelen = strlen($v);
        $filelistnew[$namelen][] = $v;
    }
//	ksort($filelistnew);

    $filelistresult = array();
    foreach ($filelistnew as $v) {
        $filelistresult = array_merge($filelistresult, $v);
    }
    foreach ($filelistresult as $filename) {
        require($filename);
    }
}

function urlDecoder($arr) {
    foreach ($arr as $key => $value) {
        $arr["$key"] = urldecode($value);
    }
    return $arr;
}


/** 发送微信 * */
function sendWeiXinText($Rcptto, $sMsg) {
    $Sender = "gpmweb";
    $isText = GBKtoUTF8($sMsg);
    $jsonData = str_replace("\'", "\"", json_encode(
                    array(
                        "Sender" => $Sender,
                        "Rcptto" => $Rcptto,
                        "isText" => urlencode($isText)
                    )
            )
    );
    $url = 'http://10.185.8.11/cgi-bin/sendmsg?data=' . $jsonData;
    $ret = json_decode(GBKtoUTF8( file_get_contents($url) ), true);

    if ($ret['errCode'] != 0) {
        OSS_LOG(__FILE__, __LINE__, LP_DEBUG, "SEND WEIXIN ERROR MsgId:" . $ret['msgid'] . ", errCode:" . $ret['errCode'] . ", errMsg:" . UTF8toGBK($ret['errMsg']) . "\r\n");
    }
}

/** 发送微信 * */
function sendWeiXinTable($Rcptto, $table = array() ) {
    $Sender = "gpmweb";
    $table = MyUrlEncode(GBKtoUTF8($table));

    $jsonData = str_replace("\'", "\"", json_encode(
                    array(
                        "Sender" => $Sender,
                        "Rcptto" => $Rcptto,
                        "isTable" => $table
                    )
            )
    );
    $url = 'http://10.185.8.11/cgi-bin/sendmsg?data=' . $jsonData;
    $ret = json_decode(GBKtoUTF8( file_get_contents($url) ), true);

    if ($ret['errCode'] != 0) {
        OSS_LOG(__FILE__, __LINE__, LP_DEBUG, "SEND WEIXIN ERROR MsgId:" . $ret['msgid'] . ", errCode:" . $ret['errCode'] . ", errMsg:" . $ret['errMsg'] . "\r\n");
    }
}

function getGpmDefaultUser() {
	$config = GetGlobalConfig();
    $users = explode(",", $config['WEIXIN']['default']);
        $users = array_unique($users);
        return $users;
	}


function sendGpmMsg($user=array(), $content, $title='') {
	if (!empty ($content)) {
		$qc = new QC();
		$users = array_unique(array_merge(getGpmDefaultUser(), $user));
		foreach ($users as $user) {
			$param = array (
				"Sender" => $user,
				"Receiver" => $user,
				"Title" => $title ? $title : "GPM通知",
				"MsgInfo" => $content
			);
			$param = GBKtoUTF8($param);
			$ret = $qc->SendRTX($param);
			//$ret = $qc->SendMail($param);
			$ret = $qc->SendSMS($param);
		}
	}
}

/**
 * 过滤用户输入参数
 *
 * @param type $param
 * @return type
 */
function cleanInput( $param ){
    if (is_array($param)){
        foreach ($param as $k => $v){
            $param[$k] = cleanInput($v); //recursive
        }
    }
    elseif (is_string($param)){
        $param = trim($param);

        // filter XSS
        $param = htmlspecialchars( $param );
        // filter SQL injection
        $trans = array(
            '"' => '&quot;',
            '\'' => ''
        );
        $param = strtr($param,$trans);
    }
    return $param;
}

/**
 * 钩子函数
 * $module string 模块名
 * $action string 控制器名
 * $func string   方法名
 *
 */
function hook($module,$action,$func){
    $config = config::getInstance();
    $hook = $config->hook;
    $unhook = $config->hook['!'];

    $unFuncHook = isset($unhook[$module][$action][$func]) ? $unhook[$module][$action][$func] : array();
    $unActionHook = isset($unhook[$module][$action]['*']) ? $unhook[$module][$action]['*'] : array();
    $unModuleHook = isset($unhook[$module]['*']['*']) ? $unhook[$module]['*']['*'] : array();
    $unCommonHook = isset($unhook['*']['*']['*']) ? $unhook['*']['*']['*'] : array();
    $unFuncHook = array_merge($unCommonHook,$unModuleHook,$unActionHook,$unFuncHook);
    $unFuncHook = array_unique($unFuncHook);

    $funcHook = isset($hook[$module][$action][$func]) ? $hook[$module][$action][$func] : array();
    $actionHook = isset($hook[$module][$action]['*']) ? $hook[$module][$action]['*'] : array();
    $moduleHook = isset($hook[$module]['*']['*']) ? $hook[$module]['*']['*'] : array();
    $commonHook = isset($hook['*']['*']['*']) ? $hook['*']['*']['*'] : array();
    $funcHook = array_merge($commonHook,$moduleHook,$actionHook,$funcHook);
    $funcHook = array_unique($funcHook);
    if(!empty($funcHook)){
        foreach ($funcHook as $key => $value) {
            
            if(in_array($value, $unFuncHook)){
                continue;
            }

            $path = DIR_PATH.'/hook/'.$value.'.php';
            if(is_file($path)){
                include_once($path);
                $hook = new $value;
                $hook->hook($module,$action,$func);
            }
        }
    }
}

/**
 * 输入日期区间范围，返回区间范围内每天日期的数组，Array([0] => 2014-04-21,[1] => 2014-04-22,[2] => 2014-04-23,[3] => 2014-04-24,[4] => 2014-04-25,[5] => 2014-04-26)
 * @param datetime $begintime
 * @param datetime $endtime
 * @return array:
 */
function dateRangeToEverydayArray($begintime,$endtime){
    $_time = range(strtotime($begintime), strtotime($endtime), 86400);
    return array_map(create_function('$v', 'return date("Y-m-d", $v);'), $_time);
}

function doCurlPost($url, $keysArr, $header = array()) {
//      $header=$this->buildHeader();
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
?>