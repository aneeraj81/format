<?php

$data="ID-1039951,GPSdate-26-07-2013 10:53:45,Indiadate-26-07-2013 16:23:45,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 10:56:57,Indiadate-26-07-2013 16:26:57,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:00:00,Indiadate-26-07-2013 16:30:00,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:03:06,Indiadate-26-07-2013 16:33:06,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:06:11,Indiadate-26-07-2013 16:36:11,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:09:15,Indiadate-26-07-2013 16:39:15,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:12:17,Indiadate-26-07-2013 16:42:17,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:15:19,Indiadate-26-07-2013 16:45:19,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0";


$string=explode("\n",$data);
$toEnd = count($string);
foreach($string as $key=>$value) {
  if (1 === --$toEnd) {
    echo "$value"."<br>";
  }
}

$string=explode("\n",$data);
$toEnd = count($string);
foreach($string as $key=>$value) {
  if (0 === --$toEnd) {
    echo "$value";
  }
}
/*$string=explode("\n",$data);
foreach ($string as $key => $value)
{
echo "[".$key."] = ".$value."<br>";
}

$string=explode("\n",$data);
foreach ($string as $key => $value) {
 
   echo "[".$key."] = ".GPS."<br>";
}*/
?>


	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" href="css/style.css" />
<style>
.page{
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
}
table{
border:1px;
}

</style>
<link href="../../css/admin.css" rel="stylesheet" type="text/css">
<link href="../../css/controls.css" rel="stylesheet" type="text/css">
<link href="../../css/mouse_over.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="page">
<script src="../../js/ajax.js"></script>
<script>
function filetime(file,service_id){

	ajaxpage_get('filetime.php?qry=testimonials&file='+file+'&service_id='+service_id,'filetime','');


}
</script>
<script>
function submitme(){
//alert("hi");
if(document.getElementById('searchtype1').checked==true){
document.form1.action='debug.php';
}
document.form1.submit();
//document.form1.action='debug.php';
return false;
}
</script>
<form method="post" action="" onsubmit="return submitme();" name="form1">
  <input type="text" name="userid" id="userid" value="1039951"><select name="selecttype">
<option id="0" value="0"  selected="selected" >All Vehicles</option>
<option id="1" value="1" >Not Working Vehicles</option>
</select>
<input type="radio" name="searchtype" id="searchtype1" value="user"   />User
<input type="radio" name="searchtype" id="searchtype2" value="imei"   checked="checked" />Imei
<input type="submit" name="submit" value="submit">&nbsp;&nbsp;<!-- <a href='data-error-log/26-07-13.txt' target='_blank'>Last Vehcile Erros</a> -->
</form>
<div style="text-align:right"> <a href="debug.php"> Search by Username </a>&nbsp;&nbsp;&nbsp;<a href="list_vehicles_by_group_name.php"> Search All </a> &nbsp;&nbsp;&nbsp; <a href="list_all_vehicles_by_group_name.php" target="_blank">Group Vehciles by Account</a> <a href="save_as_excel.php?type=print" target="_blank"></a>&nbsp;&nbsp;<a href="save_as_excel.php?manage=&username=1039951" target="_blank"></a></div>
<div id="filetime"></div><div id="datatime"></div>
 File Found <br> 				
 <textarea cols="200" rows="20" wrap="hard">
ID-1039951,GPSdate-26-07-2013 10:53:45,Indiadate-26-07-2013 16:23:45,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 10:56:57,Indiadate-26-07-2013 16:26:57,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:00:00,Indiadate-26-07-2013 16:30:00,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:03:06,Indiadate-26-07-2013 16:33:06,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:06:11,Indiadate-26-07-2013 16:36:11,Lat-28.6196578349619,Long-77.1045370666162,Speed-0,Direction-222,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:09:15,Indiadate-26-07-2013 16:39:15,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:12:17,Indiadate-26-07-2013 16:42:17,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
ID-1039951,GPSdate-26-07-2013 11:15:19,Indiadate-26-07-2013 16:45:19,Lat-28.6196618456665,Long-77.1045301911227,Speed-0,Direction-92,Ign-0,Satellite-6,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-26.117647058817,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
</textarea>

																		<br>
									Insert Error Log File
									<br>
									<textarea cols="200" rows="20">ID-1039951,GPSdate-26-07-2013 08:09:11,Indiadate-26-07-2013 13:39:11,Long-77.1651250616219,Speed-0,Direction-72,Ign-0,Satellite-5,GPS-1,MainPower-1,BatteryPower-1,MainpowerVol-25.9999999999935,BattVol-4.06823529281,TransmissionReason-11,Tempring-0,Temp-0
</textarea>
									
									
									<pre>File Modified Time=<b>2013/07/26 16:45:19</b><br>Last Record in Telemetry at:2013-07-26 16:45:19<br>Registartion No=<b>DL1PB3123</b><br><br><div style='height:300px; width:400px;overflow:scroll;'>Log=--------------------------------------
Date: 22-05-2013 17:02:14
Notes: 
**Created**
--------------------------------------
--------------------------------------
Date: 22-05-2013 17:02:14
Notes: 
Added to Vimal
--------------------------------------
--------------------------------------
Date: 22-05-2013 17:02:14
Notes: 
Added to Jupiter Travels
--------------------------------------
</pre>

</form>
</body>
</html>