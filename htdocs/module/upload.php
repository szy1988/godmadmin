<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class upload extends AbstractAction{
//    protected $newName = '';
    protected $file = '';
    protected $validType = array();
    protected $newLoc = '';
    protected $newFile = '';
    protected $sizeCheck = array();


    public function __construct($params) {
        parent::__construct($params);
    }

    public function index(){
    }
    
    protected function uploadSizeCheck(){
       return 'pass';  
    }

}
