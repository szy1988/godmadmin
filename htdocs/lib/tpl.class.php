<?php
/**
 * 初始化Smarty模版类
 * @author timmu
 * @since 2015年9月2日 15:10:41
 */
class tpl{

	private static $_instance;

	public static function getInstance(){
		if(!(self::$_instance instanceof Smarty)){
			self::$_instance = new Smarty;
		    self::$_instance->template_dir  = ROOT_PATH . '/tpl';
		    self::$_instance->compile_dir = TMP_PATH . '/tpl_c';
		    self::$_instance->cache_dir = TMP_PATH . '/cache';
		    //下放所有定义的静态变量
		    self::$_instance->assign( 'constant', get_defined_constants() );
		}
		return self::$_instance;
	}

}


?>