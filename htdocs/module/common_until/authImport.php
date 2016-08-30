<?php
/**
 * 从txt文本中批量导入权限，txt文件格式：RTX,serviceType,platId,RoleId
 * auther:jaspersong
 */

class authImport extends common_until
{

    public function __construct($params)
    {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index()
    {
        $authFile = "http://x.ied.com/static/eas.txt";
        $authContent = trim(file_get_contents( $authFile ));
        $authContent = explode("\n", $authContent);

        foreach($authContent as $row){
            $contentTmp = array();
            if( !empty($row) ){
                list( $contentTmp['AuthRtx'], $contentTmp['ServiceType'], $contentTmp['PlatID'], $contentTmp['RoleID'] ) = explode(',', $row);
                $this->applyAuthAll($contentTmp);
                sleep(1);
            }
        }

    }



     function applyAuthAll($authParams){
        $sRoleID = $authParams['RoleID'];
        $right_user = $authParams['AuthRtx'];
        $serviceType = $authParams['ServiceType'];
        $authDesc = '权限申请';
        $PlatID = $authParams['PlatID'];

        $params = array(
            'AuthRtx'=>$right_user,
            'ServiceType'=>$serviceType,
            'PlatID'=>$PlatID,
            'RoleID'=>$sRoleID,
            'AuthDesc'=>$authDesc,
            'RoleType'=>'admin',  //不需要审核
            'NotifyType'=>'quite',//不进行消息提示
        );
        $ticket = $_SESSION['X_TICKET'];
        $curlPostRes = $this->doCurlPost('http://auth.ied.com/api/commapi.php?action=CommAddAuth&ticket='.$ticket,$params);
        $curlPostJson = json_decode(substr($curlPostRes,stripos($curlPostRes, '=')+2),TRUE);
        if($curlPostJson['ret'] > 0){           
            echo '用戶'.$right_user.'已开通平台'.$PlatID.'业务'.$serviceType."的权限\n";
        }else{
            echo '用戶'.$right_user.'权限提示：'.$curlPostJson['msg']."\n";
        }
        
    }

}