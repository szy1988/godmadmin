<?php
/* 
 * img upload
 * by v_zjzzhu @2015/12/07
 * 注意事项
 */

class img_upload extends upload{
//    protected $newName = '';
    
    //size单位为K ,key为imgType  get过来的值
    protected $sizeCheck = array(
                                'sAppLogo'=>array('type'=>'image/jpeg,image/pjpeg,image/png','width'=>128,'height'=>128,'size'=>50),
                                'sPublicityImg'=>array('type'=>'image/jpeg,image/pjpeg,image/png','width'=>104,'height'=>104,'size'=>50),
                                'sCgiPublicImg'=>array('type'=>'image/jpeg,image/pjpeg,image/png','width'=>280,'height'=>158,'size'=>50),
                                'sImgUrl'=>array('type'=>'image/jpeg,image/pjpeg,image/png','width'=>276,'height'=>180,'size'=>200),
                            );


    public function __construct($params) {
        $this->file = $_FILES['file'];
        
        $this->newLoc = 'tmp/upload/'.$params['imgType'].'/'.date('Ym');
        $this->newFile = $this->newLoc.'/'.time().'_'.md5($_FILES['file']['name']).substr($_FILES['file']['name'], -4);
        parent::__construct($params);
    }

    public function index(){
//        var_dump(filesize($this->file['tmp_name']));
            if ($this->file["error"] == 0){
                    $checkRes = $this->uploadSizeCheck();
                        if($checkRes == 'pass'){
                            if(!is_dir($this->newLoc)){
                                mkdir($this->newLoc,0777,TRUE);
                            }
                            $move_res = move_uploaded_file($_FILES["file"]["tmp_name"],$this->newFile);   
                            if($move_res){
                                $this->tridentJson(0, '上传成功',array('callBack'=>'afterUpload','imgUrl'=>HOST.$this->newFile,'place'=>$this->m_params['place'],'imgType'=>$this->m_params['imgType']));
                            }else{
                                $this->tridentJson(1, '上传失败');
                            }
                        }else{
                            $this->tridentJson(1, $checkRes);
                        }
            }else{ 
                $this->tridentJson(1, $_FILES["file"]["error"]);
            }
        
        
    }
    
    protected function uploadSizeCheck(){
//        var_dump(expload(',',$this->file['type']));
        if(!in_array($this->file['type'], explode(',',$this->sizeCheck[$this->m_params['imgType']]['type']))){
            return "图片格式必须为{$this->sizeCheck[$this->m_params['imgType']]['type']}";
        }
        $imgsize = getimagesize($this->file['tmp_name']);
        if($imgsize[0]!=$this->sizeCheck[$this->m_params['imgType']]['width'] || $imgsize[1]!= $this->sizeCheck[$this->m_params['imgType']]['height']){
            return "图片尺寸必须为{$this->sizeCheck[$this->m_params['imgType']]['width']}*{$this->sizeCheck[$this->m_params['imgType']]['height']}";
        }
        if($this->sizeCheck[$this->m_params['imgType']]['size']>0 && $this->sizeCheck[$this->m_params['imgType']]['size']<$this->file['size']/1024){
//            var_dump($this->file['size']);
            return "图片大小超过{$this->sizeCheck[$this->m_params['imgType']]['size']}KB";
        }
        
       return 'pass';  
    }
    
    
}
