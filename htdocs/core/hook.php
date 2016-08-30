<?php

/*
 * 钩子父类
 */

/**
 * @author timmu  jaspersong
 */

class hook {

    public function __construct() {

    }
    /**
     * 载入各种配置项
     * @author timmu
     * @since 2015年9月2日 16:48:21
     */
    public function loadConfig()
    {
        $this->config = config::getInstance();
    }

    /**
     * 载入模版类
     * @author timmu
     * @since 2015年9月4日 17:36:48
     */
    public function loadTpl()
    {
        $this->tpl = tpl::getInstance();
    }

    public function loadDb(){
        require_once DIR_PATH.'/dao/base/DAO.php';
        require_once DIR_PATH.'/dao/base/Abstract.dao.php';
        $this->dao = getDao::getInstance();
    }

    public function loadOssLog()
    {
        $this->OssLog = OssLog::getInstance();
    }

    /**
     * 按需载入系统支持类库
     * $loadFunction存放类名与载入类实例方法的对应map
     * @param  string $param 系统支撑类对象
     * @return object
     * @author timmu
     * @since 2015年9月2日 17:38:14
     */
    public function __get($param)
    {
        $loadFunction = array('config'=>'loadConfig','tpl'=>'loadTpl','dao'=>'loadDb','OssLog'=>'loadOssLog');

        if(isset($loadFunction[$param])){
            $this->$loadFunction[$param]();
        }
        return $this->$param;
    }
    protected function tridentJson( $iRetcode, $sErrorMsg, $vmResult=array())
    {
        $res = array(
            'retCode' => $iRetcode,
            'retInfo' => $sErrorMsg,
        );
        foreach($vmResult as $k=>$v){
            $res[$k]=$v;
        }
        echo json_encode($res);
        
        exit;
    }
}

?>
