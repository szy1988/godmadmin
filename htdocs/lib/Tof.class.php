<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rickyzeng
 * Date: 13-12-6
 * Time: 下午4:10
 * To change this template use File | Settings | File Templates.
 */


class ConfigTOF{
	static $Appkey="67967952b77041269c4a1b4e5914244a";
	static $SysID=22471;

}

class DES {
	var $key;
	var $iv; //偏移量

	function GetKey($key) {
		$tmp = "--------";
		$key = substr ( $key . $tmp, 0, 8 );
		return $key;
	}
	function DES($key, $iv = 0) {

		//key长度8例如:1234abcd
		$this->key =$this->GetKey($key);

		if ($iv == 0) {
			$this->iv = $key;
		} else {
			$this->iv = $iv; //mcrypt_create_iv ( mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM );
		}



	}

	function encrypt($str) {
		//加密，返回大写十六进制字符串
		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
		$str = $this->pkcs5Pad ( $str, $size );


		return strtoupper ( bin2hex ( mcrypt_cbc ( MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv ) ) );
	}

	function decrypt($str) {
		//解密
		$strBin = $this->hex2bin ( strtolower ( $str ) );

		$str = mcrypt_cbc ( MCRYPT_DES, $this->key, $strBin, MCRYPT_DECRYPT, $this->iv );
		$str = $this->pkcs5Unpad ( $str );
		return $str;
	}

	function hex2bin($hexData) {
		$binData = "";
		for($i = 0; $i < strlen ( $hexData ); $i += 2) {
			$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
		}
		return $binData;
	}

	function pkcs5Pad($text, $blocksize) {
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}

	function pkcs5Unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text ))
			return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
			return false;
		return substr ( $text, 0, - 1 * $pad );
	}


}

/*
 * @brief url封装类，将常用的url请求操作封装在一起
 * */
class URLTOF{

	public function __construct(){

	}

	/**
	 * combineURL
	 * 拼接url
	 * @param string $baseURL   基于的url
	 * @param array  $keysArr   参数列表数组
	 * @return string           返回拼接的url
	 */
	public function combineURL($baseURL,$keysArr){
		$combined = $baseURL."?";
		$valueArr = array();

		foreach($keysArr as $key => $val){
			$valueArr[] = "$key=$val";
		}

		$keyStr = implode("&",$valueArr);
		$combined .= ($keyStr);

		return $combined;
	}

	/**
	 * get_contents
	 * 服务器通过get请求获得内容
	 * @param string $url       请求的url,拼接后的
	 * @return string           请求返回的内容
	 */
	public function get_contents($url) {
		$header=$this->buildHeader();
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 3 );
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		return $response;
	}

	/**
	 * get
	 * get方式请求资源
	 * @param string $url     基于的baseUrl
	 * @param array $keysArr  参数列表数组
	 * @return string         返回的资源内容
	 */
	public function get($url, $keysArr){
		$combined = $this->combineURL($url, $keysArr);
		return $this->get_contents($combined);
	}

	/**
	 * post
	 * post方式请求资源
	 * @param string $url       基于的baseUrl
	 * @param array $keysArr    请求的参数列表
	 * @param int $flag         标志位
	 * @return string           返回的资源内容
	 */
	public function post($url, $keysArr, $flag = 0){
		$header=$this->buildHeader();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 3 );

		curl_setopt($ch, CURLOPT_URL, $url);


		$ret = curl_exec($ch);
		$error = curl_error($ch);

		curl_close($ch);
		return $error ? $error : $ret;

	}

	public function GetKey($key) {
		$tmp = "--------";
		$key = substr ( $key . $tmp, 0, 8 );
		return $key;
	}

	public function buildHeader() {
		$random = rand ( 1000, 9999 );
		$appkey = ConfigTOF::$Appkey;
		$sysid = ConfigTOF::$SysID;

		$timestamp = time ();
		$data = "random" . $random . "timestamp" . $timestamp;

		$key=$this->GetKey($sysid);

		$crypt = new DES ( $key );


		$signature = $crypt->encrypt ($data);


		return array(
			'random:'. $random,
			'timestamp:'.$timestamp,
			'appkey:'.$appkey,
			'signature:'.$signature
		);
	}
}
/*
 * @brief QC类，api外部对象，调用接口全部依赖于此对象
 * */
