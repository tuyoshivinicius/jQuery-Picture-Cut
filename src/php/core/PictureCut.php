<?php
require_once('TVimageManipulation.php');

class PictureCut{

	private $request;
	private $currentFile;
	private $currentFileSize;
	private $currentHeight;
	private $currentWidth;
	private $enableResize;
	private $errorMessage;
	private $fileExtension;
	private $fileName;
	private $currentFileName;
	private $fileStream;	
	private $folderPath;
	private $imageNameRandom;
	private $maximumSize;
	private $enableMaximumSize;
	private $minimumHeightToResize;
	private $minimumWidthToResize;	
	private $TVimageManipulation;
	private static $instance = NULL;
	private $status;
	private $toCropImgX;
	private $toCropImgY;
	private $toCropImgW;
	private $toCropImgH;

	public function getErrorMessage() { return $this->errorMessage; } 
	public function getFileNewName() { return $this->currentFileName; } 
	public function getCurrentWidth() { return $this->currentWidth; } 
	public function getCurrentHeight() { return $this->currentHeight; } 
	public function getCurrentFileSize() { 
		if($this->TVimageManipulation == NULL){
			throw new Exception("Image not instantiated");
		} else {
			return $this->TVimageManipulation->getCurrentFileSize();
		}
	} 


	private function __construct(){
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(isset($_POST["request"])){
				if($_POST["request"] == "upload"){

					$uploadValidations = array(
						"request"               =>array("required"),
						"inputOfFile"           =>array("required"),
						"enableResize"          =>array("required"),
						"minimumWidthToResize"  =>array("int"),
						"minimumHeightToResize" =>array("int"),
						"folderOnServer"        =>array("required"),
						"imageNameRandom"       =>array("required"),
						"maximumSize"           =>array("int"),
						"enableMaximumSize"		=>array("required")
					);

					if($this->validation($uploadValidations, $_POST)){
						if(isset($_FILES[$_POST["inputOfFile"]])){							
							$this->populateFromArray($_POST);							
							$this->populateFileFromStream($_FILES[$_POST["inputOfFile"]]);
						} else {
							throw new Exception($_POST["inputOfFile"]." file variable is required");
						}
					}

				} else if($_POST["request"] == "crop"){

					$cropValidations = array(
						"folderOnServer"    => array("required"),
						"inputOfFile"       => array("required"),
						"maximumSize"       => array("int"),
						"enableMaximumSize" => array("required"),
						"toCropImgX"		=> array("int"),
						"toCropImgX"		=> array("int"),
						"toCropImgW"		=> array("int"),
						"toCropImgH"		=> array("int")
					);

					if($this->validation($cropValidations, $_POST)){
						$this->populateFromArray($_POST);
						$this->currentFile = $this->folderPath.$this->currentFileName;
					}

				} else {
					throw new Exception("request variable value is invalid");	
				}
			} else {
				throw new Exception("request variable does not exist");
			}
		} else {
			throw new Exception("request method is invalid");
		}
	}

	private function populateFromArray($data){
		foreach ($data as $key => $value) {
		    $this->$key = $value;
		}
		$this->folderPath = $_SERVER["DOCUMENT_ROOT"] . $this->folderOnServer;
		$this->maximumSize				= $this->maximumSize * 1024;
	}

	private function populateFileFromStream($fileStream){
		$this->fileStream = $fileStream;
		$this->fileName   = $fileStream["name"];
		$tmp              = explode('.', $this->fileName);
		$file_extension   = end($tmp);
		$this->fileType   = $file_extension;

		if($this->imageNameRandom == "true"){

			$newName = dechex(round(rand(0,999999999999999))).".".$this->fileType;
			while(file_exists($this->folderPath.$newName))
			{
				$newName = dechex(round(rand(0,999999999999999))).".".$this->fileType;
			}
			$this->currentFileName = $newName;
		} else {
			$this->currentFileName = $this->fileName;
		}

		$this->currentFile = $this->folderPath.$this->currentFileName;
	}

	private function validation($rules, $data){
		foreach ($rules as $key => $value) {
			if(count($rules[$key])>0){
				if(isset($data[$key])){
					foreach ($rules[$key] as $rule) {
						if($rule == "int"){
							if(!is_numeric($data[$key])){
								throw new Exception($key." variable is not ".$rule);
							}
						}
					}
				} else {
					throw new Exception($key." variable is required");
				}
			}
		}
		return true;
	}

	public static function createSingleton(){
		if(self::$instance == NULL){
			self::$instance = (new PictureCut());
		}
		return self::$instance;
	}

	public function upload(){
								
		try {								
				if (move_uploaded_file($this->fileStream['tmp_name'], $this->currentFile)){
					
					$this->TVimageManipulation = new TVimageManipulation($this->currentFile);
					$this->currentWidth        = $this->TVimageManipulation->getCurrentWidth();
					$this->currentHeight       = $this->TVimageManipulation->getCurrentHeight();
					$this->currentFileSize     = $this->TVimageManipulation->getCurrentFilesize();
						
					if($this->enableResize == "true"){

						if(($this->currentWidth > $this->minimumWidthToResize) || ($this->currentHeight > $this->minimumHeightToResize))
						{
							$this->TVimageManipulation->resize($this->minimumWidthToResize, $this->minimumHeightToResize);
							$this->TVimageManipulation->save($this->currentFile);

							$this->currentWidth		= $this->TVimageManipulation->getCurrentWidth();
							$this->currentHeight	= $this->TVimageManipulation->getCurrentHeight();
							$this->currentFileSize	= $this->TVimageManipulation->getCurrentFilesize();
						}
					} else {

						if(($this->currentWidth > $this->minimumWidthToResize || $this->currentHeight > $this->minimumHeightToResize) && ($this->enableMaximumSize == "false"))
						{
							@unlink($this->currentFile);
							$this->errorMessage = "The maximum resolution is defined ".$this->minimumWidthToResize." x ".$this->minimumHeightToResize;
							
							$this->status = false;
							return $this->status;
						}
					}

					if($this->enableMaximumSize == "true"){
						if(preg_match('/(jpg|jpeg)/i', $this->fileType)){
							$this->manipulateSize();
						}
					}
					
					$this->status = true;
					return $this->status;
					
				} else {
					$this->status = false;
					return $this->status;
				}

		} catch (Exception $e) {
			throw $e;
		}
	}

	public function manipulateSize(){
		
		$Quality=array(
			"Current"	=>100,
			"Min"		=>65,
			"Step"		=>2.5
		);
		
		$Resize=array(
			"Percent"		=>2.5,
			"CurrentLoop"	=>0,
			"TotalLoop"	 	=>10
		);				
		
		while($this->currentFileSize >= $this->maximumSize)
		{
			if($Quality["Current"] >= $Quality["Min"]){
				$this->TVimageManipulation->save($this->currentFile, ($Quality["Current"]));
				$Quality["Current"] -= $Quality["Step"];
			} else {
				if($Resize["CurrentLoop"] <= $Resize["TotalLoop"]){
					$this->TVimageManipulation->resizePercent((100-$Resize["Percent"]));
					$this->TVimageManipulation->save($this->currentFile);
					$Quality["Current"] = 100;
					$Resize["CurrentLoop"]++;
				}
			}
			$this->currentFileSize	=$this->TVimageManipulation->getCurrentFilesize();
		}		
		
		$this->currentWidth  = $this->TVimageManipulation->getCurrentWidth();
		$this->currentHeight = $this->TVimageManipulation->getCurrentHeight();
	}

	public function crop(){
		try
		{			
				$this->TVimageManipulation = new TVimageManipulation($this->currentFile);
				$this->TVimageManipulation->crop($this->toCropImgX,	$this->toCropImgY,	$this->toCropImgW,	$this->toCropImgH);
				$this->TVimageManipulation->save($this->currentFile);
				$this->currentFileSize     =$this->TVimageManipulation->getCurrentFilesize();			
			
			if($this->enableMaximumSize == "true"){
				if(preg_match('/(JPG|jpg|JPEG|jpeg)/i',$this->fileType)){
					$this->ManipularSize();
				}
			}

			$this->currentWidth    = $this->TVimageManipulation->getCurrentWidth();
			$this->currentHeight   = $this->TVimageManipulation->getCurrentHeight();
			$this->currentFileSize =$this->TVimageManipulation->getCurrentFilesize();

			return true;
		}catch(Exception $e){
			return false;
		}
	}



	public function toJson(){
		return json_encode(array(
			"status"          => $this->status,
			"currentFileName" => $this->currentFileName,
			"currentWidth"    => $this->currentWidth,
			"currentHeight"   => $this->currentHeight,
			"currentFileSize" => $this->currentFileSize,
			"request"         => $this->request
		));
	}

	public function exceptionsToJson(){
		return json_encode(array(
				"status"          => $this->status,
				"request"         => $this->request,
				"errorMessage"    => $this->errorMessage
			));
	}




}