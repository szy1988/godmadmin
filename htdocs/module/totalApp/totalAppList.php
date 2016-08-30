<?php
/**
 * Created by PhpStorm.
 * User: v_zjzzhu
 * Date: 2015/11/04
 * Time: 14:22
 */

class totalAppList extends totalApp
{

    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function index()
    {
        $serviceType = ($this->m_params['serviceType']);

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
        $condition['iAppDevGroup'] = 1;
        $condition['serviceType'] = $serviceType;
        //统计total
        $tbAppInfo = $this->dao->tbAppInfo;
        $total = $tbAppInfo->getSumByCondition($condition);

        // 每页展示数据条数
        $pageSize = 12;
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
            if(stripos($v['sAppUrl'], '#')<=0){
                $v['sAppUrl'] = $urlPre.$v['sAppUrl'].$urlSign.'right_user='.$condition['sUserRtx'].'&iUin='.$v['iUin'].'&iPlatID='.$v['iPlatID'].'&iIsPlatToService='.$v['iIsPlatToService'].'&serviceType='.$serviceType.'&ticket='.$ticket.'&e_code=pc_godm';
            }else{
                $v['sAppUrl'] = $urlPre.$v['sAppUrl'];
            }
            // $v['sAppUrl'] = $urlPre.$v['sAppUrl'].$urlSign.'right_user='.$condition['sUserRtx'].'&iUin='.$v['iUin'].'&serviceType='.$serviceType.'&iPlatID='.$v['iPlatID'].'&iIsPlatToService='.$v['iIsPlatToService'].'&ticket='.$ticket.'&e_code=pc_godm';
            $v['sConnect'] = preg_replace('/(\([\x7f-\xff]+\))/','',$v['sConnectRTX']);
        }
        $appKind = $this->getAppKind();
        
        
        $this->tpl->assign('appKind',$appKind[1]);
    	$this->tpl->assign('putData',$putData);
        $this->tpl->assign('total',$total[0]['sum']);
        $this->tpl->assign('pageCur',$pageCur);
        $this->tpl->assign('pageSize',$pageSize);
        $this->tpl->display('totalApp.html');
    }

    function applyAuth(){
        $iLoginByQQ = $this->m_params['iLoginByQQ'];
        $iIsPlatToService = $this->m_params['iIsPlatToService'];
        $sRoleID = $this->m_params['sRoleID'];
        $right_user = $_SESSION["right_user"];
        $serviceType = $_SESSION['serviceType'];
        $X_TICKET = $_SESSION['X_TICKET'];
        $authDesc = $this->m_params['authDesc'];
        $iAppId = $this->m_params['iAppId'];
        $iUin = $this->m_params['iUin'];
        $PlatID = $this->m_params['PlatID'];
        $easyApply = $this->config->acl['easyApply'];
        $checkHasItem = $this->checkHasItem($iAppId);
        if(empty($right_user)){
            $this->tridentJson(1,'请登录',array());
        }

        if($iLoginByQQ == 0){
            $params = array('AuthRtx'=>$right_user,
                        'ServiceType'=>$serviceType,
                        'PlatID'=>$PlatID,
                        'RoleID'=>$sRoleID,
                        'AuthDesc'=>$authDesc,
                        // 'OATicket'=>$X_TICKET,
                        //'RoleType'=>'admin'
                        );

            if(in_array($right_user, $easyApply)){
                $authComplete = 1;
                $params = array_merge($params,array('RoleType'=>'admin'));
            }

            $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/commapi.php?action=CommAddAuth&ticket='.$X_TICKET,$params);
            $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);

            if($curlPostJson['ret'] > 0){
                $tbMyApp = $this->dao->tbMyApp;
                $iAuthState = ($curlPostJson['ret']==1)?2:1;
                $insertParams = array('sUserRtx'=>$right_user,
                    'iAppId'=>$iAppId,
                    'dtDate'=>date('Y-m-d H:i:s'),
                    'dtAuthEndDate'=>$curlPostJson['data']['dExpireDate'],
                    'iAuthState'=>$iAuthState,
                    'iUin'=>$iUin
                    );
                    if($iIsPlatToService == 1){
                        $insertParams['sAppService']=$serviceType;
                    }
                    if($checkHasItem){
                        $insertRes = $tbMyApp->updateColumnByIds(array('id',$checkHasItem),$insertParams);
                    }else{
                        $insertRes = $tbMyApp->insertColumn($insertParams);
                    }

                if($insertRes){
                    $this->tridentJson(0,'申请成功',array('dtAuthEndDate'=>$curlPostJson['dExpireDate'],'callBack'=>'afterSubmit','iAppId'=>$iAppId,'iAuthState'=>$iAuthState));
                }else{
                    $this->tridentJson(1,'插入数据库失败');
                }
            }else{
                if($curlPostJson['ret'] == -10){
                    $this->tridentJson(1,'十分抱歉，你的登录态失效，请重新登录！');
                }else{
                    $this->tridentJson(1,'权限已申请');
                }
            }
        }else{
            $tbMyApp = $this->dao->tbMyApp;
            $iAuthState = 2;
            $insertParams = array('sUserRtx'=>$right_user,
                'iAppId'=>$iAppId,
                'dtDate'=>date('Y-m-d H:i:s'),
                'dtAuthEndDate'=>'2100-01-01',
                'iAuthState'=>$iAuthState,
                'iUin'=>$iUin
                );
                if($iIsPlatToService == 1){
                    $insertParams['sAppService']=$serviceType;
                }
                if($checkHasItem){
                        $insertRes = $tbMyApp->updateColumnByIds(array('id',$checkHasItem),$insertParams);
                    }else{
                        $insertRes = $tbMyApp->insertColumn($insertParams);
                    }
            if($insertRes){
                $this->tridentJson(0,'申请成功',array('dtAuthEndDate'=>'9999-99-99','callBack'=>'afterSubmit','iAppId'=>$iAppId,'iAuthState'=>2));
            }else{
                $this->tridentJson(1,'插入数据库失败');
            }
        }


        

        
        
    }

    function applyAuthCheck(){
        $iLoginByQQ = $this->m_params['iLoginByQQ'];
        if($iLoginByQQ>0){
            $this->tridentJson(0,'可用',array('insert'=>0,'retNotice'=>0,'iLoginByQQ'=>$iLoginByQQ));
        }
        $iIsPlatToService = $this->m_params['iIsPlatToService'];
        $iPlatID = $this->m_params['PlatID'];
        $iAppId = $this->m_params['iAppId'];
        $serviceType = $_SESSION['serviceType'];
        $right_user = $_SESSION["right_user"];
        $checkAclUrl = 'http://auth.ied.com/api/getAuthInfo.php?action=getAuthList&user_name='.$right_user.'&platID='.$iPlatID.'&isPlatToService='.$iIsPlatToService.'&serviceType='.$serviceType;
        // echo $checkAclUrl;
        //var_dump(doCurlPost($checkAclUrl,array()));
        $curlGetRes = doCurlPost($checkAclUrl,array());
        $checkHasItem = $this->checkHasItem($iAppId);
        $curlGetJson = json_decode(substr($curlGetRes,stripos($curlGetRes, '=')+2),TRUE);
        // var_dump($curlGetJson);
        $tbMyApp = $this->dao->tbMyApp;
        $insertRes = '';
        if($curlGetJson['ret'] == 1){
            $iAuthState = 2;
            $insertParams = array(
                'sUserRtx'=>$right_user,
                'iAppId'=>$iAppId,
                'dtDate'=>date('Y-m-d H:i:s'),
                'dtAuthEndDate'=>$curlGetJson['data']['dExpireDate'],
                'iAuthState'=>2,   
                );
            if($iIsPlatToService == 1){
                $insertParams['sAppService']=$serviceType;
            }
            if($checkHasItem){
                $insertRes = $tbMyApp->updateColumnByIds(array('id',$checkHasItem),$insertParams);
            }else{
                $insertRes = $tbMyApp->insertColumn($insertParams);
            }
            
        }else if($curlGetJson['ret'] == -5){
            $iAuthState = 1;
            $insertParams = array(
                'sUserRtx'=>$right_user,
                'iAppId'=>$iAppId,
                'dtDate'=>date('Y-m-d H:i:s'),
                'iAuthState'=>1,   
                );
                if($iIsPlatToService == 1){
                    $insertParams['sAppService']=$serviceType;
                }
                if($checkHasItem){
                $insertRes = $tbMyApp->updateColumnByIds(array('id',$checkHasItem),$insertParams);
                }else{
                    $insertRes = $tbMyApp->insertColumn($insertParams);
                }
        }
        if($insertRes){
            $this->tridentJson(0,'可用',array('insert'=>1,'retNotice'=>0,'iAuthState'=>$iAuthState));
        }else{
            $this->tridentJson(0,'可用',array('insert'=>0,'retNotice'=>0));
        }
       
        
    }


    //用于修改应用组件工具选择开发组
    public function getGroupByCenterID(){
        $iDevCenterID = $this->m_params['iDevCenterID'];
        $info = $this->getGroupByCenter($iDevCenterID);
        $backHtml = '';
        foreach($info as $k => $v){
            $backHtml .= '<option value="'.$k.'">'.$v.'</option>';
        }
        echo $backHtml;
    }

    public function addUseTimes(){

        $needBack = $this->m_params['needBack'];
        
        $id = $this->m_params['id'];

        $tbMyApp = $this->dao->tbMyApp;
        $updateRes = $tbMyApp->updateUseTimeById(array('id',$id),array('iUseTimes'=>'iUseTimes+1'));
        if($updateRes && $needBack){
            $this->tridentJson(0,'成功',array('id'=>$id));
        }else{
            $this->tridentJson(1,'',array());
        }
    }
}