<?php
/**
 * ��ʼ��Smartyģ����
 * @author timmu
 * @since 2015��9��2�� 15:10:41
 */
class tpl{

	private static $_instance;

	public static function getInstance(){
		if(!(self::$_instance instanceof Smarty)){
			self::$_instance = new Smarty;
		    self::$_instance->template_dir  = ROOT_PATH . '/tpl';
		    self::$_instance->compile_dir = TMP_PATH . '/tpl_c';
		    self::$_instance->cache_dir = TMP_PATH . '/cache';
		    //�·����ж���ľ�̬����
		    self::$_instance->assign( 'constant', get_defined_constants() );
		}
		return self::$_instance;
	}

}


?>