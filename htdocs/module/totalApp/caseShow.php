<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2016/02/29
 */

class caseShow extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $right_user = $_SESSION["right_user"];
        $condition['iCaseKindID'] = $this->m_params['iCaseKindID'];
        $condition['sDisplayService'] = $_SESSION['serviceType'];
        
        $tbCaseInfo = $this->dao->tbCaseInfo;

        //查询点赞信息
        $hasZanKinds = $tbCaseInfo->getZanKind($right_user);
        // 每页展示数据条数
        $pageSize = 8;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;
        $condition['iState'] = 1;
        $myApps = $tbCaseInfo->getListByCondition($condition);
        if($myApps){
            foreach($myApps as &$v){
                $v['sConnect'] = preg_replace('/(\([\x7f-\xff]+\))/','',$v['sConnectRTX']);
                if(stripos($v['sKMUrl'], '://')<=0){
                $v['sKMUrl'] = 'http://'.$v['sKMUrl'];
                }

                $v['hasZan'] = in_array($v['iCaseKindID'], $hasZanKinds)?1:0;
            }
            $putData = $myApps;
        }
        
        $condition['sum'] = 1;
        $total = $tbCaseInfo->getListByCondition($condition);

        $tbAppInfo = $this->dao->tbAppInfo;
        $appInfo = $tbAppInfo -> getListByConditionTable();
        foreach($appInfo as $item){
            $appMap[$item['iAppId']] = $item['sAppName'];
        }
        $appKind = $this->getAppKind();
        $this->tpl->assign('appKind',$appKind[4]);

        
        $this->tpl->assign('putData',$putData);
        $this->tpl->assign('appMap',$appMap);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('caseShow.html');
    }

    public function zan(){
        
        $right_user = $_SESSION["right_user"];
        $condition['iCaseId'] = $this->m_params['iCaseId'];
        $cookieName = 'zan'.$condition['iCaseId'];
        $tbCaseInfo = $this->dao->tbCaseInfo;

        $caseinfo = $tbCaseInfo->getActInfoById(array('iCaseId',$condition['iCaseId']));

        $iCaseKindID = $caseinfo[0]['iCaseKindID'];

        $zanres = $tbCaseInfo->getZanInfo($right_user,$iCaseKindID);
        // var_dump();
        if($zanres){
            $this->tridentJson(1,'1',array('callBack'=>"afterZan"));
        }
        
        
        $res = $tbCaseInfo->zan($condition['iCaseId'],$right_user,$iCaseKindID);

        if($res){

            $this->tridentJson(0,'点赞成功',array('callBack'=>"afterZan",'iCaseId'=>$condition['iCaseId'],'iCaseKindID'=>$iCaseKindID));
            
            
        }else{
            $this->tridentJson(1,'点赞失败',array('callBack'=>"afterZan"));
        }
    }
}