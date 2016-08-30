<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class totalDevAppList extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $sort_kind = $this->m_params['sort_kind'];
        if($sort_kind == 2){
            $condition['order'] =  'ORDER BY iCommonUse DESC,iUseTimes DESC';
        }
        
        $iAppGroup = $this->m_params['iAppGroup'];
        if($_SESSION["right_user"]){
            $condition['sUserRtx'] = $_SESSION["right_user"];
            $ticket = $_SESSION["X_TICKET"];
        }
        $condition['iAppGroup'] = $iAppGroup;

        $condition['iState'] = 1;
        $condition['iAppDevGroup'] = 2;
        // $condition['serviceType'] = $_SESSION['serviceType'];
        //统计total
        $tbAppInfo = $this->dao->tbAppInfo;
        // $condition['isDifferentService'] = 1;
        $total = $tbAppInfo->getSumByCondition($condition);
        
        // 每页展示数据条数
        $pageSize = 9;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myApps = $tbAppInfo->getListByCondition($condition);

    	$putData = array();
    	if($myApps){
    		$putData = $myApps;
    	}
        foreach($putData as &$v){
            $v['sRoleIDList'] = (unserialize(htmlspecialchars_decode($v['sRoleIDList'])));
            if(stripos($v['sAppUrl'], '?')>0){
                $urlSign = '&';
            }else{
                $urlSign = '?';
            }
            $urlPre = '';
            if(stripos($v['sAppUrl'], '://')<=0){
                $urlPre = 'http://';
            }
            $v['sAppUrl'] = $urlPre.$v['sAppUrl'].$urlSign.'right_user='.$condition['sUserRtx'].'&iUin='.$v['iUin'].'&iPlatID='.$v['iPlatID'].'&iIsPlatToService='.$v['iIsPlatToService'].'&ticket='.$ticket.'&e_code=godm';
        }
        $appKind = $this->getAppKind();
        $this->tpl->assign('appKind',$appKind[2]);
    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/totalDevApp.html');
    }

    function applyAuth(){
        $iLoginByQQ = $this->m_params['iLoginByQQ'];
        $right_user = $_SESSION["right_user"];
        $iAppId = $this->m_params['iAppId'];
        $iUin = $this->m_params['iUin'];
        $checkHasItem = $this->checkHasItem($iAppId,2);

        if(empty($right_user)){
            $this->tridentJson(1,'请登录',array());
        }

        $tbMyApp = $this->dao->tbMyApp;
        $iAuthState = 2;
        $insertParams = array('sUserRtx'=>$right_user,
            'iAppId'=>$iAppId,
            'dtDate'=>date('Y-m-d H:i:s'),
            'dtAuthEndDate'=>'2100-01-01',
            'iAuthState'=>$iAuthState,
            'iUin'=>$iUin
            );
            if($checkHasItem){
                    $insertRes = $tbMyApp->updateColumnByIds(array('id',$checkHasItem),$insertParams);
                }else{
                    $insertRes = $tbMyApp->insertColumn($insertParams);
                }
        if($insertRes){
            $this->tridentJson(0,'申请成功',array('dtAuthEndDate'=>'2100-01-01','callBack'=>'afterSubmit','iAppId'=>$iAppId,'iAuthState'=>2));
        }else{
            $this->tridentJson(1,'插入数据库失败');
        }
         
    }

}