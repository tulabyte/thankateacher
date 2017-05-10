<?php
//header('Access-Control-Allow-Origin: *');

// require_once 'dbHandler.php';
 
$location = '../../admin/img/card-images';
$image_name = $_POST['image'];
$uploadfilename = $_FILES['file']['tmp_name'];
// $db = new DbHandler();
// $filename = $db->randomPassword();
$filename = $location.'/'.$image_name;

   if (file_exists($filename)){
	unlink($filename);
   } else {
	 if(move_uploaded_file($uploadfilename, $location.'/'.$image_name) ){ //$filename)){
	        echo 'File successfully uploaded!';
	} else {
	        echo 'Upload error!';
		}
   };
 