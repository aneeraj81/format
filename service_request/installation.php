<?php
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_service.php');

/*include($_SERVER['DOCUMENT_ROOT']."/format/include/header.php");
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu_service.php");*/

?>
<script>
function backComment(row_id)
{
   var retVal = prompt("Write Comment : ", "Comment");
  if (retVal)
  {
  addComment(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
$.ajax({
		type:"GET",
		url:"userInfo.php?action=InstallationbackComment",
 		data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
		  location.reload(true);		
		}
	});
}

function doneConfirm(row_id)
{
  var x = confirm("Are you sure Client Confirm this installation?");
  if (x)
  {
  approve(row_id);
      return ture;
  }
  else
    return false;
}

function approve(row_id)
{
$.ajax({
		type:"GET",
		url:"userInfo.php?action=InstallationConfirm",
 		data:"row_id="+row_id,
		success:function(msg){
  		location.reload(true);		
		}
	});
}

</script>

<div class="top-bar">
  
  <h1>Installation Request</h1>
  <div style="margin-left:796px;font-size:12px;">
    <a href="add_installation.php">Installation</a> || 
    <a href="re_installation.php">Re-Addition</a> || 
    <a href="add_installation.php">Crack</a> || 
    <a href="online_crack.php">Online-Crack</a>
  </div>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
  <br/>
  <div style="float:right";><font style="color:#F2F5A9;font-weight:bold;">Yellow:</font> Back from Admin</div>
  <br/>
  <div style="float:right";><font style="color:#99FF66;font-weight:bold;">Green:</font> Closed Installation</div>
  <br/>
  <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font> Approved</div>
  <br/>
  <div style="float:right";><font style="color:#EDA4FF;font-weight:bold;">LightBlue:</font> InterBranch Installation</div>
</div>
<div class="table">
<?php 
$fromdateof_service="";
$todaydate = date("Y-m-d  H:i:s");
$newdate = strtotime ( '-5 day' , strtotime ( $todaydate ) ) ;
$fromdateof_service = date ( 'Y-m-j H:i:s' , $newdate );

$mode=$_GET['mode'];
if($mode=='') { $mode="new"; }
	
  if($mode=='close')
	{
	//$query = mysql_query("SELECT * FROM installation where reason!='' or rtime!='' and branch_id=".$_SESSION['BranchId']."  order by id desc");
	 
	$query = select_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation where (installation_status='5' or installation_status='6') and time>'".$fromdateof_service."' and branch_id=".$_SESSION['BranchId']." and request_by='".$_SESSION['username']."' order by id desc");
	}
	else if($mode=='new')
	{
	 
 	$query = select_query("SELECT *,DATE_FORMAT(req_date,'%d %b %Y %h:%i %p') as req_date,DATE_FORMAT(time,'%d %b %Y %h:%i %p') as time FROM installation_request where  (installation_status ='1' or installation_status='2' or installation_status='4' or installation_status='7' or installation_status='8' or installation_status='9')   and branch_id=".$_SESSION['BranchId']."  and request_by='".$_SESSION['username']."'  order by id desc");
	}	

	?>
  <div style="float:right"><a href="installation.php?mode=new">New</a> | <a href="installation.php?mode=close">Closed</a></div>
  <?php
 
?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th nowrap>Job Type</th>
        <th>Request By </th>
        <th>Request Date </th>
        <th><font color="#0E2C3C"><b>Sales Person </b></font></th>
        <th><font color="#0E2C3C"><b>Client</b></font></th>
        <th><font color="#0E2C3C"><b>No. Of Vehicle</b></font></th>
        <th><font color="#0E2C3C"><b>Branch</b></font></th>
        <th><font color="#0E2C3C"><b>Branch Location</b></font></th>
        <th><font color="#0E2C3C"><b>Landmark</b></font></th>
        <th><font color="#0E2C3C"><b>Device Type</b></font></th> 
        <th><font color="#0E2C3C"><b>Accessories</b></font></th>
        <th><font color="#0E2C3C"><b>A-Time</b></font></th>
        <th><font color="#0E2C3C"><b>Contact Details</b></font></th>
        <th>View Detail</th>
        <?php if($mode=='close')
	   {?>
        <th  ><font color="#0E2C3C"><b>Closed</b></font></th>
        <?php }
		else
		{?>
        <th><font color="#0E2C3C"><b>Edit</b></font></th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php 
 
//while($row=mysql_fetch_array($query))
for($i=0;$i<count($query);$i++)
{
	$sales=select_query("select name from sales_person where id='".$query[$i]['sales_person']."' ");

  //echo "<pre>";print_r($sales);die;
	 	
?>
      <!--<tr  <? if( $query[$i]["support_comment"]!="" && $query[$i]["final_status"]!=1 ){ echo 'style="background-color:#FF3333"';} elseif($query[$i]["final_status"]==1){ echo 'style="background-color:#99FF66"';}elseif($query[$i]["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';}?> >-->
      
      <tr <?php if($query[$i]["approve_status"]==1 && $query[$i]["installation_status"]==9){ echo 'style="background-color:#B6B6B4"';}elseif($query[$i]['installation_status']==5 or $query[$i]['installation_status']==6 )  {  ?> style="background:#99FF66;" <?php }elseif( $query[$i]["admin_comment"]!="" && ($query[$i]["sales_comment"]=="" || $query[$i]["installation_status"]==7)){ echo 'style="background-color:#F2F5A9"';}elseif($query[$i]['required']=='urgent'){ ?>style="background:#ADFF2F" <?php }elseif($query[$i]['inter_branch']!=0){ ?>style="background:#EDA4FF" <?php }?> >
        <td align="center">
          <?php 
       
          $sql1 = select_query("select instal_reinstall from installation_request WHERE id='".$query[$i]['id']."'");

          //echo $sql1[0]['instal_reinstall'];

          if($query[$i]['instal_reinstall'] == "installation"){ echo "I";}elseif($query[$i]['instal_reinstall'] == "re_install"){ echo "Re-Add";}elseif($query[$i]['instal_reinstall'] == "re_install"){ echo "Re-Add";}elseif($query[$i]['instal_reinstall'] == "re_install"){ echo "Re-Add";}
          
          ?>
        </td>
        <td align="center">&nbsp;<?php echo $query[$i]['request_by'];?></td>
        <td nowrap>

          &nbsp;
          <?php 

          echo date("Y-m-d",strtotime($query[$i]['req_date']))."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          echo date("G:i",strtotime($query[$i]['req_date']));

          ?></td>
        <td align="center">&nbsp;<?php echo $sales[0]['name'];?></td>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
	$rowuser=select_query($sql);

  //print_r($rowuser);die();
	?>
        <td align="center"><?echo $rowuser[0]["sys_username"];?></td>
        <td align="center">&nbsp;<?php echo $query[$i]['no_of_vehicals'];?></td>
        <?php if($query[$i]['location']!=""){?>
        <td align="center">&nbsp;<?php echo $query[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$query[$i]['inter_branch']."'");?>
        <td align="center">&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td>

          <?php 
       
          $sql1 = select_query("select Zone_area from installation_request WHERE id='".$query[$i]['id']."'");

          $sql2 = select_query("SELECT name FROM re_city_spr_1 WHERE id='".$sql1[0]['Zone_area']."'");

          echo $sql2[0]['name'];
          
          ?>


        </td>
        
         <td align="center">
          <?php
          $sql5 = select_query("select landmark from installation_request WHERE id='".$query[$i]['id']."'");

          echo $sql5[0]['landmark'];
          ?>

        </td>

        <td align="center" nowrap>

          <?php 
       
            $sql5 = select_query("select device_status from installation_request WHERE id='".$query[$i]['id']."'");

            echo $sql5[0]['device_status'];
        
          ?>

          
        </td>
        <td align="center">
          <?php
          $sql5 = select_query("select acess_selection from installation_request WHERE id='".$query[$i]['id']."'");

          echo $sql5[0]['acess_selection'];
          ?>

        </td>
        <td align="center" nowrap>

          <?php 
            echo date("Y-m-d",strtotime($query[$i]['time']))."<br>";
            echo date("G:i",strtotime($query[$i]['time']))."<br>";

            $sql4 = select_query("select atime_status from installation_request WHERE id='".$query[$i]['id']."'");

            echo $sql4[0]['atime_status'];
          ?>

        </td>
        <td>
          <?php
              $sql6 = select_query("select designation,contact_person,contact_number from installation_request WHERE id='".$query[$i]['id']."'");

              echo "&#160;&#160;&#160;&#160;".$sql6[0]['contact_person']."<br>";
              echo $sql6[0]['contact_number']."<br>";
              echo "&#160;&#160;&#160;&#160;".$sql6[0]['designation']."<br>";
          ?>

        </td>
        <td align="center" nowrap><?php if($mode=='close') {?>
          <a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
          <?php } else {?>
          <a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'installation_request','popup1'); " class="topopup">View Detail</a>
          <?php } ?>
          <?php if($query[$i]["admin_comment"]!="" && ($query[$i]["sales_comment"]=="" || $query[$i]["installation_status"]==7)){?>
          | <a href="#" onclick="return backComment(<?php echo $query[$i]["id"];?>);"  >Back Comment</a>
          <?php } 
                 if($query[$i]["installation_status"]==9 && $query[$i]["approve_status"]==1){?>
          | <a href="#" onclick="return doneConfirm(<?php echo $query[$i]["id"];?>);"  >Confirmation Done</a>
          <?php } ?></td>
        <?php if($mode=='close')
	   {?>
        <td >Closed</td>
        <?php }
		else
		{ ?>
        <td >&nbsp;
          <?php //if($query[$i]["approve_status"]!=1){?>
          
          <!-- <a href="add_installation.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a>-->
          
          

          <?php //if($query[$i]["installation_status"] == 1 ) {?>
          <!-- <a href="update_reinstallation.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a> -->
          <?php //} ?>


          <?php if($query[$i]["installation_status"] == 1 ) 
          {?>
            <a href="update_reinstallation.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a>
          <?php
           } 
          else if($query[$i]["installation_status"] == 8) 
          {?>
            <a href="update_installation.php?id=<?=$query[$i]['id'];?>&action=edit">Edit</a>
           <?php  
           }  ?>

          </td>
        <?php } ?>
        
        <!--<td >&nbsp;<a href="installation.php?id=<?php echo $query[$i]['id'];?>&action=delete">Delete</a></td>--> 
        
      </tr>
      <?php  } ?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?php

include("../include/footer.php");

?>
