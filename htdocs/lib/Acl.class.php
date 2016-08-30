<?php
/**
 * 公共权限验证类
 * 精确到某个控制器，某个方法
 * @author v_zjzzhu
 */

class Acl
{

    private static $aclInStance;

    private $aclArray;

    private $aclRtx;

    /**
     * 构造方法
     * @param $acl_cfg 权限配置文件完整路径
     * 格式为acl.cfg
     */
    private function __construct($acl_cfg)
    {
        //生成配置数组
        $this->aclArray = $acl_cfg;
    }

    /**
     * 生成唯一的实例
     * @param $acl_cfg
     * @return Acl
     */
    public static function getInstance($acl_cfg)
    {
        if (! (self::$aclInStance instanceof self)) {
            self::$aclInStance = new self($acl_cfg);
        }
        return self::$aclInStance;
    }

    /**
     * 进行权限验证
     * @param $rtx
     * @param string $ctrl_name
     * @param string $action_name
     * @return bool
     */
    public function CheckUserAcl($rtx, $ctrl_name = '', $action_name = '', $func = '')
    {


        //如果开关没有打开，不进行验证
        if ( (int)$this->aclArray['acl_lock']['open'] != 1) return true;

        //如果没有用户，直接返回false
        if ( !$rtx ) return false;

        $this->aclRtx = $rtx;

        //获得用户的模块
        $auth = $this->getUserModule($rtx);

        if($auth['ret'] == 1){
            //获得有权限的rtx组
            $aclList = $this->checkAcl($ctrl_name, $action_name, $func);

            if ( $rtxList ===false) return false;
            
            if(stristr( $auth['data']['modules'],$aclList)!== false){
                setcookie('auth_modules',$auth['data']['modules'],time()+86400);
                return true;
            }else{
                return false;
            }
             
        }else{
            return false;
        }
    }


    /**
     * 获得当前rtx的权限组
     */
    // public function getAclGroup($rtx = '')
    // {
    //     $rtxName = ($rtx != '') ? $rtx : $this->aclRtx;

    //     $aclGroup = '';
    //     foreach ($this->aclArray['cfg'] as $k => $v) {
    //         if (in_array($rtxName, $v)) {
    //             $aclGroup = $k;
    //             break;
    //         }
    //     }
    //     return $aclGroup;
    // }


    //获得用户的模块
    function getUserModule($rtx){
        //默认业务为lol

        $serviceType = $_SESSION['serviceType'];
        
        
        $rtxName = ($rtx != '') ? $rtx : $this->aclRtx;
        $checkAclUrl = 'http://auth.ied.com/api/getAuthInfo.php?action=getAuthList&user_name='.$rtxName.'&platID=55&isPlatToService=1&serviceType='.$serviceType;
        //var_dump(doCurlPost($checkAclUrl,array()));
        //$curlGetRes = doCurlPost($checkAclUrl,array());

        //$curlGetJson = json_decode(substr($curlGetRes,stripos($curlGetRes, '=')+2),TRUE);
        //return $curlGetJson;
        return true;
        // if($curlGetJson['ret'] === 1){
        //     return $curlGetJson['data'];
        // }else{
        //     return false;
        // }

    }

    /**
     * 获得系统功能模块
     * @param $ctrl_name
     * @param $action_name
     * @return array|bool
     */
    private function checkAcl($ctrl_name, $action_name, $func_name)
    {
        //获得权限组
        $aclList = $this->aclArray[$ctrl_name][$action_name][$func_name];
        if (!$aclList) return false;
        return $aclList;
    }
}