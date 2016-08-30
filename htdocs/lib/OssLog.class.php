<?php
 

class OssLog {
	public static $path = "";
	public static $_instance = NULL;
	
	public function __construct() {						
	}
	
	public static function getInstance() {
//		if (!(self::$_instance instanceof Logger)) {
//			self::$_instance = new Logger;
//			self::$path  = ROOT_PATH  .'/log/';
//			self::$_instance->initLogger (DATE_FILE_LOGGER, self::$path ,'', 1024*1024*10, 10);
//		}
//		return self::$_instance;

        return new OssLog();
	}

    /**
     * 先覆盖掉原来的写日志方法
     * @param $file
     * @param $line
     * @param $LP_LEVEL
     * @param $msg
     */
    public function writeLog($file, $line, $LP_LEVEL, $msg)
    {

    }
	//调用方式$this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, $message)
}
?>
