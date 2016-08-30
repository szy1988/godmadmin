<?php
class index extends demo{

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
			$condition .= " AND `ip` like '%{$this->m_params['ip']}%' ";
		}

		if(isset($this->m_params['mid']) && $this->m_params['mid'] != ''){
			$condition .= " AND `mid` = {$this->m_params['mid']} ";
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
		$this->tpl->assign('module',$module);
		$this->tpl->assign('total',$num);
		$this->tpl->assign('perpage',$perpage);
		$this->tpl->assign('current',$page);
		$this->tpl->assign('data',$data);
		$this->tpl->display('demo/index.html');
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
			$this->outputJson(-1,'已存在此部署！');
		}
		$this->dao->resources_test->addDeploment($data);
		$this->outputJson(0,'添加成功');
	}

	public function del(){
		$id = $this->m_params['id'];
		if($id == ''){
			$this->outputJson(-1,'缺少必要参数');
		}
		$check = $this->dao->resources_test->deleteDeploment($id);
		if($check){
			$this->outputJson(0,'删除成功',array('retDel'=>array('#source_'.$id)));
		}else{
			$this->outputJson(-1,'删除失败');
		}

	}

	public function reload(){
		$id = $this->m_params['id'];
		if($id == ''){
			$this->outputJson(-1,'缺少必要参数');
		}
		$t = date('Y-m-d H:i:s');
		$data = array('startTime'=>$t);
		$this->dao->resources_test->updateDeploment($id,$data);
		$this->outputJson(0,'重启成功！',array('retRep'=>array("#source_{$id} > .startTime"=>$t)));
	}


	public function upload(){
		header("Access-Control-Allow-Origin: *");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit;
		}
		$this->outputJson(0,'上传成功');
	}

	public function css(){
		$this->tpl->display('demo/index.html');
	}





}	

?>