<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/14
 * Time: 16:55
 */

class common_until extends AbstractAction {
    public function __construct($params) {
        $this->m_params = $params;
        parent::__construct($params);
    }

    public function index(){}

}