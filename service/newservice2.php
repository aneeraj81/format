<?php
include("include/header.inc.php");
include ("config.php");


//$rs = mysql_query("SELECT * FROM services where newstatus='1'");

/*$id=$_GET['id'];
$sql="DELETE FROM services WHERE id='$id'";
$result=mysql_query($sql);*/



	$rs = mysql_query("SELECT * FROM services WHERE newstatus='1' and inst_name!='' and inst_cur_location!='' order by id desc");
	//echo "SELECT * FROM services order by id desc WHERE pending='1' limit $d, $one_page";die();
	
	
	

?>

<script type="text/javascript">
		$(document).ready(function(){
			

			$("#myTable").tablesorter().tablesorterPager({container: $("#pagination")});
		});
	</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form name="frmMain" action="" method="post">

<table width="771" border="1" cellpadding="0" cellspacing="0" align="center" id="myTable" class="tablesorter">
<thead>

<tr align="Center">
		<th width="10%" align="center"><font color="#0E2C3C"><b>Client Name </b></font></th>
		<th width="7%" align="center"><font color="#0E2C3C"><b>Vehicle No</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Notworking </b></font></th>
		<th width="9%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
		<th width="10%" align="center"><font color="#0E2C3C"><b>Available Time</b></font></th>
		<th width="9%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
		<th width="13%" align="center"><font color="#0E2C3C"><b>Installation Name</b></font></th>
		<th width="8%" align="center"><font color="#0E2C3C"><b>Installer Current Location</b></font></th>
		<th width="7%" align="center"><font color="#0E2C3C"><b>Reason</b></font></th>
		<th width="4%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
		<td width="7%" align="center"><font color="#0E2C3C"><b>Edit</b></font></td>
		<!--<td width="6%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
		
	</tr></thead><tbody>
	<?php  
    while ($row = mysql_fetch_array($rs)) {
	
    ?>  
	<tr align="Center">
	
		<td width="10%" align="center">&nbsp;<?php echo $row['name'];?></td>
		<td width="7%" align="center">&nbsp;<?php echo $row['veh_reg'];?></td>
		
		<td width="10%" align="center">&nbsp;<?php echo $row['Notwoking'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['location'];?></td>
		<td width="10%" align="center">&nbsp;<?php echo $row['atime'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['cnumber'];?></td>
		<td width="13%" align="center">&nbsp;<?php echo $row['inst_name'];?></td>
		<td width="8%" align="center">&nbsp;<?php echo $row['inst_cur_location'];?></td>
		<td width="7%" align="center">&nbsp;<?php echo $row['reason'];?></td>
		<td width="4%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="7%" align="center">&nbsp;<a href="editnewinstallation.php?id=<?=$row['id'];?>&action=edit&pg=<? echo $pg;?>">Edit</a></td>
		<!--<td width="6%" align="center">&nbsp;<a href="newinstallation.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
		
	</tr>
		<?php  
    }
	 
    ?>
	<tr><td colspan="9" align="center"></td></tr> </tbody>
</table> 
</form>
<div id="pagination" class="pager">
			<form>
				<img src="images/sorting/first.png" class="first"/>
				<img src="images/sorting/prev.png" class="prev"/>
				<input type="text" class="pagedisplay" name="page"/>
				<img src="images/sorting/next.png" class="next"/>
				<img src="images/sorting/last.png" class="last"/>
				<select class="pagesize">
					<option selected="selected"  value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
				</select>
			</form>
		</div>
        <br/><br/>
<?
include("include/footer.inc.php");

?>
