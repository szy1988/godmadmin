<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class appList extends myApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
    	
    	$right_user = $_SESSION["right_user"];
        $ticket = $_SESSION["X_TICKET"];
    	//参数组装

        $condition['sUserRtx'] = $right_user;
        $condition['iCommonUse'] = 1;
        $sort_kind = $this->m_params['sort_kind'];
        if($sort_kind == 2){
            $condition['order'] =  'ORDER BY a.iUseTimes DESC';
        }
        $iAppGroup = $this->m_params['iAppGroup'];
        $condition['iAppGroup'] = $iAppGroup;
        $serviceType = ($this->m_params['serviceType']);
        $condition['serviceType'] = $serviceType;
        //统计total
        $tbMyApp = $this->dao->tbMyApp;
        $total = $tbMyApp->getAppListSumByCondition($condition);
        

        // 每页展示数据条数
        $pageSize = 11;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myApps = $tbMyApp->getAppListByCondition($condition);
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
            if(stripos($v['sAppUrl'], '#')<=0){
                $v['sAppUrl'] = $urlPre.$v['sAppUrl'].$urlSign.'right_user='.$right_user.'&iUin='.$v['iUin'].'&iPlatID='.$v['iPlatID'].'&iIsPlatToService='.$v['iIsPlatToService'].'&serviceType='.$serviceType.'&ticket='.$ticket.'&e_code=pc_godm';
            }else{
                $v['sAppUrl'] = $urlPre.$v['sAppUrl'];
            }
            
        }
        $appKind = $this->getAppKind();
        $this->tpl->assign('appKind',$appKind[1]);
        $this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('myApp.html');
    }

    //收藏
    public function collectMyApp(){
        $serviceType = $_SESSION['serviceType'];
        $right_user = $_SESSION["right_user"];
        $iAppId = $this->m_params['iAppId'];

        $tbMyApp = $this->dao->tbMyApp;

        $queryRes = $tbMyApp->getAppListByCondition(array('sUserRtx'=>$right_user,'iAppId'=>$iAppId,'serviceType'=>$serviceType));
        if($queryRes){
            $iCommonUse = ($queryRes[0]['iCommonUse'] == 0)?1:0;

            $updateRes = $tbMyApp->updateColumnByIds(array('id',$queryRes[0]['id']),array('iCommonUse'=>$iCommonUse));
            if($updateRes){
                $this->tridentJson(0,'申请成功',array('callBack'=>'afterCollect','iCommonUse'=>$iCommonUse,'iAppId'=>$iAppId));
            }else{
                $this->tridentJson(1,'修改数据库失败');
            }

        }else{
            $tbAppInfo = $this->dao->tbAppInfo;
            $appInfo = $tbAppInfo->getListByConditionTable(array('iAppId'=>$iAppId));
            $sAppService = ($appInfo[0]['iIsPlatToService'] == 1)?$serviceType:'';
            $insertParams = array('sUserRtx'=>$right_user,
            'iAppId'=>$iAppId,
            'dtDate'=>date('Y-m-d H:i:s'),
            'iCommonUse'=>1,
            'iIsCgi'=>0,
            'sAppService'=>$sAppService,
            'iAuthState'=>0,
            );
            $insertRes = $tbMyApp->insertColumn($insertParams);
            if($insertRes){
            $this->tridentJson(0,'申请成功',array('callBack'=>'afterCollect','iCommonUse'=>1,'iAppId'=>$iAppId));
            }else{
                $this->tridentJson(1,'插入数据库失败');
            }
        }
    }
}