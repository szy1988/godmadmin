<?php
/**
 * 针对移动端用户进行的权限申请做处理，调用智维接口进行权限申请，同时更新状态
 * @author jaspersong
 * Date: 2016/04/21
 */
//ini_set( 'display_errors', 'on');
// error_reporting( E_ERROR );
//error_reporting(E_ALL);
class userAuthApply extends totalApp {

    public function __construct($params){

        parent::__construct($params);
    }

    public function index(){
        $right_user = $_SESSION["right_user"];
        $easyApply = $this->config->acl['easyApply'];

        if(in_array($right_user, $easyApply)){
            $isAdmin = 1;
        }else{
            $isAdmin = 0;
        }
        $sUserRtx = $this->m_params['sUserRtx'];

        // 每页展示数据条数
        $pageSize = 10;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;
        $condition['iStatus'] = 0;
        if($sUserRtx){
            $condition['sUserRtx'] = $sUserRtx;
        }
        $select = ' * ';

        $tbAuthList = $this->dao->tbAuthList;
        $totalAuthList = $tbAuthList->getAuthList($condition, $select);

        $this->tpl->assign('putData',$totalAuthList['data']);
        $this->tpl->assign('total',$totalAuthList['total']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->assign('isAdmin',$isAdmin);
        $this->tpl->display('authApplyList.html');

    }

    function applyAuthNew(){
        $sRoleID = $this->m_params['iRoleID'];
        $sUserRtx = $this->m_params["sUserRtx"];
        $serviceType = $this->m_params['sAppService'];
        $X_TICKET = $_SESSION['X_TICKET'];
        $PlatID = $this->m_params['iPlatID'];
        $easyApply = $this->config->acl['easyApply'];
        $iId = $this->m_params['iId'];

        $right_user = $_SESSION["right_user"];
        if(empty($right_user)){
            $this->tridentJson(1,'请登录',array('url'=>'self'));
        }
        $params = array(
            'AuthRtx'=>$sUserRtx,
            'ServiceType'=>$serviceType,
            'PlatID'=>$PlatID,
            'RoleID'=>$sRoleID,
            'AuthDesc'=>'权限申请',
        );
        if(in_array($sUserRtx, $easyApply)){
            $params = array_merge($params,array('RoleType'=>'admin'));
        }
        //先进行权限查询
        $iIsPlatToService = $this->m_params['iIsPlatToService'];
        $checkAclUrl = 'http://auth.ied.com/api/getAuthInfo.php?action=getAuthList&user_name='.$sUserRtx.'&platID='.$PlatID.'&isPlatToService='.$iIsPlatToService.'&serviceType='.$serviceType;
        $curlGetRet = $this->doCurlPost($checkAclUrl,array());
        $curlGetJsonNew = json_decode(substr($curlGetRet,stripos($curlGetRet, '=')+2),TRUE);
        
        $flag = false;
        if($curlGetJsonNew['ret'] == 1){
            $flag = true;
        }else{
            $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/commapi.php?action=CommAddAuth&ticket='.$X_TICKET,$params);
            $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
            if($curlPostJson['ret'] > 0){ 
               $flag = true; 
            }else{
                if($curlPostJson['ret'] == -10){
                    $this->tridentJson(1,'十分抱歉，你的登录态失效，请重新登录！',array('url'=>'self'));
                }else{
                    $flag = true;
                }
            }
        }
        if($flag){
            $tbAuthList = $this->dao->tbAuthList;
            $insertParams = array(
                'iStatus'=>1,
            );
            $insertRes = $tbAuthList->updateColumnByIds(array('iId',$iId),$insertParams);
            if($insertRes){
                $this->tridentJson(0,'申请成功',array('url'=>'self'));
            }else{
                $this->tridentJson(1,'更新数据库失败');
            }
        }
        
    }
}

?>
