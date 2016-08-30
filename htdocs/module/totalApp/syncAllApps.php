<?php
/**
 * 同步申请通过的外部应用
 * 在审核通过后执行该脚本
 * @author jaspersong
 * Date: 2016/03/31
 */

class syncAllApps extends totalApp {

    public function __construct($params){
        parent::__construct($params);
    }
    public function index(){
        //$serviceType = cleanInput($this->m_params['serviceType']);  //所有業務的應用全部同步
        $publishType = cleanInput($this->m_params['publishType']);//0:测试发布,1:正式发布
        $publishType = isset($publishType)?intval($publishType):0;
        $curlType = cleanInput($this->m_params['curlType']);//0:定時任務,1:接口調用
        $curlType = isset($curlType)?intval($curlType):0;


        if (in_array($publishType, array(0,1) === false)) {
            if ($curlType == 1) {
                $this->tridentJson( -1, '发布参数错误');
            }else{
                echo '发布参数错误!</br>';
            }           
            exit;
        }

        //统计total
        $tbAppInfo = $this->dao->tbAppInfo;
        $condition = array();
        $condition['iState'] = 1;
        $condition['iAppDevGroup'] = 1;
        //$condition['serviceType'] = $serviceType;
        $select = "iAppId,sAppName,sAppLogoTxt,sAppUrl,sAppMobileUrl,iLogoColorIndex,iPlatID,iIsPlatToService,sAppOnlyService,iLoginByQQ,iSupportMobile,sRoleIDList";
        $totalApps = $tbAppInfo->getListByConditionTable($condition, $select);
        //数据处理
        $jsDataArr = array();
        for ($i=0; $i < count($totalApps); $i++) { 
            $totalApps[$i]['sRoleIDList'] = (unserialize(htmlspecialchars_decode($totalApps[$i]['sRoleIDList'])));
            $jsDataArr[$totalApps[$i]['iAppId']] = $totalApps[$i];
        }
        if( CUR_ENV_IP == TEST_IP ){
            $jsfilePath = './log/godmAllApps.js';
        }else{
            if (!file_exists( CFG_SAVE_PATH.'/appsweb/godm/' )) {
                if (!mkdir(CFG_SAVE_PATH.'/appsweb/godm/', 0777, true)) {
                    if ($curlType == 1) {
                        $this->tridentJson( -1, '新建目录失败');
                    }else{
                        echo '新建目录失败!</br>';
                    } 
                    exit;
                }
            }
            $jsfilePath = CFG_SAVE_PATH.'/appsweb/godm/godmAllApps.js';
            chmod(CFG_SAVE_PATH.'/appsweb/godm/', 0777);
            chmod($jsfilePath,0777);
        }

        $publishJsData = "window['godmAllApps'] = ".json_encode($jsDataArr);
        $fp = fopen($jsfilePath, 'w');
        if(!fwrite($fp, $publishJsData))
        {
            fclose($fp);
            if ($curlType == 1) {
                $this->tridentJson( -1, 'appsweb-js文件写错误');
            }else{
                echo '营销手册我的应用在'.$serviceType.'下appsweb-js文件写错误!</br>';
            } 
            exit;
        }
        fclose($fp);
        //发布文件
        if( CUR_ENV_IP==OP_IP ){
            $jsPublishPath =  IMG_PUBLISH_PATH.'/allapps/godmAllApps.js';
            if ($publishType ==1){
                $ret = $this->sync("ossweb-img",$jsfilePath, $jsPublishPath );
            }else {
                $ret = $this->syncTest("ossweb-img",$jsfilePath, $jsPublishPath );
            }
            if( !$ret ){
                if ($curlType == 1) {
                    $this->tridentJson( -1, '发布文件失败');
                }else{
                    echo '营销手册我的应用发布文件失败!</br>';
                } 
                exit;;
            }
        }
        if ( $publishType ==1){
            if ($curlType == 1) {
                $this->tridentJson( 0, '发布到正式环境成功');
            }else{
                echo '营销手册我的应用发布到正式环境成功!</br>';
            }
        }else {
            if ($curlType == 1) {
                $this->tridentJson( 0, '发布到测试环境成功');
            }else{
                echo '营销手册我的应用发布到测试环境成功!</br>';
            }
        }

    }
}

?>
