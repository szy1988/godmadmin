<?php
 class totalApp extends AbstractAction {
	public function __construct($params) {
		parent::__construct($params);
	}

    public function index(){}

        //检查某条应用是否已经有个记录
    function checkHasItem($iAppId,$iAppDevGroup=1){
        $right_user = $_SESSION["right_user"];
        $serviceType = $_SESSION['serviceType'];

        $tbMyApp = $this->dao->tbMyApp;
        $queryRes = $tbMyApp->getAppListByCondition(array('sUserRtx'=>$right_user,'iAppId'=>$iAppId,'serviceType'=>$serviceType,'iAppDevGroup'=>$iAppDevGroup));
        if($queryRes){
            return $queryRes[0]['id'];
        }else{
            return false;
        }
    }

    public function sendRTXWhenUDCase($service,$MsgInfo){
        $caseCheck = $this->config->acl['caseCheck'];
        if(array_key_exists($service, $caseCheck)){
            $send = $caseCheck[$service];
        }else{
            $send = $caseCheck['default'];
        }

        $param = array (
                    "Title"=>"营销手册通知",
                    "Sender" => $_SESSION['right_user'],
                    'MsgInfo' => $MsgInfo,
                    'Receiver' => $send
                );

        $qc = $this->qc;
        $ret = $qc->SendRTX($param);
    }
}
?>