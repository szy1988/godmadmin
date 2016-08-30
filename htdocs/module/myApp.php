<?php
 class myApp extends AbstractAction {
	public function __construct($params) {
		parent::__construct($params);
	}

    public function index(){}


    // public function getDevGroup(){
    //     $tbDevGroup = $this->dao->tbDevGroup;
    //     $res = $tbDevGroup->getActInfoById();
    //     $data = array();
    //     foreach($res as $k=>$v){
    //         $data[$v['id']] = $v['sGroupName'];
    //     }
    //     return $data;
    // }

}
?>