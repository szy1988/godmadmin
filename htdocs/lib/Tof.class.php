<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rickyzeng
 * Date: 13-12-6
 * Time: ����4:10
 * To change this template use File | Settings | File Templates.
 */


class ConfigTOF{
	static $Appkey="67967952b77041269c4a1b4e5914244a";
	static $SysID=22471;

}

class DES {
	var $key;
	var $iv; //ƫ����

	function GetKey($key) {
		$tmp = "--------";
		$key = substr ( $key . $tmp, 0, 8 );
		return $key;
	}
	function DES($key, $iv = 0) {

		//key����8����:1234abcd
		$this->key =$this->GetKey($key);

		if ($iv == 0) {
			$this->iv = $key;
		} else {
			$this->iv = $iv; //mcrypt_create_iv ( mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM );
		}



	}

	function encrypt($str) {
		//���ܣ����ش�дʮ�������ַ���
		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
		$str = $this->pkcs5Pad ( $str, $size );


		return strtoupper ( bin2hex ( mcrypt_cbc ( MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv ) ) );
	}

	function decrypt($str) {
		//����
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
 * @brief url��װ�࣬�����õ�url���������װ��һ��
 * */
class URLTOF{

	public function __construct(){

	}

	/**
	 * combineURL
	 * ƴ��url
	 * @param string $baseURL   ���ڵ�url
	 * @param array  $keysArr   �����б�����
	 * @return string           ����ƴ�ӵ�url
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
	 * ������ͨ��get����������
	 * @param string $url       �����url,ƴ�Ӻ��
	 * @return string           ���󷵻ص�����
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
	 * get��ʽ������Դ
	 * @param string $url     ���ڵ�baseUrl
	 * @param array $keysArr  �����б�����
	 * @return string         ���ص���Դ����
	 */
	public function get($url, $keysArr){
		$combined = $this->combineURL($url, $keysArr);
		return $this->get_contents($combined);
	}

	/**
	 * post
	 * post��ʽ������Դ
	 * @param string $url       ���ڵ�baseUrl
	 * @param array $keysArr    ����Ĳ����б�
	 * @param int $flag         ��־λ
	 * @return string           ���ص���Դ����
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
 * @brief QC�࣬api�ⲿ���󣬵��ýӿ�ȫ�������ڴ˶���
 * */
class QC{
	private $kesArr, $APIMap;
	private $urlUtils;
	/**
	 * _construct
	 *
	 * ���췽��
	 * @access public
	 * @since 5
	 * @param string $access_token  access_token value
	 * @param string $openid        openid value
	 * @return Object QC
	 */
	public function __construct($access_token = "", $openid = ""){


		$this->keysArr = array();

		//��ʼ��APIMap
		/*���½�Ϊʾ��������������API���ã�������Ҫ������ա�tofwebapi�����ĵ������������
		 * ��#��ʾ�Ǳ��룬���򲻴���url(url�в�����ָò���)�� "key" => "val" ��ʾkey���û�ж�����ʹ��Ĭ��ֵval
		 * ���� array( baseUrl, argListArr, method)
		 * baseUrl���ڴ˴�������Ҫ���õ�tof webapi�ķ�����ַ,���ա�tofwebapi�����ĵ���
		 * argListArr��get���� post�����б����ա�tofwebapi�����ĵ���
		 * method�����÷�ʽ
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

	//������Ӧapi
	private function _applyAPI($arr, $argsList, $baseUrl, $method){

		$pre = "#";
		$keysArr = $this->keysArr;

		$optionArgList = array();//һЩ����ѡ�������ѡһ������


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

			//-----���û��������Ӧ�Ĳ���
			if (! isset ( $arr [$tmpKey] ) || $arr [$tmpKey] === "") {
				if ($tmpVal == $pre) { //��ʹ��Ĭ�ϵ�ֵ
					continue;
				} else if ($tmpVal) {
					$arr [$tmpKey] = $tmpVal;
				} else {
					if ($v = $_FILES [$tmpKey]) {

						$filename = dirname ( $v ['tmp_name'] ) . "/" . $v ['name'];
						move_uploaded_file ( $v ['tmp_name'], $filename );
						$arr [$tmpKey] = "@$filename";

					} else {

						return array('Ret'=> -1, 'ErrMsg' => "api���ò�������,δ�������".$tmpKey   );
					}
				}
			}

			$keysArr [$tmpKey] = $arr [$tmpKey];
		}
		//���ѡ���������һ������
		foreach ( $optionArgList as $val ) {
			$n = 0;
			foreach ( $val as $v ) {
				if (in_array ( $v, array_keys ( $keysArr ) )) {
					$n ++;
				}
			}

			if (! $n) {
				$str = implode ( ",", $val );
				return array('Ret'=> -1, 'ErrMsg' => "api���ò�������". $str . "����һ��"  );
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
	 * ħ����������api����ת��
	 * @param string $name    ���õķ�������
	 * @param array $arg      �����б�����
	 * @since 5.0
	 * @return array          ���ӵ��ý������
	 */
	public function __call($name,$arg){

		//���APIMap��������Ӧ��api
		if(empty($this->APIMap[$name])){

			return array('Ret'=> -1, 'ErrMsg' => "api�������ƴ���,�����ڵ�API: ".$name );
		}

		//��APIMap��ȡapi��Ӧ����
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

		//��鷵��ret�ж�api�Ƿ�ɹ�����

		return $responseArr;

	}

	//php ��������ת��
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


	//��ʵ��json��php����ת������
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