class QC{
	private $kesArr, $APIMap;
	private $urlUtils;
	/**
	 * _construct
	 *
	 * 构造方法
	 * @access public
	 * @since 5
	 * @param string $access_token  access_token value
	 * @param string $openid        openid value
	 * @return Object QC
	 */
	public function __construct($access_token = "", $openid = ""){


		$this->keysArr = array();

		//初始化APIMap
		/*以下仅为示例，不包含所有API调用，如有需要，请参照《tofwebapi接入文档》，进行添加
		 * 加#表示非必须，无则不传入url(url中不会出现该参数)， "key" => "val" 表示key如果没有定义则使用默认值val
		 * 规则 array( baseUrl, argListArr, method)
		 * baseUrl：在此处增加你要调用的tof webapi的方法地址,参照《tofwebapi接入文档》
		 * argListArr：get或者 post参数列表，参照《tofwebapi接入文档》
		 * method：调用方式
		 *
		 */
		$this->APIMap = array(
			/*                       message                    */
			"SendMail" => array(
				"http://oss.api.tof.oa.com/api/v1/message/SendMail",
				array( "To"=>null, "CC"=>"#","Bcc"=>"#","From"=>null,"Content"=>null,"Title"=>null,"Priority"=>0,"BodyFormat"=>1,"EmailType"=>1,"attachment"=>"#"),
				"POST"
			),
			"SendRTX" => array(
				"http://oss.api.tof.oa.com/api/v1/message/SendRTX",
				array( "Receiver"=>null, "MsgInfo"=>null,"Title"=>null,"Sender"=>null,"Priority"=>0),
				"POST"
			),
			"SendSMS" => array(
				"http://oss.api.tof.oa.com/api/v1/message/SendSMS",
				array( "Receiver"=>null, "MsgInfo"=>null,"Sender"=>null,"Priority"=>0),
				"POST"
			),
			"SendWeiXin" => array(
				"http://oss.api.tof.oa.com/api/v1/message/SendWeiXin",
				array( "Content"=>null, "MsgInfo"=>null,"Sender"=>null,"Priority"=>0),
				"POST"
			),
			
		);

		$this->urlUtils = new URLTOF();

	}

	//调用相应api
	private function _applyAPI($arr, $argsList, $baseUrl, $method){

		$pre = "#";
		$keysArr = $this->keysArr;

		$optionArgList = array();//一些多项选填参数必选一的情形


		foreach ( $argsList as $key => $val ) {
			$tmpKey = $key;
			$tmpVal = $val;

			if (! is_string ( $key )) {
				$tmpKey = $val;

				if (strpos ( $val, $pre ) === 0) {
					$tmpVal = $pre;
					$tmpKey = substr ( $tmpKey, 1 );
					if (preg_match ( "/-(\d$)/", $tmpKey, $res )) {
						$tmpKey = str_replace ( $res [0], "", $tmpKey );
						$optionArgList [$res [1]] [] = $tmpKey;
					}
				} else {
					$tmpVal = null;
				}
			}

			//-----如果没有设置相应的参数
			if (! isset ( $arr [$tmpKey] ) || $arr [$tmpKey] === "") {
				if ($tmpVal == $pre) { //则使用默认的值
					continue;
				} else if ($tmpVal) {
					$arr [$tmpKey] = $tmpVal;
				} else {
					if ($v = $_FILES [$tmpKey]) {

						$filename = dirname ( $v ['tmp_name'] ) . "/" . $v ['name'];
						move_uploaded_file ( $v ['tmp_name'], $filename );
						$arr [$tmpKey] = "@$filename";

					} else {

						return array('Ret'=> -1, 'ErrMsg' => "api调用参数错误,未传入参数".$tmpKey   );
					}
				}
			}

			$keysArr [$tmpKey] = $arr [$tmpKey];
		}
		//检查选填参数必填一的情形
		foreach ( $optionArgList as $val ) {
			$n = 0;
			foreach ( $val as $v ) {
				if (in_array ( $v, array_keys ( $keysArr ) )) {
					$n ++;
				}
			}

			if (! $n) {
				$str = implode ( ",", $val );
				return array('Ret'=> -1, 'ErrMsg' => "api调用参数错误". $str . "必填一个"  );
			}
		}



		if ($method == "POST") {
			$response = $this->urlUtils->post ( $baseUrl, $keysArr, 0 );
		} else if ($method == "GET") {
			$response = $this->urlUtils->get ( $baseUrl, $keysArr );
		}


		return $response;

	}

	/**
	 * _call
	 * 魔术方法，做api调用转发
	 * @param string $name    调用的方法名称
	 * @param array $arg      参数列表数组
	 * @since 5.0
	 * @return array          返加调用结果数组
	 */
	public function __call($name,$arg){

		//如果APIMap不存在相应的api
		if(empty($this->APIMap[$name])){

			return array('Ret'=> -1, 'ErrMsg' => "api调用名称错误,不存在的API: ".$name );
		}

		//从APIMap获取api相应参数
		$baseUrl = $this->APIMap[$name][0];
		$argsList = $this->APIMap[$name][1];
		$contentType= $this->APIMap[$name][2];

		$method = isset($this->APIMap[$name][2]) ? $this->APIMap[$name][2] : "GET";

		if(empty($arg)){
			$arg[0] = null;
		}

        try{
            $responseArr = $this->simple_json_parser($this->_applyAPI($arg[0], $argsList, $baseUrl, $method));
        }
        catch( Exception $e ){
            return array();
        }

		//检查返回ret判断api是否成功调用

		return $responseArr;

	}

	//php 对象到数组转换
	private function objToArr($obj){
		if(!is_object($obj) && !is_array($obj)) {
			return $obj;
		}
		$arr = array();
		foreach($obj as $k => $v){
			$arr[$k] = $this->objToArr($v);
		}
		return $arr;
	}


	//简单实现json到php数组转换功能
	private function simple_json_parser($json){
		$json = str_replace("{","",str_replace("}","", $json));
		$jsonValue = explode(",", $json);
		$arr = array();
		foreach($jsonValue as $v){
			$jValue = explode(":", $v);
			$arr[str_replace('"',"", $jValue[0])] = (str_replace('"', "", $jValue[1]));
		}
		return $arr;
	}


}