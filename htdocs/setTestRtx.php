<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/15
 * Time: 16:12
 */

session_start();
$dominName = $_SERVER['SERVER_NAME'];
$_SESSION['right_user'] = $_GET['rtx'];
$_SESSION['X_TICKET'] = $_GET['rtx'];
