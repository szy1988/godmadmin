<?php
/**
 * Created by PhpStorm.
 * User: zhaojingsi
 * Date: 2015/9/22
 * Time: 14:49
 * 系统维护状态
 */

class systemState extends hook
{
    public function hook($module,$action,$func,$type='')
    {

        $sysState = $this->config->sys;
		$openState = $sysState['sys_lock'];
		
		if ($openState == 0) {
			//header("location:index.php?module=common_until&action=no_acl&open=0");
			$this->showAlert(0, '系统已经关闭，请稍候再试。');
			exit;
		} else {
			//如果为1时，判断当前时间是否在维护期间
			$nowDateTime = date('Y-m-d H:i:s');
			$startTime = $sysState['start_time'];
			$endTime = $sysState['end_time'];
			
			if ($nowDateTime >= $startTime && $nowDateTime <= $endTime) {
				//维护中
				//header("location:index.php?module=common_until&action=no_acl&open=1");
				$this->showAlert(0, "系统正在维护中，请稍候再试！\\n维护时间：{$startTime}--{$endTime}。");
				exit;
			}
		}
        

        return true;
    }
	
	private function showAlert($iRet, $msg, $url = '')
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo '<script>';
        echo "alert('".$msg."');";
        if ($url != '') {
            echo "location.href = '{$url}';";
        }
        echo "</script>";
        exit;
    }
}