<?php
class tbAppKind extends AbstractDao {

    protected $m_tableName = 'tbAppKind';

    public function __construct()
    {
        parent::__construct('DB');
    }

    public function getAppKindByOrder() {
        // var_dump($condition);
        $sql = "SELECT * FROM {$this->m_tableName} order by iOrder asc";
        $ret = $this->dao->ExecQuery($sql, &$result);
        if ($ret > 0) {
            return $result;
        } else {
            $this->OssLog->writeLog(__FILE__, __LINE__, LP_DEBUG, "SQL: " . $sql . " EXEC error\n");
            return false;
        }
    }
}