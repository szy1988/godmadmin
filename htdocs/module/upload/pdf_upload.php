<?php
/* 
 * img upload
 * by v_zjzzhu @2015/12/07
 * 注意事项
 */

class pdf_upload extends upload{
//    protected $newName = '';
    
    //size单位为K ,key为imgType  get过来的值
    protected $sizeCheck = array(
                                'sOperationManual'=>array('type'=>'pdf,txt','size'=>50),
                            );


    public function __construct($params) {
        $this->file = $_FILES['file'];
        
        $this->newLoc = 'tmp/upload/'.$params['pdfType'].'/'.date('Ym');
        $this->newFile = $this->newLoc.'/'.time().'_'.$_FILES['file']['name'];
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
                            $move_res = move_uploaded_file($this->file["tmp_name"],$this->newFile);   
                            if(!$move_res){
                                $this->showFrameAlert(1, "手册上传失败。");
                            }else{
                                $this->tridentJson(0, '上传成功',array('callBack'=>'afterPDFUpload','pdfUrl'=>HOST.$this->newFile,'pdfName'=>$this->file['name']));
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
        if(!in_array(strtolower(substr($this->file['name'],-3)), explode(',',$this->sizeCheck[$this->m_params['pdfType']]['type']))){
            return "文件格式必须为:{$this->sizeCheck[$this->m_params['pdfType']]['type']}";
        }

        if($this->sizeCheck[$this->m_params['imgType']]['size']>0 && $this->sizeCheck[$this->m_params['pdfType']]['size']<$this->file['size']/1024){
//            var_dump($this->file['size']);
            return "文件大小超过{$this->sizeCheck[$this->m_params['pdfType']]['size']}KB";
        }
        
       return 'pass';  
    }
    
    
}
