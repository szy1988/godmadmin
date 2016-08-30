<?php
class tbMyApp extends AbstractDao {

    protected $m_tableName = 'tbMyApp';

    public function __construct()
    {
        parent::__construct('DB');
    }

    //获得统计列表统计
    public function getSumByCondition($condition = array()) {
        // var_dump($condition);
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} where 1 ";
        
        // 字段
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND iAppId='" . mysql_escape_string($condition['iAppId']) . "'";
        }
        if (array_key_exists('dtDate', $condition) && $condition['dtDate']) {
            $sql .= " AND dtDate='" . mysql_escape_string($condition['dtDate']) . "'";
        }
        if (array_key_exists('iCommonUse', $condition) && $condition['iCommonUse']) {
            $sql .= " AND iCommonUse=" . mysql_escape_string($condition['iCommonUse']);
        }
        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (sAppService='" . mysql_escape_string($condition['serviceType']). "' or sAppService='' )";
        }

        if (array_key_exists('iIsCgi', $condition) && $condition['iIsCgi']) {
            $sql .= " AND iIsCgi=" . mysql_escape_string($condition['iIsCgi']);
        }else{
            $sql .= " AND iIsCgi=0";
        }

        if (array_key_exists('group', $condition) && $condition['group']) {
            $sql .= " GROUP BY  {$condition['group']} ";
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


    //获得关联APP列表sum
    public function getAppListSumByCondition($condition = array()) {
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} a left join tbAppInfo b on a.iAppId=b.iAppId where 1 AND a.iIsCgi=0 and b.iState=1";
        
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND a.id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND a.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND a.iAppId='" . mysql_escape_string($condition['iAppId']) . "'";
        }
        if (array_key_exists('dtDate', $condition) && $condition['dtDate']) {
            $sql .= " AND a.dtDate='" . mysql_escape_string($condition['dtDate']) . "'";
        }

        if (array_key_exists('iCommonUse', $condition) && $condition['iCommonUse']) {
            $sql .= " AND a.iCommonUse=" . mysql_escape_string($condition['iCommonUse']);
        }
        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (a.sAppService='" . mysql_escape_string($condition['serviceType']). "' or a.sAppService='' )";
        }
        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['serviceType']). "',b.sAppOnlyService) or b.sAppOnlyService='' )";
        }

        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND b.iAppDevGroup=" . mysql_escape_string($condition['iAppDevGroup']);
        }else{
            $sql .= " AND b.iAppDevGroup=1";
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND b.iAppGroup=" . mysql_escape_string($condition['iAppGroup']);
        }

        // echo $sql;
        // exit;
        // limit
        
        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
    
    //获得关联APP列表
    public function getAppListByCondition($condition = array()) {
        $sql = "SELECT * FROM {$this->m_tableName} a left join tbAppInfo b on a.iAppId=b.iAppId where 1 AND a.iIsCgi=0 and b.iState=1";
        
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND a.id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND a.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND a.iAppId='" . mysql_escape_string($condition['iAppId']) . "'";
        }
        if (array_key_exists('dtDate', $condition) && $condition['dtDate']) {
            $sql .= " AND a.dtDate='" . mysql_escape_string($condition['dtDate']) . "'";
        }

        if (array_key_exists('iCommonUse', $condition) && $condition['iCommonUse']) {
            $sql .= " AND a.iCommonUse=" . mysql_escape_string($condition['iCommonUse']);
        }
        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (a.sAppService='" . mysql_escape_string($condition['serviceType']). "' or a.sAppService='' )";
        }

        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['serviceType']). "',b.sAppOnlyService) or b.sAppOnlyService='' )";
        }

        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND b.iAppDevGroup=" . mysql_escape_string($condition['iAppDevGroup']);
        }else{
            $sql .= " AND b.iAppDevGroup=1";
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND b.iAppGroup=" . mysql_escape_string($condition['iAppGroup']);
        }

        if (array_key_exists('group', $condition) && $condition['group']) {
            $sql .= " GROUP BY  {$condition['group']} ";
        }
        // 排序
        if (array_key_exists('order', $condition) && $condition['order']) {
            $sql .= ' ' . mysql_escape_string($condition['order']);
        } else {
            $sql .= " ORDER BY a.iUseTimes DESC";
        }

        // echo $sql;
        // exit;
        // limit
        if (array_key_exists('limit', $condition) && $condition['limit']) {
            $sql .= ' limit ';
            if (array_key_exists('start', $condition) && $condition['start']) {
                $sql .= mysql_escape_string($condition['start']) . ',';
            }
            $sql .= mysql_escape_string($condition['limit']);
        }
        
        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }


    //获得关联CGI列表sum
    public function getCgiListSumByCondition($condition = array()) {
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} a left join tbCgiInfo b on a.iAppId=b.iCgiId where 1 AND a.iIsCgi=1 and b.iState=1 AND a.iCommonUse=1";
        
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND a.id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND a.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND a.iAppId='" . mysql_escape_string($condition['iAppId']) . "'";
        }
        if (array_key_exists('dtDate', $condition) && $condition['dtDate']) {
            $sql .= " AND a.dtDate='" . mysql_escape_string($condition['dtDate']) . "'";
        }

        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    //获得关联CGI列表
    public function getCgiListByCondition($condition = array()) {
        $sql = "SELECT * FROM {$this->m_tableName} a left join tbCgiInfo b on a.iAppId=b.iCgiId where 1 AND a.iIsCgi=1 and b.iState=1 AND a.iCommonUse=1";
        
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND a.id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND a.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND a.iAppId='" . mysql_escape_string($condition['iAppId']) . "'";
        }
        if (array_key_exists('dtDate', $condition) && $condition['dtDate']) {
            $sql .= " AND a.dtDate='" . mysql_escape_string($condition['dtDate']) . "'";
        }

        // 排序
        if (array_key_exists('order', $condition) && $condition['order']) {
            $sql .= ' ' . mysql_escape_string($condition['order']);
        } else {
            $sql .= " ORDER BY a.iUseTimes DESC";
        }

        // limit
        if (array_key_exists('limit', $condition) && $condition['limit']) {
            $sql .= ' limit ';
            if (array_key_exists('start', $condition) && $condition['start']) {
                $sql .= mysql_escape_string($condition['start']) . ',';
            }
            $sql .= mysql_escape_string($condition['limit']);
        }

        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    public function updateUseTimeById($primaryArr=array(), $data=array()){
        $data = $this->filterParameters($data);
        $keys = array_keys($data);
        $values = array_values($data);
        foreach ($keys as &$v) {
            $v = '`' . $v . '`';
        }
        $setStr = '';
        for ($i = 0; $i < count($keys); $i++) {
            $setStr .= $keys[$i] . '=' . $values[$i] . ',';
        }
        $setStr = substr($setStr, 0, -1);

        $sql = 'UPDATE `' . $this->m_tableName . '` SET ' . $setStr . ' WHERE `'.$primaryArr[0].'`="'.$primaryArr[1].'"';
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . "\n");
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return true;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    public function queryAuthstate($rtx){
        $sql = "SELECT * FROM {$this->m_tableName} a left join tbAppInfo b on a.iAppId=b.iAppId where 1 and sUserRtx='{$rtx}' and iAuthState = 1";
        $ret = $this->dao->ExecQuery($sql, &$result);
         if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
    public function updateAuthstate($id){
        $sql = "update {$this->m_tableName} set iAuthState = 2 where id={$id}";
        $ret = $this->dao->ExecUpdate($sql);
         if ($ret > 0) {
            return $ret;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
}