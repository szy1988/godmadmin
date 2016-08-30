<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/29
 * Time: 10:10
 * tbBehaviorLog
 */

class tbBehaviorLog extends AbstractDao {

    protected $m_tableName = 'tbBehaviorLog';

    public function __construct()
    {
        parent::__construct('DB');
    }

}