<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu.php');

/*include($_SERVER['DOCUMENT_ROOT']."/format/include/header.php");
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu.php");*/

$id=$_GET['id'];
$inst_req_id = $_GET['req_id'];
if($_GET['action']=="close")
{
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
    $query1="UPDATE installation SET pending_closed='1',installation_status=6  WHERE id='$id'";
    mysql_query($query1);
   
    $total_row = select_query("SELECT COUNT(*) AS total_count FROM installation WHERE inst_req_id='".$inst_req_id."'");
   
    $close_inst_row = select_query("SELECT COUNT(*) AS total_row FROM installation WHERE inst_req_id='".$inst_req_id."' AND installation_status IN ('5','6')");
   
    if($total_row[0]['total_count'] == $close_inst_row[0]['total_row'])
    {
        mysql_query("UPDATE installation_request SET close_date='".date("Y-m-d")."', installation_made='".$close_inst_row[0]['total_row']."', installation_status=5 WHERE id='".$inst_req_id."'");
    }
   
    echo "<script>document.location.href ='back_installation.php?for=formatrequest'</script>";
}

?>

<div class="top-bar">
  
    <h1>Back Installation</h1>
     
</div>
    <div class="top-bar">
   
        <div style="float:right";><font style="color:#CC6;font-weight:bold;">Brown:</font> Back Installation</div>
        <br/>
    </div>

<div class="table">
<?php
 
   
    //$rs = mysql_query("SELECT * FROM installation WHERE (pending='1' or newpending='1')  and (pending_closed='0') and branch_id=".$_SESSION['BranchId'] ." order by id desc");
    //1-New,2-assigned,3-newbacktoservice,4-backtoservice,5-close,6-callingclose
    $query = select_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation WHERE installation_status=3 and branch_id=".$_SESSION['BranchId'] ."  and sales_person='".$sales[0]['id']."' order by id desc");
 

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th>Sl. No</th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Sales Person </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>ClientName </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Location</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Model</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>time </b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Vehicle Type</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact No</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Contact Person</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Back Reason</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>View Detail</b></font></th>
           
            <!--<th width="11%" align="center"><font color="#0E2C3C"><b>Edit</b></font></th>
            <th width="11%" align="center"><font color="#0E2C3C"><b>Close</b></font></th>-->
            <!--<td width="11%" align="center"><font color="#0E2C3C"><b>Delete</b></font></td>-->
           
        </tr>
    </thead>
    <tbody>
 
 
 
<?php
 
//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
?>
<tr align="center" <? if( $query[$i]["installation_status"]=="3"){ echo 'style="background-color:#CC6"';} ?> >

<td><?php echo $i+1;?></td>
  <td width="11%" align="center">&nbsp;<?php $sales=select_query("select name from sales_person where id='$query[$i][sales_person]' "); echo $sales[0]['name'];?></td>
          <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
    $rowuser=select_query($sql);
    ?>
  <td><? echo $rowuser[0]["sys_username"];?></td>
        <td width="11%" align="center">&nbsp;<?php echo $query[$i]['no_of_vehicals'];?></td>
       
       
        <?php if($query[$i]['location']!=""){?>
        <td align="center">&nbsp;<?php echo $query[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$query[$i]['inter_branch']."'");?>
        <td align="center">&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td width="10%" align="center">&nbsp;<?php echo $query[$i]['model'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $query[$i]['time'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $query[$i]['veh_type'];?></td>
       
        <td width="12%" align="center">&nbsp;<?php echo $query[$i]['contact_number'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $query[$i]['contact_person'];?></td>
        <td width="12%" align="center">&nbsp;<?php echo $query[$i]['back_reason'];?></td>
       
        <td><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a></td>
        <!--<td width="10%" align="center">&nbsp;<a href="update_installation.php?id=<?=$query[$i]['id'];?>&pending=1&action=editp">Edit</a></td>
        <td width="10%" align="center">&nbsp;<a href="?id=<?=$query[$i]['id'];?>&pending=1&action=close&pg=<? echo $pg;?>">Close</a></td>-->
       
        <!--<td width="10%" align="center">&nbsp;<a href="update_back_installation.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a></td>
        <td width="10%" align="center">&nbsp;<a href="?id=<?=$query[$i]['id'];?>&req_id=<?=$query[$i]['inst_req_id'];?>&action=close&pg=<? echo $pg;?>">Close</a></td>-->
               
  </tr>
<?php    }?>
</table>
    
    <div id="toPopup">
       
        <div class="close">close</div>
           <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup1"> <!--your content start-->
           

 
        </div> <!--your content end-->
   
    </div> <!--toPopup end-->
   
    <div class="loader"></div>
       <div id="backgroundPopup"></div>
</div>
 
 
 
<?
include("../include/footer.php");

?>