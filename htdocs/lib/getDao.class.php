<?php
/**
 * 获取dao类
 * 1.获取文件配置
 * -----------------------
 * 文件配置目录/config/
 * dao.php
 * 如果dao类直接放在dao/根目录下则不需要进行配置，如果放在某个文件夹类则需要配置，如放在dao/stat/下，则需要如下配置
 * 配置文件书写格式如下：
 * <?php
 * $dao['tbResourceQuota'] = 'stat/';
 * ?>
 * @author timmu
 * @since 2015-09-02
 */
class getDao{

	public $dao;

	private static $_instance;

	public function __construct(){

	}

	/**
	 * 单例dao类
	 * @author timmu
	 * @since  2015-09-21
	 */
	public static function getInstance(){
		if(!self::$_instance instanceof self){
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/*
	 * @return
     * @author timmu
     * @since 2015-09-02
	 */
	public function __get($param)
	{
		if(!isset($this->dao[$param])){
			$config = config::getInstance();
			$daoDir = $config->dao[$param];
			if(empty($daoDir)){
				$daoDir ='';
			}
			$filePath = DIR_PATH.'/dao/'.$daoDir.$param.'.dao.php';
			if(is_file($filePath)){
				include_once($filePath);
				$this->dao[$param] = new $param;
			}else{

				$this->dao[$param] = array();
			}
		}

		return $this->dao[$param];
	}
}


?>