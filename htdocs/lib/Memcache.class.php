<?php
//defined('COMMON_MEMCACHE') || define('COMMON_MEMCACHE', '/data/website/commweb/cfg/CommMemcache/apps_memcache.cfg');
defined('COMMON_MEMCACHE') || define('COMMON_MEMCACHE', '/usr/local/commweb/cfg/CommConfig/comm_cmem_php.cfg');
defined('GENERAL_ERROR_DIR') || define('GENERAL_ERROR_DIR', '/usr/local/gpmwebadmin/htdocs/log/memc.log');

/**
 * get memcache server lists by parse cfg file
 */
function & mcache_parsefile_servers()
{
	//ÅäÖÃÎÄ¼þ
	if(is_file(COMMON_MEMCACHE))
	{
		$serverInfo = parse_ini_file(COMMON_MEMCACHE, true);
		if(false ===  $serverInfo)
		{
			throw new Exception(date("Y-m-d H:i:s").': parse ini file error !');
		}
	}
	else
	{
		throw new Exception(date("Y-m-d H:i:s").': common memcache config file is not exist !');
	}
	return $serverInfo ;
}

class CommonMemcache
{
	public static $sMemcache;

	private  function __construct()
	{
	}

	public static function init()
	{
		//1. Get a server array from config file
		$MemcacheHosts=array();
		try{
			$MemcacheHosts = mcache_parsefile_servers();
		}catch(Exception $e)
		{
			error_log(date("Y-m-d H:i:s").': get serverlist error:'.$e->getMessage().' \n', 3, GENERAL_ERROR_DIR);
			return false;
		}

		//2. Add memcached server to connection pool
		if(!isset($MemcacheHosts['cacheserverlist_cf']['server_num']) || $MemcacheHosts['cacheserverlist_cf']['server_num']<1)
		{
			error_log(date("Y-m-d H:i:s").': no found serverlist ! \n', 3, GENERAL_ERROR_DIR);
			return false;
		}

		self::$sMemcache = new Memcache;
		for($i=1; $i<=$MemcacheHosts['cacheserverlist_cf']['server_num']; $i++)
		{
			self::$sMemcache->addServer($MemcacheHosts['cacheserverlist_cf']['server_host'.$i],
										$MemcacheHosts['cacheserverlist_cf']['server_port'.$i],
										false, 1, 1, 15, true,
										array('CommonMemcache', 'failureCallback'));
		}
		return true;
	}

	public static function & getInstance()
	{
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}
		return self::$sMemcache;
	}

	public static function get($key, $flags=0)
	{
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}
		$val = self::$sMemcache->get(md5($key),$flags);

        // make CMEM support time out, added by jensen
        if( is_array($val) && isset( $val['expire'] ) !== false  ){
            if( $val['expire']['settime'] + $val['expire']['expires'] < time() ){
                return false;
            }
            else{
                return $val['value'];
            }
        }
        else{
            return false;
        }
	}

	public static function set($key, $val, $flag=0, $expires=0)
	{
		//echo "flag:{$flag}";
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}

        // make CMEM support time out
        $expire = array( 'settime' => time(), 'expires' => $expires );
        $val = array( 'expire' => $expire, 'value'=> $val );
		return self::$sMemcache->set(md5($key), $val, $flag, $expires);
	}

	public static function delete($key)
	{
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}
		return self::$sMemcache->delete(md5($key));
	}

	public static function increment($key, $val=1)
	{
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}
		return self::$sMemcache->increment(md5($key), $val);
	}

	public static function add($key, $val, $flag=0, $expire=0)
	{
		if(empty(self::$sMemcache) && !self::init())
		{
			return false;
		}
		return self::$sMemcache->add(md5($key), $val, $flag, $expire);
	}

	public static function failureCallback($host, $port) {
   		 error_log(date("Y-m-d H:i:s").": memcache '$host:$port' failed ! \n", 3, GENERAL_ERROR_DIR);
	}

}
