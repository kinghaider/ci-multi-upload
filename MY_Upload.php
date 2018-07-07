<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

Class MY_Upload extends CI_Upload {   
    public $uped_data = [];

    public function __constructor(){
        parent::__constructor();
    }
    /**
     * Upload multiple files using CodeIgniers Upload class
     * 
     * The following method iterates over the files and 
     * uses the do_upload method to upload multiple files 
     * 
     * @param string $field The html file input element name 
     * 
     * @param bool $break If set to true, breaks upload on error
     * 
     * @param bool $delete If set to true, deletes all uploaded files on upload failure 
     * 
     * @return void
     */

	/**
	 * Validate Upload Path
	 *
	 * This method overwrites CI's upload class validate_upload_path method and
     * verifies that it is a valid upload path with proper permissions. Creates 
     * a the upload directory if it does not exist, returns error if creating 
     * directory fails.
     * 
	 *
	 * @return	bool
	 */
	public function validate_upload_path()
	{        
		if ($this->upload_path === '')
		{
			$this->set_error('upload_no_filepath', 'error');
			return FALSE;
		}

		if (realpath($this->upload_path) !== FALSE)
		{
			$this->upload_path = str_replace('\\', '/', realpath($this->upload_path));
		}

		if ( ! is_dir($this->upload_path))
		{
            //echo 'creating directory '.FCPATH.$this->upload_path."<br/>";
            if(!mkdir($this->upload_path,0775,true)){
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
		}

		if ( ! is_really_writable($this->upload_path))
		{
			$this->set_error('upload_not_writable', 'error');
			return FALSE;
		}

		$this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
		return TRUE;
    }
         
    public function multi_upload($field,$break=0,$delete=0){                                
        $file_count = count($_FILES[$field]['name']);
        for($i=0;$i<$file_count;$i++){
            $_FILES['upfile']['name']     = $_FILES[$field]['name'][$i];
            $_FILES['upfile']['type']     = $_FILES[$field]['type'][$i];
            $_FILES['upfile']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
            $_FILES['upfile']['error']    = $_FILES[$field]['error'][$i];
            $_FILES['upfile']['size']     = $_FILES[$field]['size'][$i];

            if(!$this->do_upload('upfile')){                
                if($break){
                    if($delete){
                        $this->delete_uploaded();
                    }
                    break;
                }
            }                   
            $this->uped_data[] = $this->data();
        }
    }
    /**
     * Delete uploaded files.
     * 
     * This method deletes uploaded files when an error has been encountered 
     * on an upload in the multi_upload method.
     * 
     * @return void
     */
    private function delete_uploaded(){
        if(empty($this->uped_data)) return;                
        foreach($this->uped_data as $k=>$v){
            unlink($v['full_path']);
            unset($this->uped_data[$k]);
        }
    }

	/**
	 * Set an error message
     * 
	 * Overwrites CI's Upload class set_error method to 
     * include file name of failed upload for better front end error
     * reporting
     * 
	 * @param	string	$msg
	 * @return	CI_Upload
	 */
	public function set_error($msg, $log_level = 'error')
	{
		$this->_CI->lang->load('upload');

		is_array($msg) OR $msg = array($msg);
		foreach ($msg as $val)
		{
			$msg = ($this->_CI->lang->line($val) === FALSE) ? $val : $this->_CI->lang->line($val);
			$this->error_msg[] = $msg.( ($this->file_name) ? "({$this->file_name})" : '' );
			log_message($log_level, $msg);
		}

		return $this;
	}

    /**
     * Return the data of all uploaded files
     */
    public function multi_data($index=null){
        if(!$index) return $this->uped_data;        
        return array_column($this->uped_data,$index);
    }

    /**
     * Return the processed errors in an array 
     * 
     * @return array 
     */
    public function return_erray(){
        return $this->error_msg;
    }    

}<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

Class MY_Upload extends CI_Upload {   
    public $uped_data = [];

    public function __constructor(){
        parent::__constructor();
    }
	/**
	 * Validate Upload Path
	 *
	 * This method overwrites CI's upload class validate_upload_path method and
     * verifies that it is a valid upload path with proper permissions. Creates 
     * a the upload directory if it does not exist, returns error if creating 
     * directory fails.
     * 
	 *
	 * @return	bool
	 */
	public function validate_upload_path()
	{        
		if ($this->upload_path === '')
		{
			$this->set_error('upload_no_filepath', 'error');
			return FALSE;
		}

		if (realpath($this->upload_path) !== FALSE)
		{
			$this->upload_path = str_replace('\\', '/', realpath($this->upload_path));
		}

		if ( ! is_dir($this->upload_path))
		{
            //echo 'creating directory '.FCPATH.$this->upload_path."<br/>";
            if(!mkdir($this->upload_path,0775,true)){
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
		}

		if ( ! is_really_writable($this->upload_path))
		{
			$this->set_error('upload_not_writable', 'error');
			return FALSE;
		}

		$this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
		return TRUE;
    }
         
    /**
     * Upload multiple files using CodeIgniers Upload class
     * 
     * The following method iterates over the files and 
     * uses the do_upload method to upload multiple files 
     * 
     * @param string $field The html file input element name 
     * 
     * @param bool $break If set to true, breaks upload on error
     * 
     * @param bool $delete If set to true, deletes all uploaded files on upload failure 
     * 
     * @return bool 
     */    
    public function multi_upload($field,$break=0,$delete=0){                                
        $file_count = count($_FILES[$field]['name']);
        for($i=0;$i<$file_count;$i++){
            $_FILES['upfile']['name']     = $_FILES[$field]['name'][$i];
            $_FILES['upfile']['type']     = $_FILES[$field]['type'][$i];
            $_FILES['upfile']['tmp_name'] = $_FILES[$field]['tmp_name'][$i];
            $_FILES['upfile']['error']    = $_FILES[$field]['error'][$i];
            $_FILES['upfile']['size']     = $_FILES[$field]['size'][$i];

            if(!$this->do_upload('upfile')){                
                if($break){
                    if($delete){
                        $this->delete_uploaded();
                    }            
                    //Return false at this point         
                    return false;
                }
            } else {
                $this->uped_data[] = $this->data();
            }                   
            
        }
        return true;        
    }
    /**
     * Delete uploaded files.
     * 
     * This method deletes uploaded files when an error has been encountered 
     * on an upload in the multi_upload method.
     * 
     * @return void
     */
    private function delete_uploaded(){
        if(empty($this->uped_data)) return;                
        foreach($this->uped_data as $k=>$v){
            unlink($v['full_path']);
            unset($this->uped_data[$k]);
        }
    }

	/**
	 * Set an error message
     * 
	 * Overwrites CI's Upload class set_error method to 
     * include file name of failed upload for better front end error
     * reporting
     * 
	 * @param	string	$msg
	 * @return	CI_Upload
	 */
	public function set_error($msg, $log_level = 'error')
	{
		$this->_CI->lang->load('upload');

		is_array($msg) OR $msg = array($msg);
		foreach ($msg as $val)
		{
			$msg = ($this->_CI->lang->line($val) === FALSE) ? $val : $this->_CI->lang->line($val);
			$this->error_msg[] = $msg.( ($this->file_name) ? "({$this->file_name})" : '' );
			log_message($log_level, $msg);
		}

		return $this;
	}

    /**
     * Return the data of all uploaded files
     */
    public function multi_data($fields=null){        
        if(!$fields) return $this->uped_data;
        if(is_string($fields)) return array_column($this->uped_data,$fields);
        $ret = [];
        for($i = 0; $i<count($this->uped_data); $i++){
            foreach($fields as $f){
                $ret[$i][$f] = $this->uped_data[$i][$f];
            }
        }        
        return $ret;
    }

    /**
     * Return the processed errors in an array 
     * 
     * @return array 
     */
    public function return_erray(){
        return $this->error_msg;
    }    

}
