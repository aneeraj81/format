<?php
include ("config.php");
include("include/header.inc.php");

//$rs = mysql_query("SELECT * FROM services WHERE status='1'");

$id=$_GET['id'];
$sql="DELETE FROM services WHERE id='$id'";
$result=mysql_query($sql);


	
$status=$_GET['status']	;
if($status=='back_to')
	{
	$rs = mysql_query("SELECT * FROM installation WHERE (newpending='1' and newstatus='0') or (pending=1 and status=0 and newpending=0) and branch_id=".$_SESSION['branch_id']);
	}
	else
	{
	$rs = mysql_query("SELECT * FROM installation where status='1' and branch_id=".$_SESSION['branch_id']." order by id desc");
	}

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

<table width="784" border="1" cellpadding="0" cellspacing="0" align="center"  id="myTable" class="tablesorter">
<thead>
<tr align="Center">
		<th width="12%" align="center"><font color="#0E2C3C"><b>sales Person </b></font></th>
		<th width="12%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
		<th width="12" align="center"><font color="#0E2C3C"><b>Vehicle No<br/>/IP Box</b></font></th>
		
		<th width="12%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
		<th width="12%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
		<th width="12%" align="center"><font color="#0E2C3C"><b>Time</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>DIMTS</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Demo</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Amount</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
		<th width="11%" align="center"><font color="#0E2C3C"><b>Immobilizer Type</b></font></th>
		
		<th width="12%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
		
		<td width="12%" align="center"><font color="#0E2C3C"><b>Edit</b></font></td>
		<!--<td width="12%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
		
		
	</tr></thead>
			<tbody>
	<?php  
    while ($row = mysql_fetch_array($rs)) {
	$sales=mysql_fetch_array(mysql_query("select name from sales_person where id='$row[sales_person]' "));
	if($row[IP_Box]=="") $ip_box="Not Required";  else $ip_box="$row[IP_Box]"; 
    ?>  
	<tr align="Center">
	
		<td width="11%" align="center">&nbsp;<?php echo $sales['name'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['client'];?></td>
		
		<td width="11%" align="center">&nbsp;<?php echo "$row[no_of_vehicals] <br/>/$ip_box";?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['location'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['model'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['time'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['dimts'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['demo'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['payment_req'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['veh_type'];?></td>
		<td width="9%" align="center">&nbsp;<?php echo $row['immobilizer_type'];?></td>
		
		<td width="10%" align="center">&nbsp;<?php echo $row['contact_number'];?></td>
		
		<td width="8%" align="center">&nbsp;<a href="edit_installation.php?id=<?=$row['id'];?>&action=edit&pg=<? echo $pg;?>">Edit</a></td>
		<!--<td width="11%" align="center">&nbsp;<a href="services_from_sales.php?id=<?php echo $row['id'];?>">Delete</a></td>-->
		
	</tr>
		<?php  
    }
	 
    ?>
	<tr><td colspan="9" align="center"></td></tr></tbody>
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