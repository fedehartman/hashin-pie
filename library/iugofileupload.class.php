<?php

if (!defined('UPLOAD_ERR_OK'))
    define('UPLOAD_ERR_OK', 0);

if (!defined('UPLOAD_ERR_INI_SIZE'))
    define('UPLOAD_ERR_INI_SIZE', 1);

if (!defined('UPLOAD_ERR_FORM_SIZE'))
    define('UPLOAD_ERR_FORM_SIZE', 2);

if (!defined('UPLOAD_ERR_PARTIAL'))
    define('UPLOAD_ERR_PARTIAL', 3);

if (!defined('UPLOAD_ERR_NO_FILE'))
    define('UPLOAD_ERR_NO_FILE', 4);

if (!defined('UPLOAD_ERR_NO_TMP_DIR'))
    define('UPLOAD_ERR_NO_TMP_DIR', 6);

if (!defined('UPLOAD_ERR_CANT_WRITE'))
    define('UPLOAD_ERR_CANT_WRITE', 7);

define('FILE_UPLOAD_ERR_PATH', 10);
define('FILE_UPLOAD_ERR_SIZE', 11);
define('FILE_UPLOAD_ERR_TYPE', 12);
define('FILE_UPLOAD_ERR_INVALID', 13);

class IUGOFileUpload {

    var $path;

    var $_allowed;
    var $_max_size;

    var $_is_error; //(bool)
    var $_errno;

    function IUGOFileUpload() {
        $this->_allowed_mime = array();
        $this->_allowed_ext  = array();
        $this->_is_error     = false;
        $this->_errno        = 0;
        $this->path          = '';
        $this->_max_size     = 100000;
    }

    function allow($type) {

        switch ($type) {
            case 'images' :
                $this->_allowed['image/gif']  = array('gif');
                $this->_allowed['image/png']  = array('png');
                $this->_allowed['image/jpeg'] = array('jpg', 'jpeg');
                $this->_allowed['image/pjpeg'] = $this->_allowed['image/jpeg'];
                break;
            case 'text' :
                $this->_allowed['plain/text'] = array('txt');
                $this->_allowed['text/plain'] = $this->_allowed['plain/text'];
                break;
            case 'pdf' :
                $this->_allowed['application/pdf'] = array('pdf');
                break;
        }
    }

    function add_allowed($mime, $ext) {
        $this->_allowed[$mime] = $ext;
    }

    function get_extensions() {
        $all_ext = array();
        foreach ($this->_allowed as $exts)
            foreach($exts as $ext)
                $all_ext[] = $ext;
        return $all_ext;
    }

    function upload($file) {
        extract($file);
        // $type, $name, $tmp_name, $size, $error
        
        if (UPLOAD_ERR_OK!=$error) {
            $this->_is_error = true;
            $this->_errno    = $error;
            return false;
        }
        $path = $this->get_path();
        if (!is_dir($path) || $path=='') {
            $this->_is_error = true;
            $this->_errno    = FILE_UPLOAD_ERR_PATH;
            return false;
        }
        if ($size>$this->get_max_size()) {
            $this->_is_error = true;
            $this->_errno    = FILE_UPLOAD_ERR_SIZE;
            return false;
        }
        if (!isset($this->_allowed[$type])) {
            $this->_is_error = true;
            $this->_errno    = FILE_UPLOAD_ERR_TYPE;
            return false;
        }

        $ext = strtolower(IUGOFileUpload::file_extension($name));
        if (!$ext || !in_array($ext, $this->_allowed[$type])) {
            $this->_is_error = true;
            $this->_errno    = FILE_UPLOAD_ERR_TYPE;
            return false;
        }

        if (!is_uploaded_file($tmp_name)) {
            $this->_is_error = true;
            $this->_errno    = FILE_UPLOAD_ERR_INVALID;
            return false;
        }


        // cleanup file name to be cool
        $name = preg_replace('/[^a-zA-Z0-9. ]+/','_',$name);

        // auto rename
        $name = IUGOFileUpload::unique_filename($path, $name);
        
        move_uploaded_file($tmp_name, $path.$name);

        // extra paranoid to prevent any execution ever
        chmod($path.$name, 0644);
        return $name;
    }

    function upload_multiple($files) {
        $total = count($files['name']);
        $filenames = array();
        for ($x=0; $x<$total; $x++) {
            $filenames[] = $this->upload( array(
                'name' => $files['name'][$x],
                'type' => $files['type'][$x],
                'tmp_name' => $files['tmp_name'][$x],
                'error'    => $files['error'][$x],
                'size'     => $files['size'][$x],
            ));
        }
        return $filenames;
    }

    function file_extension($file) {
        $ext = array_pop(explode(".", $file));
        if ($ext==$file)
            return false;
        return $ext;
    }

    function set_max_size($size=0) { $this->_max_size = $size; }
    function get_max_size()        { return $this->_max_size; }

    function get_error() {
        switch ($this->_errno) {
            case UPLOAD_ERR_INI_SIZE :
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            case UPLOAD_ERR_FORM_SIZE :
                return ' The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            case UPLOAD_ERR_PARTIAL :
                return 'The uploaded file was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE :
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR :
                return 'Missing a temporary folder.';
            case UPLOAD_ERR_CANT_WRITE :
                return 'Failed to write file to disk.';
            case FILE_UPLOAD_ERR_PATH :
                return 'Upload path is not a directory.';
            case FILE_UPLOAD_ERR_SIZE :
                return 'The uploaded file exceeds the max file size.';
            case FILE_UPLOAD_ERR_TYPE:
                return 'The uploaded file type is invalid.';
            case FILE_UPLOAD_ERR_INVALID:
                return 'The file is not an actual uploaded file.';
        }
        return 'Unknown error ('.intval($this->errno).')';
    }

    function is_error()      { return $this->_is_error; }
    function set_path($path) { $this->path = $path; }
    function get_path()      { return $this->path; }

    function unique_filename($path, $name) {

        if (!file_exists($path.$name))
            return $name;

        return IUGOFileUpload::unique_filename($path, rand(0,99999).'_'.$name);
    }

}

?>