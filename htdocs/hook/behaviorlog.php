<?php
/**
 * 记录用户访问日志
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/10/29
 * Time: 10:11
 */

class behaviorlog extends hook
{
    public function hook($module,$action,$func,$type='')
    {
        $tbBehaviorLog = $this->dao->tbBehaviorLog;

        $sRtxName = $_SESSION['right_user'];

        //$_GET 及 post 参数获取
        $args = json_encode(array_merge($_GET, $_POST));

        $data = array(
            'sRtxName'  => $sRtxName,
            'module'    => $module,
            'action'    => $action,
            'func'      => $func,
            'sArg'      => $args,
            'dtOptTime' => date('Y-m-d H:i:s')
        );

        $tbBehaviorLog->insertColumn($data);

        return true;
    }
}