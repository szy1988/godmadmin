<?php
class tbAppInfo extends AbstractDao {

    protected $m_tableName = 'tbAppInfo';

    public function __construct()
    {
        parent::__construct('DB');
    }

    //获得统计列表统计
    public function getSumByCondition($condition = array()) {
        // var_dump($condition);
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} where 1";
        
        // 字段
        if (array_key_exists('id', $condition) && $condition['id']) {
            $sql .= " AND id=" . mysql_escape_string($condition['id']);
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND iAppGroup='" . mysql_escape_string($condition['iAppGroup']). "'";
        }

        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState='" . mysql_escape_string($condition['iState']). "'";
        }
        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND iAppDevGroup='" . mysql_escape_string($condition['iAppDevGroup']). "'";
        }
        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['serviceType']). "',sAppOnlyService) or sAppOnlyService='' )";
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
    
    //获得统计列表数据
    public function getListByCondition($condition = array()) {
        $sql = "SELECT a.*,b.sUserRtx,b.iAuthState,b.iUin,b.iCommonUse,b.id FROM {$this->m_tableName} a left join tbMyApp b on a.iAppId=b.iAppId ";

        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND b.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }else{
            $sql .= " AND b.sUserRtx='-1'";
        }

        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (b.sAppService='" . mysql_escape_string($condition['serviceType'])."' or b.sAppService='')";
        }

        $sql .= " AND b.iIsCgi = 0 ";
        $sql .= " where 1 ";
        
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND a.iAppId=" . mysql_escape_string($condition['iAppId']);
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND a.iAppGroup='" . mysql_escape_string($condition['iAppGroup']). "'";
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND a.iState='" . mysql_escape_string($condition['iState']). "'";
        }

        if (array_key_exists('serviceType', $condition) && $condition['serviceType']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['serviceType']). "',a.sAppOnlyService) or a.sAppOnlyService='' )";
        }
        // if (array_key_exists('group', $condition) && $condition['group']) {
        //     $sql .= " GROUP BY  {$condition['group']} ";
        // }
        // 排序
        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND a.iAppDevGroup='" . mysql_escape_string($condition['iAppDevGroup']). "'";
        }
        $sql .= " GROUP BY a.iAppId ";
        if (array_key_exists('order', $condition) && $condition['order']) {
            $sql .= ' ' . mysql_escape_string($condition['order']);
        } else {
            $sql .= " ORDER BY a.dtUDate DESC";
        }


        
        // limit
        if (array_key_exists('limit', $condition) && $condition['limit']) {
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

    //获得统计列表统计单表查询
    public function getSumByConditionTable($condition = array()) {
        // var_dump($condition);
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} where 1";
        
        // 字段
        if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND iAppId=" . mysql_escape_string($condition['iAppId']);
        }
        if (array_key_exists('sConnectRTX', $condition) && $condition['sConnectRTX']) {
            $sql .= " AND sConnectRTX='%" . mysql_escape_string($condition['sConnectRTX']). "%'";
        }
        if (array_key_exists('sAppName', $condition) && $condition['sAppName']) {
            $sql .= " AND sAppName like '%" . mysql_escape_string($condition['sAppName']). "%'";
        }
        if (array_key_exists('iDevCenter', $condition) && $condition['iDevCenter']) {
            $sql .= " AND iDevCenter='" . mysql_escape_string($condition['iDevCenter']) . "'";
        }
        if (array_key_exists('iDevGroup', $condition) && $condition['iDevGroup']) {
            $sql .= " AND iDevGroup='" . mysql_escape_string($condition['iDevGroup']) . "'";
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND iAppGroup='" . mysql_escape_string($condition['iAppGroup']) . "'";
        }
        // if (array_key_exists('sAppOnlyService', $condition) && $condition['sAppOnlyService']) {
        //     $sql .= " AND FIND_IN_SET('" . mysql_escape_string($condition['sAppOnlyService']). "',sAppOnlyService) ";
        // }
        if (array_key_exists('sAppOnlyService', $condition) && $condition['sAppOnlyService']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['sAppOnlyService']). "',sAppOnlyService) or sAppOnlyService='' )";
        }
        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND iAppDevGroup=" . mysql_escape_string($condition['iAppDevGroup']);
        }
        if (array_key_exists('iHot', $condition) && $condition['iHot']) {
            $sql .= " AND iHot=" . mysql_escape_string($condition['iHot']);
        }

        if (array_key_exists('iSupportMobile', $condition) && $condition['iSupportMobile']) {
            $sql .= " AND iSupportMobile=" . mysql_escape_string($condition['iSupportMobile']);
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState=" . mysql_escape_string($condition['iState']);
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
    
    //获得统计列表数据单表查询
    public function getListByConditionTable($condition = array(), $select = "*") {
        $sql = "SELECT {$select} FROM {$this->m_tableName} where 1";
        
         if (array_key_exists('iAppId', $condition) && $condition['iAppId']) {
            $sql .= " AND iAppId=" . mysql_escape_string($condition['iAppId']);
        }
        if (array_key_exists('sConnectRTX', $condition) && $condition['sConnectRTX']) {
            $sql .= " AND sConnectRTX like '%" . mysql_escape_string($condition['sConnectRTX']). "%'";
        }
        if (array_key_exists('sAppName', $condition) && $condition['sAppName']) {
            $sql .= " AND sAppName like '%" . mysql_escape_string($condition['sAppName']). "%'";
        }
        if (array_key_exists('iDevCenter', $condition) && $condition['iDevCenter']) {
            $sql .= " AND iDevCenter='" . mysql_escape_string($condition['iDevCenter']) . "'";
        }
        if (array_key_exists('iDevGroup', $condition) && $condition['iDevGroup']) {
            $sql .= " AND iDevGroup='" . mysql_escape_string($condition['iDevGroup']) . "'";
        }
        if (array_key_exists('iAppGroup', $condition) && $condition['iAppGroup']) {
            $sql .= " AND iAppGroup='" . mysql_escape_string($condition['iAppGroup']) . "'";
        }
        // if (array_key_exists('sAppOnlyService', $condition) && $condition['sAppOnlyService']) {
        //     $sql .= " FIND_IN_SET('" . mysql_escape_string($condition['sAppOnlyService']). "',sAppOnlyService)";
        // }
        if (array_key_exists('sAppOnlyService', $condition) && $condition['sAppOnlyService']) {
            $sql .= " AND (FIND_IN_SET('" . mysql_escape_string($condition['sAppOnlyService']). "',sAppOnlyService) or sAppOnlyService='' )";
        }
        if (array_key_exists('iAppDevGroup', $condition) && $condition['iAppDevGroup']) {
            $sql .= " AND iAppDevGroup=" . mysql_escape_string($condition['iAppDevGroup']);
        }


        if (array_key_exists('iHot', $condition) && $condition['iHot']) {
            $sql .= " AND iHot=" . mysql_escape_string($condition['iHot']);
        }

        if (array_key_exists('iSupportMobile', $condition) && $condition['iSupportMobile']) {
            $sql .= " AND iSupportMobile=" . mysql_escape_string($condition['iSupportMobile']);
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState=" . mysql_escape_string($condition['iState']);
        }
        if (array_key_exists('group', $condition) && $condition['group']) {
            $sql .= " GROUP BY  {$condition['group']} ";
        }
        // 排序
        if (array_key_exists('order', $condition) && $condition['order']) {
            $sql .= ' ' . mysql_escape_string($condition['order']);
        } else {
            $sql .= " ORDER BY dtUDate DESC";
        }
        
        // limit
        if (array_key_exists('limit', $condition) && $condition['limit']) {
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

    public function batchDeal($iAppIds,$aftState,$preState){
        $sql = "update {$this->m_tableName} set iState={$aftState} where iAppId in ({$iAppIds}) and iState={$preState}";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret > 0) {
            return $ret;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
}