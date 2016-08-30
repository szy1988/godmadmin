<?php
class tbCaseInfo extends AbstractDao {

    protected $m_tableName = 'tbCaseInfo';

    public function __construct()
    {
        parent::__construct('DB');
    }

    //获得统计列表统计
    public function getListByCondition($condition = array()) {
        // var_dump($condition);
        if (array_key_exists('sum', $condition) && $condition['sum']) {
            $sql = "SELECT count(1) as sum FROM {$this->m_tableName} where 1";
        }else{
        	$sql = "SELECT * FROM {$this->m_tableName} where 1";
        }
       
        // 字段
        if (array_key_exists('iCaseId', $condition) && $condition['iCaseId']) {
            $sql .= " AND iCaseId=" . mysql_escape_string($condition['iCaseId']);
        }
        if (array_key_exists('iCaseKindID', $condition) && $condition['iCaseKindID']) {
            $sql .= " AND iCaseKindID='" . mysql_escape_string($condition['iCaseKindID']). "'";
        }
        
        if (array_key_exists('sDisplayService', $condition) && $condition['sDisplayService']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['sDisplayService']). "',sDisplayService) or sDisplayService='' )";
        }
        if (array_key_exists('sCaseName', $condition) && $condition['sCaseName']) {
            $sql .= " AND sCaseName like '%" . mysql_escape_string($condition['sCaseName']). "%'";
        }
        if (array_key_exists('sConnectRTX', $condition) && $condition['sConnectRTX']) {
            $sql .= " AND (sConnectRTX like '%" . mysql_escape_string($condition['sConnectRTX']). "%' or sSubmitRtx = '" . mysql_escape_string($condition['sConnectRTX']). "')";
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState='" . mysql_escape_string($condition['iState']). "'";
        }
        
        $sql .= " order by iCaseId desc ";

        if (array_key_exists('limit', $condition) && $condition['limit'] && !array_key_exists('sum', $condition)) {
            $sql .= ' limit ';
            if (array_key_exists('start', $condition) && $condition['start']) {
                $sql .= mysql_escape_string($condition['start']) . ',';
            }
            $sql .= mysql_escape_string($condition['limit']);
        }

        // echo $sql;
        // exit;
        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
    
    

    

    public function batchDeal($iCaseId,$aftState,$preState,$sRefuseReason){
        $sql = "update {$this->m_tableName} set iState={$aftState} ";
        if($aftState == 2 && !empty($sRefuseReason)){
            $sql.= " ,sRefuseReason = '{$sRefuseReason}' ";
        }

        $sql.=  "where find_in_set(iCaseId,'{$iCaseId}') and iState={$preState}";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret > 0) {
            return $ret;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    public function zan($iCaseId,$rtx,$iCaseKindID){
        $insertsql = "insert into tbCaseZan (`sRTX`,`iCaseId`,`iCaseKindID`,`dtDateTime`) values ('{$rtx}','{$iCaseId}','{$iCaseKindID}',now())";
        $insertres = $this->dao->ExecUpdate($insertsql);
        if($insertres){
            $sql = "update {$this->m_tableName} set iZan=iZan+1 where iCaseId={$iCaseId}";
            $ret = $this->dao->ExecUpdate($sql);
            if ($ret > 0) {
                return true;
            } else {
                $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
                return false;
            }

        }else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
        
    }

    public function getZanInfo($rtx,$iCaseId){

        $sql = "select * from tbCaseZan where sRTX='{$rtx}' and iCaseId={$iCaseId} and dtDateTime>=CURDATE()";
        // echo $sql;
        // exit;
        $ret = $this->dao->ExecQuery($sql, &$result);
        if($ret){
            return $result;
        }else{
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }

    }

    public function getZanKind($rtx){
        $res = array();
        $sql = "select * from tbCaseZan where sRTX='{$rtx}' and dtDateTime>=CURDATE() group by iCaseKindID";
        // echo $sql;
        // exit;
        $ret = $this->dao->ExecQuery($sql, &$result);
        if($ret){
            foreach($result as $v){
                $res[] = $v['iCaseKindID'];
            }
            return $res;
        }else{
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }

    }

    public function getZanCase($rtx){
        $res = array();
        $sql = "select * from tbCaseZan where sRTX='{$rtx}' and dtDateTime>=CURDATE()";
        // echo $sql;
        // exit;
        $ret = $this->dao->ExecQuery($sql, &$result);
        if($ret){
            foreach($result as $v){
                $res[] = $v['iCaseId'];
            }
            return $res;
        }else{
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }

    }
    
}