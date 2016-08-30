<?php
/*
数据操作类
*/


abstract class AbstractDao
{
    protected $dao;

    public function __construct($config_db='DB' ){
        $this->dao = DAO::getInstance($config_db);
        $this->OssLog = OssLog::getInstance();

    }

    protected function filterParameters($param) {
        if (is_array($param)){
            foreach ($param as $k => $v){
                $param[$k] = $this->filterParameters($v); //recursive
            }
        }
        elseif (is_string($param)){
            $param = htmlspecialchars($param);
            // 过滤引号
            $trans = array(
                "'" => '&apos;'
            );
            $param = strtr($param,$trans);
        }
        return $param;
    }

    /**
     * 根据主键ID获取详细信息（通用函数）
     * @param array  $primaryArr('id',123)
     * @author  jaspersong
     */
    public function getActInfoById($primaryArr=array()){
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "EXEC " . __METHOD__ . " \n");
        if (!empty($primaryArr)){
            $sql = "SELECT * FROM `{$this->m_tableName}` WHERE `{$primaryArr[0]}`='{$primaryArr[1]}'";
        }else {
            $sql = "SELECT * FROM `{$this->m_tableName}` WHERE 1";
        }
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . "\n");
        $res = $this->dao->ExecQuery($sql, &$result);
        if ($res > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return $res;
        }
    }

    /**
     * 删除操作
     * @param  array('id',12)
     * @return boolean
     * @since  2015-9-17
     * @author jaspersong
     */
    public function delColumnById($primaryArr=array()){
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "EXEC " . __METHOD__ . " \n");
        $sql = "DELETE FROM `{$this->m_tableName}` WHERE `{$primaryArr[0]}` = '{$primaryArr[1]}'";
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . "\n");
        return $this->dao->ExecUpdate($sql);
    }

    /**
     *更新操作
     * @param array $ids
     * @return boolean
     * @since 2015-9-14
     * @author jaspersong
     */
    public function updateColumnByIds($primaryArr=array(), $data=array()){
        $data = $this->filterParameters($data);
        $keys = array_keys($data);
        $values = array_values($data);
        foreach ($keys as &$v) {
            $v = '`' . $v . '`';
        }
        foreach ($values as &$v) {
            if( $v != 'NOW()' ){
                $v = '"' . $v . '"';
            }
        }
        $setStr = '';
        for ($i = 0; $i < count($keys); $i++) {
            $setStr .= $keys[$i] . '=' . $values[$i] . ',';
        }
        $setStr = substr($setStr, 0, -1);

        $sql = 'UPDATE `' . $this->m_tableName . '` SET ' . $setStr . ' WHERE `'.$primaryArr[0].'`="'.$primaryArr[1].'"';
        // echo $sql;
        // exit;
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . "\n");
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return true;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }

    public function insertColumn($data = array()){
        $data = $this->filterParameters($data);
        $keys = array_keys($data);
        $values = array_values($data);
        foreach ($keys as &$v) {
            $v = '`' . $v . '`';
        }
        foreach ($values as &$v) {
           if( $v != 'NOW()' ){
                $v = '"' . $v . '"';
            }
        }

        $sql = 'INSERT INTO `' . $this->m_tableName . '` (' . implode(',', $keys) . ') VALUES (' . implode(',', $values) . ')';
        $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . "\n");
        $ret = $this->dao->ExecUpdate($sql);
        if ($ret >= 0) {
            return $ret;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }



}
?>