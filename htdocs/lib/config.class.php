<?php
/**
 * 获取配置类
 * 1.获取文件配置
 * 2.设置，获取零时配置
 * -----------------------
 * 文件配置目录/config/
 * 文件名为配置项.php
 * 文件内容为一个以配置项为名称的数据
 * 如：新建一个配置项system
 * 则需要在/config/文件夹下新建system.php文件
 * 文件中书写如下：
 * <?php
 * $system['systemName'] = 'GPM系统';
 * $system['serviceIp'] = '127.0.0.1';
 * $system['allService'] = array('cf'=>'穿越火线','lol'=>'英雄联盟');
 * ?>
 * @author timmu
 * @since 2015年9月2日 15:10:41
 */
class config{

	public $config;		//已载入的文件配置数据
	public $tmpConfig;	//存储零时配置信息
	private static $_instance;

	public function __construct(){

	}

	/**
	 * 单例调用
	 * @author timmu
	 * @since  2015年9月21日 15:29:28
	 */
	public static function getInstance(){
		if(!self::$_instance instanceof self){
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * 获取配置文件配置值
	 * @param  string $param 配置key
	 * @return 
     * @author timmu
     * @since 2015年9月2日 17:19:44
	 */
	public function __get($param)
	{
		if(!isset($this->config[$param])){
			$filePath = DIR_PATH.'/config/'.$param.'.php';
			if(is_file($filePath)){
				include_once($filePath);
				$this->config[$param] = $$param;
			}else{
				$this->config[$param] = array();
			}
		}

		return $this->config[$param];
	}

	/**
	 * 设置零时配置，并不影响全局配置
	 * @param string $param 配置key
	 * @param object $value 配置值
	 * @return boolean
     * @author timmu
     * @since 2015年9月2日 17:19:44
	 */
	public function set($param,$value){
		return $this->tmpConfig[$param] = $value;
	}

	/**
	 * 获取零时配置，
	 * @param string $param 配置key
	 * @return array
     * @author timmu
     * @since 2015年9月2日 17:19:44
	 */
	public function get($param){
		return $this->tmpConfig[$param];
	}
}


?>