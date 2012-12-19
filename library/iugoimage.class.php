<?php

class IUGOImage
{
    const Max_file = MAX_UPLOAD_FILESIZE; 	// Maximum file size in MB
    const Max_width = IMG_MAX_WIDTH;		// Max width allowed for the large image

    protected $original_path;
    protected $original_name;
    protected $original_width;
    protected $original_height;
    protected $user_filename;

    protected $upload_dir;
    protected $thumb_image_name;
    protected $allowed_image_ext;
    protected $allowed_image_types = array('image/pjpeg'=>"jpg",
                                           'image/jpeg'=>"jpg",
                                           'image/jpg'=>"jpg",
                                           'image/png'=>"png",
                                           'image/x-png'=>"png",
                                           'image/gif'=>"gif");
    protected $image_ext;

    /**
    * CONSTRUCTOR de IUGOImage
    */
    public function IUGOImage($original_path)
    {
        $this->upload_dir = UPLOAD_DIR;
        $this->original_path = $original_path;

        $this->allowed_image_ext = array_unique($this->allowed_image_types); // do not change this
        $image_ext = "";	// initialise variable, do not change this.
        foreach ($allowed_image_ext as $mime_type => $ext) {
            $this->image_ext.= strtoupper($ext)." ";
        }

        //Create the upload directory with the right permissions if it doesn't exist
        if(!is_dir($this->upload_dir)){
            mkdir($this->upload_dir, 0777);
            chmod($this->upload_dir, 0777);
        }
        if(!is_dir($this->original_path)){
            mkdir($this->original_path, 0777);
            chmod($this->original_path, 0777);
        }
    }

    public function resizeImage($image,$width,$height,$scale) {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        
        switch($imageType) {
            case "image/gif":
                $source=imagecreatefromgif($image);
                break;
            case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                $source=imagecreatefromjpeg($image);
                break;
            case "image/png":
                case "image/x-png":
                $source=imagecreatefrompng($image);
                imageAlphaBlending($newImage, false); 
                imageSaveAlpha($newImage, true);     
                break;
            }

        imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

        switch($imageType) {
                case "image/gif":
                    imagegif($newImage,$image);
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    imagejpeg($newImage,$image,100);
                break;
                case "image/png":
                case "image/x-png":
                    imagepng($newImage,$image);
                break;
        }

        chmod($image, 0777);
        return $image;
    }

    public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        switch($imageType) {
            case "image/gif":
                $source=imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source=imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source=imagecreatefrompng($image);
                imageAlphaBlending($newImage, false); 
                imageSaveAlpha($newImage, true); 
               break;
        }

        imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

