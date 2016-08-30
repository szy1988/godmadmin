<?php
/*
 公司的osslogin的验证
*/

$domain = $_SERVER['SERVER_NAME'];
$nowPath = str_replace('checkOALogin.php','',$_SERVER['REQUEST_URI']);
$nowUrl = 'http://'.$domain.$nowPath; //这个是项目的根目录

//如果有cookie，清空cookie
setcookie("right_user",'',time()-8640000,"/",$domain,0);

// $ossName = "v_zjzzhu";
// setcookie("right_user",$ossName,time()+86400,"/",$domain,0);
// echo "<script language=javascript>window.location='http://v_zjzzhu.qq.com/opwebadmin/opwebadmin/htdocs/index.php';</script>";
// exit;
//ticket参数
if(!empty($_GET['ticket'])) {
	$ticket = $_GET['ticket'];
	setcookie("TCOA_TICKET",$ticket,time()+86400,"/",$domain,0);
} else {
    header("location:{$nowUrl}");
	//echo "<script language=javascript>alert('参数错误，请您重新登录');window.location='{$nowUrl}';</script>";
	exit;
}

try {
//10.1.163.32 是外包同学开发机
if($_SERVER['SERVER_ADDR'] == "10.12.239.238" || $_SERVER['SERVER_ADDR'] == "10.1.163.32"){
	//echo 1;
	$client = new SoapClient("http://logon.oa.com/Services/passportService.asmx?WSDL");
	//$client = new SoapClient("http://login.oa.com/Services/passportService.asmx?WSDL");
}else{
	$client = new SoapClient("http://login.oa.com/Services/passportService.asmx?WSDL");
}
//$client = new SoapClient("http://indigo.oa.com/services/passportservice.asmx?WSDL");
    //$client = new SoapClient("http://login.oa.com/Services/passportService.asmx?WSDL");
    //$client = new SoapClient("http://passport.oa.com/services/passportservice.asmx?WSDL");

    //$error = $client->getError();
    //var_dump($error);exit;

    //$client = new SoapClient("http://logon.oa.com/Services/passportService.asmx?WSDL");
//$client = new SoapClient("http://indigo.oa.com/services/passportservice.asmx?WSDL",array('proxy_host'=> "10.1.164.109",'proxy_port'=>'7070'));
} catch (exception $e) {

	var_dump($e);
}

//echo $ticket."<br />";
$ticketInfo = $client->DecryptTicket(array("encryptedTicket" => $ticket));
//var_dump($ticketInfo);exit;


if(!isset($ticketInfo->DecryptTicketResult)){
	//echo "<script language=javascript>alert('NO Login！');</script>";
    $rediectUrl = str_replace('checkOALogin.php', '', $_SERVER['REQUEST_URI']);
    $rediectUrl = substr($rediectUrl, 0, strpos($rediectUrl, '?'));
    $lastRediectUrl = 'http://'. $domain . $rediectUrl . 'index.php?module=common_until&action=no_acl&from=checkOALogin';

    header("location:{$lastRediectUrl}");
	exit;
}

$ossName = $ticketInfo->DecryptTicketResult->LoginName;

setcookie("right_user",$ossName,time()+86400,"/",$domain,0);
$lastUrl=$_COOKIE["CLIENT_POP_LAST_URL"];

if ($lastUrl=="") {
	echo "<script language=javascript>window.location='$nowUrl';</script>";
} else {
	echo "<script language=javascript>window.location='$lastUrl';</script>";
}

