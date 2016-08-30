<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class totalCgiList extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        
        // $iAppGroup = $this->m_params['iAppGroup'];
        if($_SESSION["right_user"]){
            $condition['sUserRtx'] = $_SESSION["right_user"];
            $ticket = $_SESSION["X_TICKET"];
        }
        // $condition['iAppGroup'] = $iAppGroup;

        $condition['iState'] = 1;
        // $condition['iAppDevGroup'] = 2;
        //统计total
        $iCgiGroup = $this->m_params['iCgiGroup'];
        $condition['iCgiGroup'] = $iCgiGroup;

        $tbCgiInfo = $this->dao->tbCgiInfo;
        $total = $tbCgiInfo->getSumByCondition($condition);

        // 每页展示数据条数
        $pageSize = 12;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myCgis = $tbCgiInfo->getListByCondition($condition);

    	$putData = array();
    	if($myCgis){
    		$putData = $myCgis;
    	}
        foreach($putData as &$v){
            if(stripos($v['sCgiUrl'], '://')<=0){
                $v['sCgiUrl'] = 'http://'.$v['sCgiUrl'];
            }
            if(stripos($v['sCgiContentUrl'], '://')<=0){
                $v['sCgiContentUrl'] = 'http://'.$v['sCgiContentUrl'];
            }


            $v['sConnect'] = preg_replace('/(\([\x7f-\xff]+\))/','',$v['sConnectRTX']);
        }
        $appKind = $this->getAppKind();
        $this->tpl->assign('appKind',$appKind[3]);
    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/totalCgiList.html');
    }

    function applyAuth(){
        $right_user = $_SESSION["right_user"];
        $iCgiId = $this->m_params['iCgiId'];
        $id = $this->m_params['id'];

        $tbMyApp = $this->dao->tbMyApp;

        $queryRes = $tbMyApp->getActInfoById(array('id',$id));
        if($queryRes){
            $iCommonUse = ($queryRes[0]['iCommonUse'] == 0)?1:0;

            $updateRes = $tbMyApp->updateColumnByIds(array('id',$id),array('iCommonUse'=>$iCommonUse));
            if($updateRes){
                $this->tridentJson(0,'申请成功',array('callBack'=>'afterSubmit','iCommonUse'=>$iCommonUse,'iCgiId'=>$iCgiId));
            }else{
                $this->tridentJson(1,'修改数据库失败');
            }

        }else{
            $insertParams = array('sUserRtx'=>$right_user,
            'iAppId'=>$iCgiId,
            'dtDate'=>date('Y-m-d H:i:s'),
            'iCommonUse'=>1,
            'iIsCgi'=>1,
            );
            $insertRes = $tbMyApp->insertColumn($insertParams);
            if($insertRes){
            $this->tridentJson(0,'申请成功',array('callBack'=>'afterSubmit','iCommonUse'=>1,'iCgiId'=>$iCgiId));
            }else{
                $this->tridentJson(1,'插入数据库失败');
            }
        }
        
    }
}