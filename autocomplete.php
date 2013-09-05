<?php

	include ('./includes/initiate.php');
	if (isset($_GET['term']) && ($_GET['term'] !='')){  //Retrieve the search term
		$term=$_GET['term']; $aws_s3 = 'http://s3.amazonaws.com/weflect-objects/'; $last_cat=''; $y=1; $array = array();
		if (is_numeric($_SESSION['portal_id'])) {
			$portal=" AND ("; $x=1;
			$cat_id=$_SESSION['portal_id'];
			$query = 	"SELECT cat_child
						FROM cat_rel
						WHERE cat_parent='$cat_id'";
			$result = mysql_query($query);
			while ($row=mysql_fetch_object($result)){
				if ($x!=1) $portal.=" OR ";
				$portal.="cat_id=".$row->cat_child;
				$x++;
			}
			$portal.=")";
		} else {
			$portal="";	$cat_id='';
		}
		$query =	"SELECT *
					FROM popular
					WHERE obj_name REGEXP '$term'".$portal."
					ORDER BY cat_id ASC";
		$result = mysql_query($query);
		if (mysql_num_rows($result) != 0) {
			while ($row=mysql_fetch_object($result)){
				if ($row->cat_name!=$last_cat) {  //creates separation between results of different categories
					$cat=$row->cat_name;
					$last_cat=$row->cat_name; $show=1;
				} else {
					$show++;
					$cat="&nbsp;";
				}
				if ($show<6) {  //limits results to 5 per category
					if (strlen($row->prim_photo_url) > 30) {
						$img = $aws_s3.$row->prim_photo_url."-xs.jpg";
					} else {
						$img = $default_img;	
					}
					$array[]=array(
					 value=> $row->obj_name,
					 label=> $row->obj_name,
					 cat=> $cat,
					 desc=> $row->obj_name,
					 img=> $img,
					 url=> 'http://'.$_SERVER['HTTP_HOST']."/object/".$row->obj_name."/".$row->obj_id."/".$row->cat_id.'/'
					 );
				}
			}
			echo json_encode($array);
		} else {
			echo "";
		}
	} else {
		echo "";
	}
?>