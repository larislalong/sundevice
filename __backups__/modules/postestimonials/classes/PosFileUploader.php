<?php
class PosFileUploader {
	protected $allowedExtensions = array();
	protected $size_limit;
	protected $module;
	protected $file;
	public $errors;
	public function __construct( $module, $file )
	{
		$this->module = $module;
		$image_types = rtrim($this->module->getParams()->get('type_image'),'|');
		$video_types = $this->module->getParams()->get('type_video');
		$allowedExtensions = $image_types .'|'. $video_types;
		if($allowedExtensions){
		$allowedExtensions = explode('|', $allowedExtensions);
		$allowedExtensions = array_map('strtolower', $allowedExtensions);
		$this->allowedExtensions = $allowedExtensions;
		}
		$size_limit = $this->module->getParams()->get('size_limit', 6);
		$this->size_limit = $size_limit*1024*1024;
		$this->file = $file;
	}

	public function handleUpload() {
		if (!$this->file)
			$this->errors[] = $this->module->l('No files were uploaded.');
			$size = $this->getSize();
		if ($size == 0)
			$this->errors[] = $this->module->l('File is empty');
		if ($size > $this->size_limit)
			$this->errors[] = $this->module->l('File is too large');
			$ext = $this->getExt();
		 // if (!$ext)
		 // $this->errors[] = $this->module->l('File has an invalid extension, it should be one of ').implode(',', $this->allowedExtensions).'.';
		if ($this->allowedExtensions && !in_array($ext, $this->allowedExtensions))
			$this->errors[] = $this->module->l('File has an invalid extension, it should be one of').implode(',', $this->allowedExtensions).'.';
		if(!sizeof($this->errors))
			return $this->upload();
		else
			return $this->errors ;
	}

	public function upload(){
		$upload_path = _PS_IMG_DIR_.'postestimonial/';
		if(!is_dir($upload_path))
			mkdir($upload_path, 0777);
			$refile_name = rand(0,1000).'-'.strtolower($this->file['name']);
			$type = strtolower($this->getExt());
			$result = false;
		if(move_uploaded_file($this->file["tmp_name"], $upload_path . $refile_name)){
			$result = array(
			'status' => 'ok',
			'name' => $refile_name,
			'type' => $type,
			);
		}else{
			$this->errors[] = $this->module->l('Can not move file!');
		}
		return $result;
	}

	public function getExt(){
	$these = explode('.', $this->file['name']);
	if($these)
	return end($these);
	return;
	}

	public function getSize(){
		return $this->file['size'];
	}


}
