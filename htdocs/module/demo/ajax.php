<?php
class ajax extends demo{

	public function __construct($params){
		parent::__construct($params);
	}

	public function index(){
		$this->tpl->display('demo/index.html');
	}

	public function js(){
		$page = isset($this->m_params['page'])?$this->m_params['page']:1;
		$perpage = isset($this->m_params['perpage'])?$this->m_params['perpage']:10;
		$start = ($page - 1)*$perpage;
		$condition = '';
		if(isset($this->m_params['ip']) && $this->m_params['ip'] != ''){
			$ip = $this->m_params['ip'];
			$condition .= " AND `ip` like '%{$this->m_params['ip']}%' ";
		}else{
			$ip = '';
		}

		if(isset($this->m_params['mid']) && $this->m_params['mid'] != ''){
			$mid = $this->m_params['mid'];
			$condition .= " AND `mid` = {$this->m_params['mid']} ";
		}else{
			$mid = '';
		}
		
		if(isset($this->m_params['order']) && $this->m_params['order'] != ''){
			$direction = (isset($this->m_params['direction'])&&$this->m_params['direction'] != ''&&in_array($this->m_params['direction'], array('asc','desc')))?$this->m_params['direction']:'desc';
			$order = $this->m_params['order'];
			$condition .= " order by `{$this->m_params['order']}` {$direction} ";
		}else{
			$direction = '';
			$order = '';
		}

		$condition_limit = $condition. " limit {$start},{$perpage}";

		$data = $this->dao->resources_test->getAllDeployment($condition_limit);
		$module = $this->dao->resources_test->getAllModule();
		$moduleMap = array();
		foreach ($module as $key => $value) {
			$moduleMap[$value['id']] = $value['name'];
		}
		foreach ($data as $key => $value) {
			$data[$key]['module_cn'] = $moduleMap[$value['mid']];
		}
		$num = $this->dao->resources_test->getAllDeploymentCount($condition);

		$rt = array('module'=>$module,'perpage'=>$perpage,'page'=>$page,'total'=>$num,'deploment'=>$data,'search'=>array('mid'=>$mid,'ip'=>$ip),'sort'=>array('order'=>$order,'direction'=>$direction));

		$this->outputJson(0,'success',array('data'=>$rt),'callback');
	}

	public function trident(){
		$rt = array('name'=>'trident','version'=>'1.0','pushTime'=>'2016-01-01','file'=>array(array('name'=>'tpl','url'=>'http://ossweb-img.qq.com/images/ui/trident/js/tpl.js'),array('name'=>'form','url'=>'http://ossweb-img.qq.com/images/ui/trident/js/form.js')));
		$this->outputJson(0,'success',array('data'=>$rt),'callback');
	}

	public function add(){
		$data = $this->dao->resources_test->getAllModule();
		$this->tpl->assign('data',$data);
		$this->tpl->display('demo/add.html');
	}

	public function doAdd(){
		$data = array();
		$data['ip'] = $this->m_params['ip'];
		$data['mid'] = $this->m_params['mid'];
		$data['model'] = $this->m_params['model'];
		$check = $this->dao->resources_test->checkDeploment($data['mid'],$data['ip']);
		if($check){
			$this->outputJson(-1,'已存在此部署！',array(),'callback');
		}
		$this->dao->resources_test->addDeploment($data);
		$this->outputJson(0,'添加成功',array(),'callback');
	}

	public function del(){
		$id = $this->m_params['id'];
		if($id == ''){
			$this->outputJson(-1,'缺少必要参数',array(),'callback');
		}
		$check = $this->dao->resources_test->deleteDeploment($id);
		if($check){
			$this->outputJson(0,'删除成功',array('retDel'=>array('#source_'.$id)),'callback');
		}else{
			$this->outputJson(-1,'删除失败',array(),'callback');
		}
	}

	public function delJsonp(){
		echo 'callback('.json_encode(array('retCode'=>0,'retInfo'=>'删除成功')).');';
	}

	public function delScript(){
		$this->outputJson(0,'删除成功',array(),'callback');
	}


	public function reload(){
		$id = $this->m_params['id'];
		if($id == ''){
			$this->outputJson(-1,'缺少必要参数',array(),'callback');
		}
		$t = date('Y-m-d H:i:s');
		$data = array('startTime'=>$t);
		$this->dao->resources_test->updateDeploment($id,$data);
		//exit('showA('.json_encode(array('retCode'=>0,'retInfo'=>'重启成功')).');');
		 $this->outputJson(0,'重启成功！',array('retRep'=>array("#source_{$id} > .startTime"=>$t)),'callback');
	}


	public function upload(){
		header("Access-Control-Allow-Origin: *");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit;
		}
		$this->outputJson(0,'上传成功');
	}

	public function uploads(){
		header("Access-Control-Allow-Origin: *");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit;
		}
		echo '{"retCode":0,"retInfo":"success","callBack":"afterUpload","retRep":{"#showMyPic":"<img src=\"http:\/\/gpm.oa.com\/trident\/api\/static\/t.jpg\"\/>"}}';
	}


	public function css(){
		$this->tpl->display('demo/index.html');
	}





}	

?>