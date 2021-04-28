

<?php 
class UploadImage 
{
    private $target_dir="";
    private $target_file="";
    private $fileType="";
    private $upload_status="";
    private $fileName="";
    function __construct($dir,$fileToUpload)
    {
        $this->target_dir = $dir;
        $this->target_file = $this->target_dir . basename($_FILES[$fileToUpload]["name"]);
        $this->fileType = strtoLower(pathinfo($this->target_file, PATHINFO_EXTENSION));
        $this->isValidImage($fileToUpload);
        $this->isExisting();
        $this->isValidSize($fileToUpload);
        $this->isValidFormat();
        
    
    }
     function getStatus(){
    	return $this->upload_status;
    }

     function setStatus($status){
    	$this->upload_status = $status;
    }

    function getFileName(){
    	return $this->fileName;
    }

    function setFileName($filename){
    	$this->fileName = $filename;

    }

     function isValidImage($fileToUpload){
        $imageSize = getimagesize($_FILES[$fileToUpload]["tmp_name"]);
        if ($imageSize !== false) {
            $this->setStatus(true);
        }else{
        	$this->setStatus(false);
        }
        
    }

     function isExisting(){
        if (file_exists($this->target_file)) {
            $this->setStatus(false);
        }
    }

     function isValidSize($fileToUpload){
        if ($_FILES[$fileToUpload]["size"] > 500000) {
           
            $this->setStatus(false);
        }

    }

     function isValidFormat(){
        if ($this->$fileType != "jpg" && $this->fileType != "png" && $this->fileType != "gif") {
            $this->setStatus(false);
        }
    }

    function uploadFile($fileToUpload){
    	if ($this->upload_status == false) {
		    echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], $this->target_file)) {
		        return true;
		    } else {
		        return false;
		    }
		}
	}
}
 ?>

<?php
	/*$imageUpload ="";
	$imageUpload = new UploadImage('upload/','uploadfile');
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		
		echo  "<script type='text/javascript'> alert('".$imageUpload->uploadfile('uploadfile')."');</script> "; 
	}

	*/
	?>