        switch($imageType) {
            case "image/gif":
                imagegif($newImage,$thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage,$thumb_image_name,100);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage,$thumb_image_name);
                break;
        }
        
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    public function uploadImage($files,$forced_name='') {
        //Get the file information
        $userfile_name = $files['image']['name'];
        $userfile_tmp = $files['image']['tmp_name'];
        $userfile_size = $files['image']['size'];
        $userfile_type = $files['image']['type'];
        $filename = ($forced_name == '')? rand(1000000, 9999999) : $forced_name;
        $file_ext = strtolower(substr($userfile_name, strrpos($userfile_name, '.') + 1));
        $filename.= ".".$file_ext;
        $this->original_name = $filename;
        $this->user_filename = $userfile_name;

        //Only process if the file is a JPG, PNG or GIF and below the allowed limit
        if((!empty($files["image"])) && ($files['image']['error'] == 0)) {

            foreach ($this->allowed_image_types as $mime_type => $ext) {
                //loop through the specified image types and if they match the extension then break out
                //everything is ok so go and check file size
                if ($file_ext==$ext && $userfile_type==$mime_type){
                    $error = "";
                    break;
                }else{
                    $error = "Only <strong>".$this->image_ext."</strong> images accepted for upload<br />";
                }
            }
            //check if the file size is above the allowed limit
            if ($userfile_size > (self::Max_file*1048576)) {
                $error.= "Images must be under ".self::Max_file."MB in size";
            }
            
        }else{
                $error = "Select an image for upload";
        }
        //Everything is ok, so we can upload the image.
        if (strlen($error)==0){
            if (isset($files['image']['name'])){
                
                move_uploaded_file($userfile_tmp, $this->original_path.$this->original_name);
                chmod( $this->original_path.$this->original_name, 0777);

                $width = $this->getWidth($this->original_path.$this->original_name);
                $height = $this->getHeight($this->original_path.$this->original_name);

                //Scale the image if it is greater than the width set above
                if ($width > self::Max_width){
                    $scale = self::Max_width/$width;
                    $uploaded = $this->resizeImage($this->original_path.$this->original_name,$width,$height,$scale);
                }else{
                    $scale = 1;
                    $uploaded = $this->resizeImage($this->original_path.$this->original_name,$width,$height,$scale);
                }
                $this->original_width = ceil($width * $scale);
                $this->original_height = ceil($height * $scale);
                $this->original_name = $filename;
                return array('result'=>true);
            }
        }else{
            return array('result'=>false,'error'=>$error);
        }
    }
    
    public function uploadImage2($files,$forced_name='') {
        //Get the file information
        $userfile_name = $files['image2']['name'];
        $userfile_tmp = $files['image2']['tmp_name'];
        $userfile_size = $files['image2']['size'];
        $userfile_type = $files['image2']['type'];
        $filename = ($forced_name == '')? rand(1000000, 9999999) : $forced_name;
        $file_ext = strtolower(substr($userfile_name, strrpos($userfile_name, '.') + 1));
        $filename.= ".".$file_ext;
        $this->original_name = $filename;
        $this->user_filename = $userfile_name;

        //Only process if the file is a JPG, PNG or GIF and below the allowed limit
        if((!empty($files["image"])) && ($files['image2']['error'] == 0)) {

            foreach ($this->allowed_image_types as $mime_type => $ext) {
                //loop through the specified image types and if they match the extension then break out
                //everything is ok so go and check file size
                if ($file_ext==$ext && $userfile_type==$mime_type){
                    $error = "";
                    break;
                }else{
                    $error = "Only <strong>".$this->image_ext."</strong> images accepted for upload<br />";
                }
            }
            //check if the file size is above the allowed limit
            if ($userfile_size > (self::Max_file*1048576)) {
                $error.= "Images must be under ".self::Max_file."MB in size";
            }
            
        }else{
                $error = "Select an image for upload";
        }
        //Everything is ok, so we can upload the image.
        if (strlen($error)==0){
            if (isset($files['image2']['name'])){
                
                move_uploaded_file($userfile_tmp, $this->original_path.$this->original_name);
                chmod( $this->original_path.$this->original_name, 0777);

                $width = $this->getWidth($this->original_path.$this->original_name);
                $height = $this->getHeight($this->original_path.$this->original_name);

                //Scale the image if it is greater than the width set above
                if ($width > self::Max_width){
                    $scale = self::Max_width/$width;
                    $uploaded = $this->resizeImage($this->original_path.$this->original_name,$width,$height,$scale);
                }else{
                    $scale = 1;
                    $uploaded = $this->resizeImage($this->original_path.$this->original_name,$width,$height,$scale);
                }
                $this->original_width = ceil($width * $scale);
                $this->original_height = ceil($height * $scale);
                $this->original_name = $filename;
                return array('result'=>true);
            }
        }else{
            return array('result'=>false,'error'=>$error);
        }
    }

    /**
     * Creates the thumbnail
     * @param Int $x1 x1 position
     * @param Int $x2 x2 position
     * @param Int $y1 y1 position
     * @param Int $y2 y2 position
     * @param Int $w width
     * @param Int $h height
     * @param Int $thumb_w desired width for the thumb
     */
    function createThumbnail($copy_path,$x1,$x2,$y1,$y2,$w,$h,$thumb_w) {
	//Scale the image to the thumb_width set above
	$scale = $thumb_w/$w;
	$cropped = $this->resizeThumbnailImage($copy_path.$this->original_name, $this->original_path.$this->original_name,$w,$h,$x1,$y1,$scale);
	//header("location:".$_SERVER["PHP_SELF"]);
	return true;
    }

    /**
     * Duplica la imagen
     * @param <type> $original_path
     * @param <type> $copy_path
     * @param <type> $filename
     */
    function duplicateImage($original_path,$copy_path) {
        if(!is_dir($copy_path)){
            mkdir($copy_path, 0777);
            chmod($copy_path, 0777);
        }
        copy($original_path.$this->getOriginal_name(),$copy_path.$this->getOriginal_name());
        chmod($copy_path.$this->getOriginal_name(), 0777);
    }

    public function getHeight($image) {
        $size = getimagesize($image);
        $height = $size[1];
        return $height;
    }

    public function getWidth($image) {
        $size = getimagesize($image);
        $width = $size[0];
        return $width;
    }

    function getOriginal_width(){
        return $this->original_width;
    }

    function getOriginal_height(){
        return $this->original_height;
    }

    function setOriginal_name($name) {
        $this->original_name = $name;
    }

    function getOriginal_name(){
        return $this->original_name;
    }

    function getUser_filename(){
        return $this->user_filename;
    }

//    function getOriginal_path(){
//        return $this->original_path;
//    }
//
//    function getOriginal_relative_src(){
//        $return = str_replace(ABS_PATH, "/".APP_DIR, $this->original_path);
//        $return.= $this->getOriginal_name();
//        return $return;
//    }

}