<?php
class resources_test extends AbstractDao{
    protected $m_tableName = 'tbGoDeployment';  //数据库的表名

    function __construct() {
        parent::__construct('HAC_DB');
    }


    /**
     * 获取所有资源
     * @return [type] [description]
     */
    public function getAllDeployment($condition = ''){
        $sql = "SELECT * FROM `{$this->m_tableName}` where 1 ".$condition;
        $res = $this->dao->ExecQuery($sql, &$result);
        if ($res > 0) {
            return $result;
        } else {
            return false;
        }    
    }

    public function getAllDeploymentCount($condition = ''){
        $sql = "SELECT count(*) as num FROM `{$this->m_tableName}` where 1 ".$condition;
        $res = $this->dao->ExecQuery($sql, &$result);
        if ($res > 0) {
            return $result[0]['num'];
        } else {
            return 0;
        } 
    }

    public function getAllModule(){
        $sql = "SELECT * FROM `tbGoModule` where 1 ";
        $res = $this->dao->ExecQuery($sql, &$result);
        if ($res > 0) {
            return $result;
        } else {
            return false;
        }    
    }

    public function checkDeploment($mid,$ip){
        $sql = "SELECT * FROM `tbGoDeployment` where `mid` = {$mid} and `ip`= '{$ip}'";
        $res = $this->dao->ExecQuery($sql, &$result);
        if ($res > 0) {
            return true;
        } else {
            return false;
        } 
    }

    public function addDeploment($data){
        $sql = "INSERT INTO `tbGoDeployment`(`ip`,`mid`,`model`) values('{$data['ip']}',{$data['mid']},'{$data['model']}')";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDeploment($id){
        $sql = "delete FROM `tbGoDeployment` where id = {$id}";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDeploment($id,$data){
        if(empty($data)){
            return true;
        }
        $conditions = array();
        foreach ($data as $key => $value) {
            $conditions[] = " `{$key}` = '{$value}' ";
        }
        $condition = implode(',', $conditions);
        $sql = "update `tbGoDeployment` set {$condition} where id = {$id}";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return true;
        } else {
            return false;
        }
    }


}