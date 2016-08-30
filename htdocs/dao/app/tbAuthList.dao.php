<?php
class tbAuthList extends AbstractDao {

    protected $m_tableName = 'tbAuthList';

    public function __construct()
    {
        parent::__construct('DB');
    }

    public function getAuthList($condition = array(),$select = '*') {
        // 字段
        $sql = " FROM {$this->m_tableName} WHERE 1 ";
        if (array_key_exists('iId', $condition) && $condition['iId']) {
            $sql .= " AND iId=" . mysql_escape_string($condition['iId']);
        }
        if (array_key_exists('sUserRtx', $condition) && $condition['sUserRtx']) {
            $sql .= " AND sUserRtx='" . mysql_escape_string($condition['sUserRtx']). "'";
        }
        if (array_key_exists('iStatus', $condition)) {
            $sql .= " AND iStatus=" . mysql_escape_string($condition['iStatus']);
        }
        if (array_key_exists('sAppService ', $condition) && $condition['sAppService ']) {
            $sql .= " AND sAppService ='" . mysql_escape_string($condition['sAppService ']). "'";
        }  
        $countSql = 'SELECT COUNT(*) AS `num` ' . $sql;
        $ret = $this->dao->ExecQuery($countSql, &$result);
        $num = $result[0]['num'];
        if ($ret > 0) {
            $sql = "SELECT {$select}" . $sql;
            if (array_key_exists('limit', $condition) && $condition['limit']) {
                $sql .= ' limit ';
                if (array_key_exists('start', $condition) && $condition['start']) {
                    $sql .= mysql_escape_string($condition['start']) . ',';
                }
                $sql .= mysql_escape_string($condition['limit']);
            }
            $ret = $this->dao->ExecQuery($sql, &$result);
            if ($ret > 0) {
                $return = array(
                    'total' => $num,
                    'data' => $result,
                );
                return $return;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
}