<?php
function make_thumb($img_name,$filename,$new_w,$new_h,$ext)
{		
		if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
			$src_img=imagecreatefromjpeg($img_name);
					
		if(!strcmp("png",$ext))
			$src_img=imagecreatefrompng($img_name);
			
		if(!strcmp("GIF",$ext) || !strcmp("gif",$ext))
			$src_img=imagecreatefromgif($img_name);
			
		$old_x=imagesx($src_img);
		$old_y=imagesy($src_img);	
		
		$thumb_w=$new_w;
		$thumb_h=$new_h;
		
		$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
		if(!strcmp("png",$ext)){
			imagealphablending($dst_img,false);
   			imagesavealpha($dst_img,true);
			$transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
     		imagefilledrectangle($dst_img, 0, 0, $thumb_w, $thumb_h, $transparent);
		}
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		if(!strcmp("png",$ext))			
			imagepng($dst_img,$filename);
		else
		imagejpeg($dst_img,$filename);
		imagedestroy($dst_img);
		imagedestroy($src_img);
}

class content{
	function getData($id){
		global $db;
		$select="select * from ".TABLE_CONFERENCE." where id=".(int)$id;
		$data=$db->fetchRow($select);
		return $data;
	}
	function checkData($data,$id){
		global $db; $error=array();
		 
		 
		
		return $error;
		 
	 
	}
	function insertData($data){
		global $db;		
		global $conn;				
		$insert="insert into ".TABLE_CONFERENCE." set 	conference='".mysqli_real_escape_string($conn, stripslashes($data['conference']))."', 
		date='".mysqli_real_escape_string($conn, stripslashes($data['date']))."', 
		time='".mysqli_real_escape_string($conn, stripslashes($data['time']))."', 
		description='".mysqli_real_escape_string($conn, stripslashes($data['description']))."', 
		
		author='".mysqli_real_escape_string($conn, stripslashes($data['author']))."', 
		
		author_discript='".mysqli_real_escape_string($conn, stripslashes($data['author_discript']))."', 
		ord='".mysqli_real_escape_string($conn, stripslashes($data['ord']))."', 
		title='".mysqli_real_escape_string($conn, stripslashes($data['title']))."'";
										
		$reuslt=$db->fetchResult($insert);
		$id = mysqli_insert_id($conn);
		 
		
		if($reuslt)  echo "<script>location.replace('index.php?p=conferenceagendas&msg=1');</script>";	
	
	}
	
	function updateData($data,$id){
		global $db;		
        global $conn;
		$update="update ".TABLE_CONFERENCE." set conference='".mysqli_real_escape_string($conn, stripslashes($data['conference']))."', date='".mysqli_real_escape_string($conn, stripslashes($data['date']))."', 
		time='".mysqli_real_escape_string($conn, stripslashes($data['time']))."', 
		description='".mysqli_real_escape_string($conn, stripslashes($data['description']))."', 
		
		author='".mysqli_real_escape_string($conn, stripslashes($data['author']))."', 
		
		author_discript='".mysqli_real_escape_string($conn, stripslashes($data['author_discript']))."',ord='".mysqli_real_escape_string($conn, stripslashes($data['ord']))."',  title='".mysqli_real_escape_string($conn, stripslashes($data['title']))."' where id=".$id;				  
																					
		$reuslt=$db->fetchResult($update);
		if($reuslt){
			echo "<script>location.replace('index.php?p=conferenceagendas&msg=2');</script>";
		 }
	}
}
?>