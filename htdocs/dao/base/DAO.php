<?php
/**
 * 数据操作类, 单例
 *
 * @author jaspersong timmu
 *
*/
class DAO
{
    public $db;

    /**
     * singleton
     * @var type
     */
    private static $instance;

    private function __construct(){
    }

    public static function getInstance( $config_db="DB" ) {
    	if(!isset(self::$instance[$config_db])) {
				$config = config::getInstance();
				$nodeName = (ENV!=='') ? 'db_'.ENV : 'db';
			    $db = new DBMysql(
                    $config->{$nodeName}["{$config_db}"]["host"],
                    $config->{$nodeName}["{$config_db}"]["user"],
                    $config->{$nodeName}["{$config_db}"]["password"],
                    $config->{$nodeName}["{$config_db}"]["database"],
                    $config->{$nodeName}["{$config_db}"]["port"]
                );
    	    	self::$instance[$config_db] = $db;
    	}
    	return self::$instance[$config_db];
    }


}
?>