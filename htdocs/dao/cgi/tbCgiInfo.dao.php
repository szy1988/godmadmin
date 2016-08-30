<?php
class tbCgiInfo extends AbstractDao {

    protected $m_tableName = 'tbCgiInfo';

    public function __construct()
    {
        parent::__construct('DB');
    }

    //获得统计列表统计
    public function getSumByCondition($condition = array()) {
        // var_dump($condition);
        $sql = "SELECT count(1) as sum FROM {$this->m_tableName} where 1";
        
        // 字段
        if (array_key_exists('iCgiId', $condition) && $condition['iCgiId']) {
            $sql .= " AND iCgiId=" . mysql_escape_string($condition['iCgiId']);
        }

        
        if (array_key_exists('iDevCenter', $condition) && $condition['iDevCenter']) {
            $sql .= " AND iDevCenter='" . mysql_escape_string($condition['iDevCenter']). "'";
        }
        if (array_key_exists('iCgiDevGroup', $condition) && $condition['iCgiDevGroup']) {
            $sql .= " AND iCgiDevGroup='" . mysql_escape_string($condition['iCgiDevGroup']). "'";
        }
        if (array_key_exists('sCgiName', $condition) && $condition['sCgiName']) {
            $sql .= " AND sCgiName like '%" . mysql_escape_string($condition['sCgiName']). "%'";
        }
        if (array_key_exists('sConnectRTX', $condition) && $condition['sConnectRTX']) {
            $sql .= " AND sConnectRTX like '%" . mysql_escape_string($condition['sConnectRTX']). "%'";
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState='" . mysql_escape_string($condition['iState']). "'";
        }
        if (array_key_exists('iCgiGroup', $condition) && $condition['iCgiGroup']) {
            $sql .= " AND iCgiGroup='" . mysql_escape_string($condition['iCgiGroup']). "'";
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
        $sql = "SELECT a.*,b.sUserRtx,b.iAuthState,b.iUin,b.id,b.iCommonUse FROM {$this->m_tableName} a left join tbMyApp b on a.iCgiId=b.iAppId ";

        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND b.sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }else{
            $sql .= " AND b.sUserRtx='-1'";
        }

        $sql .= " AND b.iIsCgi = 1 ";
        $sql .= " where 1 ";
        
        if (array_key_exists('iCgiId', $condition) && $condition['iCgiId']) {
            $sql .= " AND a.iCgiId=" . mysql_escape_string($condition['iCgiId']);
        }
        if (array_key_exists('iCgiDevGroup', $condition) && $condition['iCgiDevGroup']) {
            $sql .= " AND a.iCgiDevGroup='" . mysql_escape_string($condition['iCgiDevGroup']). "'";
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND a.iState='" . mysql_escape_string($condition['iState']). "'";
        }

        if (array_key_exists('iAuthState', $condition) && $condition['iAuthState']) {
            $sql .= " AND b.iAuthState='" . mysql_escape_string($condition['iAuthState']). "'";
        }




        // if (array_key_exists('group', $condition) && $condition['group']) {
        //     $sql .= " GROUP BY  {$condition['group']} ";
        // }
        // 排序
        if (array_key_exists('iCgiGroup', $condition) && $condition['iCgiGroup']) {
            $sql .= " AND iCgiGroup='" . mysql_escape_string($condition['iCgiGroup']). "'";
        }
        $sql .= " GROUP BY a.iCgiId ";
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
        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }


    //获得统计列表数据
    public function getListByConditionOneTable($condition = array()) {
        $sql = "SELECT * FROM {$this->m_tableName} where 1 ";

        if (array_key_exists('iCgiId', $condition) && $condition['iCgiId']) {
            $sql .= " AND iCgiId=" . mysql_escape_string($condition['iCgiId']);
        }
        if (array_key_exists('iDevCenter', $condition) && $condition['iDevCenter']) {
            $sql .= " AND iDevCenter='" . mysql_escape_string($condition['iDevCenter']). "'";
        }
        if (array_key_exists('iCgiDevGroup', $condition) && $condition['iCgiDevGroup']) {
            $sql .= " AND iCgiDevGroup='" . mysql_escape_string($condition['iCgiDevGroup']). "'";
        }
        if (array_key_exists('iState', $condition) && $condition['iState']) {
            $sql .= " AND iState='" . mysql_escape_string($condition['iState']). "'";
        }
        if (array_key_exists('sCgiName', $condition) && $condition['sCgiName']) {
            $sql .= " AND sCgiName like '%" . mysql_escape_string($condition['sCgiName']). "%'";
        }
        if (array_key_exists('sConnectRTX', $condition) && $condition['sConnectRTX']) {
            $sql .= " AND sConnectRTX like '%" . mysql_escape_string($condition['sConnectRTX']). "%'";
        }
        // if (array_key_exists('group', $condition) && $condition['group']) {
        //     $sql .= " GROUP BY  {$condition['group']} ";
        // }
        // 排序
        if (array_key_exists('iCgiGroup', $condition) && $condition['iCgiGroup']) {
            $sql .= " AND iCgiGroup='" . mysql_escape_string($condition['iCgiGroup']). "'";
        }
        $sql .= " GROUP BY iCgiId ";
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

        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    

    public function batchDeal($iAppIds,$aftState,$preState,$sRefuseReason){
        $sql = "update {$this->m_tableName} set iState={$aftState} ";
        if($aftState == 2 && !empty($sRefuseReason)){
            $sql.= " ,sRefuseReason = '{$sRefuseReason}' ";
        }

        $sql.=  "where iCgiId in ({$iAppIds}) and iState={$preState}";
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret > 0) {
            return $ret;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
}