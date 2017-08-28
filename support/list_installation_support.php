<?php 
include("../connection.php");
include_once(__DOCUMENT_ROOT.'/include/header.php');
include_once(__DOCUMENT_ROOT.'/include/leftmenu_support.php');
 
?>
<script>
 
function BackComment(row_id)
{
  //var retVal = prompt("Write Comment : ", "Comment");
  var retVal = confirm("Are you sure you want to Close?");
  if (retVal)
  {
  	  addComment(row_id,'Done');
      return ture;
  }
  else
    return false;
}

function addComment(row_id,retVal)
{
	//alert(user_id);
$.ajax({
		type:"GET",
		url:"userInfo.php?action=ForwardInstallationDone",
 		 
		data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
			 alert(msg);
			location.reload(true);		
		}
	});
}
</script>

<div class="top-bar">
  <div align="center">
    <form name="myformlisting" method="post" action="">
      <select name="Showrequest" id="Showrequest" onchange="form.submit();" >
        <option value="0" <? if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>
        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
        <option value="3" <?if($_POST['Showrequest']==3){ echo "Selected" ;}?>>Action Taken</option>
        <option value="4" <?if($_POST['Showrequest']==4){ echo "Selected" ;}?>>Forward</option>
        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
      </select>
    </form>
  </div>
  <h1>Installation List</h1>
</div>
<div class="top-bar">
  <div style="float:right";><font style="color:#ADFF2F;font-weight:bold;">GreenYellow:</font> Urgent Installation</div>
  <br/>
  <div style="float:right";><font style="color:#CCCCCC;font-weight:bold;">Grey:</font> Closed Installation</div>
</div>
<div class="table">
  <?php
if($_POST["Showrequest"]==1)
 {
	   $WhereQuery=" where fwd_tech_rm_id='".$_SESSION['userId']."' and (installation_status='5' or installation_status='6') ";
 }
 else if($_POST["Showrequest"]==2)
 {
	  $WhereQuery="where fwd_tech_rm_id='".$_SESSION['userId']."'";
 }
 else if($_POST["Showrequest"]==3)
 {
	  $WhereQuery="where installation_status='2' and fwd_tech_rm_id='".$_SESSION['userId']."' and fwd_repair_to_install is NOT NULL ";
 }
 else if($_POST["Showrequest"]==4)
 {
	  $WhereQuery="where installation_status='11' and fwd_tech_rm_id='".$_SESSION['userId']."' and (fwd_repair_id is NOT NULL and fwd_install_to_repair is NOT NULL and fwd_repair_to_install is NULL) ";
 }
 else
 { 
	 $WhereQuery=" where installation_status='11' and fwd_tech_rm_id='".$_SESSION['userId']."' and fwd_reason is NOT NULL and fwd_repair_id is NULL and fwd_install_to_repair is NULL and fwd_repair_to_install is NULL ";
  
 }
   
$query = select_query("SELECT * FROM installation ". $WhereQuery."  order by id desc limit 1500");   

