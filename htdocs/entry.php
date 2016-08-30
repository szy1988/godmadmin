<?php
// setcookie('right_user','jaspersong');
// setcookie('right_user','v_zjzzhu');
/*
*头部应用程序
*/
//phpinfo();
//exit;
ini_set( 'display_errors', 'on');
error_reporting( 0 );
// error_reporting( E_ERROR );
// error_reporting(E_ALL);

//如果是cli模式，直接定义到正式�IP
if (php_sapi_name() == 'cli') {
    define('CUR_ENV_IP','10.193.9.32');
} else {
    define('CUR_ENV_IP',$_SERVER['SERVER_ADDR']);
}
session_start();
define('TEST_IP','10.12.239.238');
define('DEV_IP','10.194.0.196');
define('OP_IP','10.193.9.32');

switch (CUR_ENV_IP) {
    case TEST_IP:
        define( 'ENV', 'test' );
        break;
    case DEV_IP:
        define( 'ENV', 'dev');
        break;
    case OP_IP:
        define( 'ENV', '');
        break;
    default:
        define( 'ENV', 'test');
        break;
}


define( 'ROOT_PATH',  dirname( __FILE__ ) );
define( 'DIR_PATH', dirname( __FILE__ ));
define( 'TMP_PATH', ROOT_PATH.'/tmp');
define( 'UPLOAD_PATH', ROOT_PATH.'/static/upload');
define( 'IMPORTEXCEL_PATH', ROOT_PATH.'/static/importexcel');
define( 'STATISTIC_PATH', ROOT_PATH.'/static/statistics_new');
define( 'SHELL_PATH', '/data/webadmin/admin-website/gpmwebadmin/htdocs/shell');
define( 'TMP_DIR', '/tmp/cfClientPop');
define( 'STATISTIC_LOG_PATH', STATISTIC_PATH.'/log');

//static content
if( CUR_ENV_IP == TEST_IP ){
    $host = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $host = str_replace("index.php","",$host);
    define('HOST', $host);
    define('FRONTEND_HOST', $host);
    define( 'TPL_SAVE_PATH', ROOT_PATH.'/tpl' );
    define( 'UPLOAD_IMG_PATH',  UPLOAD_PATH );
}else if ( CUR_ENV_IP == DEV_IP ){
    $host = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $host = str_replace("index.php","",$host);
    define('HOST', $host);
    define('FRONTEND_HOST', $host);
    define( 'TPL_SAVE_PATH', ROOT_PATH.'/tpl' );
    define( 'UPLOAD_IMG_PATH',  UPLOAD_PATH );

}else{
    $host = 'http://'.$_SERVER['HTTP_HOST']."/";
    define('HOST', $host);
    define('FRONTEND_HOST', 'http://apps.game.qq.com/client_pop/');
    define( 'TPL_SAVE_PATH', UPLOAD_PATH.'/tpl' );
    define( 'UPLOAD_IMG_PATH', UPLOAD_PATH.'/clientpop' );
    define( 'TPL_PUBLISH_PATH', '/usr/local/appsweb/htdocs/client_pop/tpl' );
    define( 'IMG_PUBLISH_PATH', '/usr/local/ossweb-img/htdocs/images/godm' );
    define( 'CFG_SAVE_PATH', UPLOAD_PATH.'/gen_config' );
    define( 'CFG_APPS_PUBLISH_PATH', '/usr/local/appsweb/htdocs/CommArticle/time/gen_php/' );
    define( 'CFG_CONDITION_PUBLISH_PATH', '/usr/local/conditionweb/htdocs/condition/cf/client_pop/gen_php/' );
}


require_once 'osslib.inc.php';
//加载公共函数库
require_once DIR_PATH.'/lib/common.php';
//加载核心父类
require_once DIR_PATH.'/core/AbstractAction.php';
//加载钩子父类
require_once DIR_PATH.'/core/hook.php';

//加载lib里面的公共类
function loadLib($name){
	$path = ROOT_PATH.'/lib/'.$name.'.class.php';
	if(file_exists($path)){
		include_once($path);
	}
}
spl_autoload_register('loadLib');

//注册OSSLIB
spl_autoload_register('__autoload');
?>