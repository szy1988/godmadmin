<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class cgiList extends myApp
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

        // $sort_kind = $this->m_params['sort_kind'];
        // if($sort_kind == 2){
        //     $condition['order'] =  'ORDER BY iCommonUse DESC,iUseTimes DESC';
        // }
        
        $serviceType = ($this->m_params['serviceType']);
        $condition['serviceType'] = $serviceType;
        $condition['iAuthState'] = 2;
        $condition['iState'] = 1;
        
        //统计total
        $tbMyApp = $this->dao->tbMyApp;

        //后期业务多了之后再加
        $total = $tbMyApp->getCgiListSumByCondition($condition);

        // 每页展示数据条数
        $pageSize = 11;
        // 当前是第几页
        $pageCur = intval($this->m_params['page']) <= 0 ? 1 : $this->m_params['page'];
        $condition['limit'] = $pageSize;
        $condition['start'] = ($pageCur - 1) * $pageSize;

    	$myCgis = $tbMyApp->getCgiListByCondition($condition);
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
        $this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('dev/myCgiList.html');
    }

    public function goOtherPlat(){
        $url = $this->m_params['url'];
        
        $id = $this->m_params['id'];
        $tbMyApp = $this->dao->tbMyApp;
        $updateRes = $tbMyApp->updateUseTimeById(array('id',$id),array('iUseTimes'=>'iUseTimes+1'));
        if($updateRes){
            $this->tridentJson(0,'成功',array('id'=>$id));
        }else{
            $this->tridentJson(1,'失败',array());
        }
    }

   
}