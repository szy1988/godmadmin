<?php
class tbMyApp extends AbstractDao {

    protected $m_tableName = 'tbDevGroup';

    public function __construct()
    {
        parent::__construct('DB');
    }
}