<?php
class tbServiceMap extends AbstractDao {

    protected $m_tableName = 'tbServiceMap';

    public function __construct()
    {
        parent::__construct('DB');
    }

    public function updateService($param = array()){
    	$deletesql = "delete from {$this->m_tableName}";
    	// echo $deletesql;
    	// exit;
    	$delres = $this->dao->ExecUpdate($deletesql);

    	if($delres){
    		$insertsql = "insert into {$this->m_tableName} (sServiceKey,sServiceName) values ";
    		foreach($param as $k=>$v){
    			$insertsql .= "('{$k}','{$v}'),";
    		}
    	}
    	$insertsql = substr($insertsql,0,strlen($insertsql)-1);
    	$insertres = $this->dao->ExecUpdate($insertsql);
    	return  $insertres;
    }
}