<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/15
 * Time: 10:30
 */

class task extends AbstractAction {

    public function __construct($params) {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index(){}

}