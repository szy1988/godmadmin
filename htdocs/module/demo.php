<?php



 class demo extends AbstractAction {
	public function __construct($params) {
		$this->m_params = $params;
		parent::__construct($params);
	}

    public function index(){}

    public function outputJson( $iRetcode, $sErrorMsg, $vmResult=array(),$jsonp='')
    {
        $res = array(
            'retCode' => $iRetcode,
            'retInfo' => $sErrorMsg
        );
        $res = array_merge($res,$vmResult);
        if ( $jsonp ){
            echo "var $jsonp = ".json_encode($res);
        }else {
        	echo json_encode($res);
        }
        exit;
    }


}