?>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      <tr>
        <th>Sl. No</th>
        <th>Request Date </th>
        <th><font color="#0E2C3C"><b>Sales Person </b></font></th>
        <th><font color="#0E2C3C"><b>Client Name </b></font></th>
        <th><font color="#0E2C3C"><b>No.Of Vehicle </b></font></th>
        <th><font color="#0E2C3C"><b>Location</b></font></th>
        <th><font color="#0E2C3C"><b>Model</b></font></th>
        <th><font color="#0E2C3C"><b>Time</b></font></th>
        <th><font color="#0E2C3C"><b>Installer Name</b></font></th>
        <th><font color="#0E2C3C"><b>Forward Comment</b></font></th>
        <th><font color="#0E2C3C"><b>Dispatch Comment</b></font></th>
        <th><font color="#0E2C3C"><b>View Detail</b></font></th>
        <th><font color="#0E2C3C"><b>Forward</b></font></th>
      </tr>
    </thead>
    <tbody>
      <?php 
	
    //while ($row = mysql_fetch_array($rs)) 
	for($i=0;$i<count($query);$i++)
	{
		if($query[$i]['IP_Box']==""){ $ip_box="Not Required";}  else { $ip_box = $query[$i]['IP_Box']; }; 
	
    ?>
      <tr align="Center"  <? if($query[$i]['installation_status']==5 or $query[$i]['installation_status']==6 )  {  ?> style="background:#CCCCCC;" <? }
	else  if($query[$i]['required']=='urgent'){ ?>style="background:#ADFF2F" <? }?>>
        <td><?php echo $i+1; ?></td>
        
        <td>&nbsp;<?php echo $query[$i]['req_date'];?></td>
        <td>&nbsp;
          <?php $sales = select_query("select name from sales_person where id='".$query[$i]['sales_person']."' "); echo $sales[0]['name'];?></td>
        <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$query[$i]["user_id"];
			$rowuser=select_query($sql);
		?>
        <td><? echo $rowuser[0]["sys_username"];?></td>
        <td>&nbsp;<?php echo $query[$i]['no_of_vehicals']." <br/><br/>/".$ip_box;?></td>
        <?php if($query[$i]['location']!=""){?>
        <td>&nbsp;<?php echo $query[$i]['location'];?></td>
        <?php }else{ $city= select_query("select * from tbl_city_name where branch_id='".$query[$i]['inter_branch']."'");?>
        <td>&nbsp;<?php echo $city[0]['city'];?></td>
        <?php }?>
        <td>&nbsp;<?php echo $query[$i]['model'];?></td>
        <td>&nbsp;<?php echo $query[$i]['time'];?></td>
        <td  style="font-size:12px">&nbsp;<strong><?php echo $query[$i]['inst_name'];
		if($query[$i]['job_type']==2)
		{
			echo "<br/><font color='red'>(pending Job)</font>";
		}
		else
		{
			echo "<br/>(Ongoing Job)";
		}
		?> </strong></td>
        <td>&nbsp;<?php if($query[$i]['fwd_install_to_repair'] != "" && $query[$i]['fwd_repair_id'] != "") {
					echo $query[$i]['fwd_repair_date'].' - '.$query[$i]['fwd_install_to_repair']; 
				}?></td>
        <td><?php if($query[$i]['fwd_reason'] != "" && $query[$i]['fwd_tech_rm_id'] != "") { 
					echo $query[$i]['fwd_datetime'].' - '.$query[$i]['fwd_reason'];
				} ?>
        </td>
        
        <td><a href="#" onclick="Show_record(<?php echo $query[$i]["id"];?>,'installation','popup1'); " class="topopup">View Detail</a>
          <?php if($_POST["Showrequest"]!=1 && $_POST["Showrequest"]!=2 && $_POST["Showrequest"]!=3 && $_POST["Showrequest"]!=4){?>
          |<a href="#" onclick="return BackComment(<?php echo $query[$i]["id"];?>);">Action</a>
          <?php } ?></td>
        <td>
        <?php if($_POST["Showrequest"]!=1 && $_POST["Showrequest"]!=2 && $_POST["Showrequest"]!=3 && $_POST["Showrequest"]!=4){?>
        <a href="forward_service_installation_iframe.php?id=<?=$query[$i]["id"];?>&req_id=2&height=220&width=480" class="thickbox">Forward</a>
         <?php } ?>
        </td>
        
      </tr>
      <?php  
    	}
	 
    ?>
  </table>
  <div id="toPopup">
    <div class="close">close</div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup1" style ="height:100%;width:100%"> <!--your content start--> 
      
    </div>
    <!--your content end--> 
    
  </div>
  <!--toPopup end-->
  
  <div class="loader"></div>
  <div id="backgroundPopup"></div>
</div>
<?
include("../include/footer.php");

?>
