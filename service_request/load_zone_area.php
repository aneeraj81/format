<?php
session_start();
include("../connection.php");

$keyword = $_POST['data'];
$city_id = $_POST['city_id'];

$sql = "SELECT id,`name` FROM re_city_spr_1 WHERE `name` LIKE '".$keyword."%' and region_code='".$city_id."' ORDER BY `name`";
	//echo $sql; die;
	
	$result = select_query($sql);
	if(count($result))
	{
		echo '<ul class="list">';
		//while($row = mysql_fetch_array($result))
		for($i=0;$i<count($result);$i++)
		{
			$str = strtolower($result[$i]['name']);
			$start = strpos($str,$keyword); 
			$end   = similar_text($str,$keyword); 
			$last = substr($str,$end,strlen($str));
			$first = substr($str,$start,$end);
			
			$final = strtoupper('<span class="bold">'.$first.'</span>'.$last);
		
			echo '<li><a href=\'javascript:void(0);\'>'.$final.'</a></li>';
		}
		echo "</ul>";
	}
	else
		echo 0;
		
?>