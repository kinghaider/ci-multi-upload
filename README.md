# CI-MultiUpload Library
A simple library that extends CI's native Upload class to enable multiple file uploading with a few modifications.

# Features 
  - Deletion of uploaded files on error 
  - Returns all information of uploaded files in a 2D Array 
  - Error messages appends file name in paranthesis to end of error message
  - Creates upload directory recursively if it does not exist 
 
# Installation 
Download and place MY_Uploader.php in your **application/libraries** folder
Rename MY_Uploader.php to use your own prefix if you've changed it in your
CI configuration

# Usage
1)Initialize the upload library as usual
```
$config = [];
$config['upload_path'] = './uploads';
$config['allowed_types'] = 'png|jpg|jpeg';
$config['max_size'] = '1024';
$this->load->library('upload',$config);
```
2)Call the multi_upload method with your field name and other parameters<br>
The multi upload field takes 3 arguments<br>
  - string $field - The name of your input element 
  - bool $break - Stop uploading when an error is encountered. Defaults to false
  - bool $delete - Delete uploaded files if an error is encountered. Only runs if break is true. Defaults to false
<br>

```
$this->upload->multi_upload('myfile',1,1);
//returns all error messages as an array
$errors = $this->upload->return_erray();
//Fetch information of all uploaded files 
$files = $this->upload->multi_data();
$file_paths_only = $this->upload->multi_data('full_path');
```
<br>The multi upload method does not return a boolean on success/failure due to the nature of multi file uploads, as some files can be met with an error.<br>
You can use the **return_erray()** method and check if any errors were encountered

