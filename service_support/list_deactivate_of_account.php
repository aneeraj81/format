<?php
include($_SERVER['DOCUMENT_ROOT']."/format/include/header.php");

if($_SESSION['user_name']=='jaipurrequest') {
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu_service.php");
}
else{
include($_SERVER['DOCUMENT_ROOT']."/format/include/leftmenu_service_support.php");
}
 

?>
<script>
 
function ConfirmReactivate(row_id)
{
  var x = confirm("Are you sure you want to Reactivate this?");
  if (x)
  {
  ractivate(row_id);
      return ture;
  }
  else
    return false;
}

function ractivate(row_id)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=ReactivateUserAccount",
 		data:"row_id="+row_id,
		success:function(msg){
		 
		location.reload(true);		
		}
	});
}

function ConfirmPaymentClear(row_id)
{
  var x = confirm("All payment cleared?");
  if (x)
  {
  PaymentClear(row_id);
      return ture;
  }
  else
    return false;
}

function PaymentClear(row_id)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=DeactivationPaymentClear",
 		data:"row_id="+row_id,
		success:function(msg){
		 
		location.reload(true);		
		}
	});
}

function ConfirmPaymentPending(row_id)
{
   var retVal = prompt("Pending Amount : ", "Pending Amount");
  if (retVal)
  {
  PaymentPending(row_id,retVal);
      return ture;
  }
  else
    return false;
}

function PaymentPending(row_id,retVal)
{
	//alert(user_id);
	//return false;
$.ajax({
		type:"GET",
		url:"userInfo.php?action=DeactivationPaymentPending",
 		 
		 data:"row_id="+row_id+"&comment="+retVal,
		success:function(msg){
			  
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
                        <option value=3 <?if($_POST['Showrequest']==3){ echo "Selected"; }?>>Admin Approved</option>
                        <option value="" <?if($_POST['Showrequest']==''){ echo "Selected"; }?>>Pending</option>
                        <option value="1" <?if($_POST['Showrequest']==1){ echo "Selected"; }?>>Closed</option>
                        <option value="2" <?if($_POST['Showrequest']==2){ echo "Selected" ;}?>>All</option>
                        
                    </select>
                </form>
            </div>
                    <h1>Deactivation of account</h1>
					  
                </div>
                  <div class="top-bar">
                    <div style="float:right";><font style="color:#B6B6B4;font-weight:bold;">Grey:</font>Admin Approved</div>
                    <br/>
                    <div style="float:right";><font style="color:#D462FF;font-weight:bold;">Purple:</font> Back from support</div>
                    <br/>
                    <div style="float:right";><font style="color:#FF0000;font-weight:bold;">Red:</font> Back from Account</div>
                    <br/>
                    <div style="float:right";><font style="color:#8BFF61;font-weight:bold;">Green:</font> Completed your requsest.</div>			  
                    </div>
                <div class="table">
<?php


if($_POST["Showrequest"]==1)
 {
	  $WhereQuery=" where approve_status=1 and final_status=1 and acc_manager in ('khetraj','jaipursales')";
 }
 else if($_POST["Showrequest"]==2)
 {
	 $WhereQuery=" where approve_status=1 and acc_manager in ('khetraj','jaipursales') ";
 }
  else if($_POST["Showrequest"]==3)
 {
	 $WhereQuery=" where approve_status=1 and final_status!=1 and acc_manager in ('khetraj','jaipursales')";
 }
 else
 { 
	  
	/* $WhereQuery=" where approve_status=0 and final_status!=1 and acc_manager in ('khetraj','jaipursales')";*/
	 
   $WhereQuery=" where (approve_status=0 or (approve_status=1 and support_comment!='' and final_status!=1)) and (sales_comment is null or deactivation_status=2)  and (acc_manager in ('khetraj','jaipursales') or (forward_req_user in ('khetraj','jaipursales') and (forward_back_comment is null or forward_back_comment='')))";
  
 }
  
 
 
$query = mysql_query("SELECT * FROM deactivation_of_account   ". $WhereQuery."  order by id desc ");   

?>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
        <tr>
            <th>SL No</th>
            <th>Date</th>
            <th>Account Manager</th>
            <th>Client</th>
            <th>Total No Of Vehicles</th>
            <th>Deactivate Temporary</th>
            <th>Reason</th> 
            <th>View Detail</th>
            <th>Add Details</th>
        </tr>
	</thead>
	<tbody>
 
<?php 
$i=1;
while($row=mysql_fetch_array($query))
{
?>
<tr align="center" <?php /*?><? if( $row["support_comment"]!="" && $row["final_status"]!=1 ){ echo 'style="background-color:#D462FF"';}elseif($row["pay_pending"]!="" && $row["account_comment"]=="" && $row["sales_comment"]==""){ echo 'style="background-color:#FF0000"';}elseif($row["approve_status"]==1){ echo 'style="background-color:#B6B6B4"';} elseif($row["final_status"]==1){ echo 'style="background-color:#8BFF61"';}?><?php */?> >
 
<td><?php echo $i ?></td>
  <td><?php echo $row["date"];?></td>
  <? if($row["acc_manager"]=='saleslogin') {
$account_manager=$row["sales_manager"]; 
}
else {
$account_manager=$row["acc_manager"]; 
}

?>
  
  <td><?php echo $account_manager;?></td>
 <? $sql="SELECT Userid AS id,UserName AS sys_username FROM addclient  WHERE Userid=".$row["user_id"];

	$rowuser=select_query($sql);

			?>
  <td><?php echo $row["company"];?></td>
  <td><?php echo $row["total_no_of_vehicles"];?></td>
  <td><?php echo $row["deactivate_temp"];?></td>
  <!-- <td><?php echo $row["reg_no"];?></td>
  <td><?php echo $row["ps_of_location"];?></td>
  <td><?php echo $row["ps_of_ownership"];?></td>
  <td><?php echo $row["reason"];?></td>
  <td><?php echo $row["psd_paid"];?></td>
  <td><?php echo $row["psd_unpaid"];?></td>
  <td><?php echo $row["ps_rent"];?></td>
  <td><?php echo $row["service_action"];?></td> --> 
  <td><?php echo $row["reason"];?></td>
  
   <td><a href="#" onclick="Show_record(<?php echo $row["id"];?>,'deactivation_of_account','popup1'); " class="topopup">View Detail</a>
   	<?php if($_POST["Showrequest"]==1 && $row["reactivate_status"]=='N'){?>
   | <a href="#" onclick="return ConfirmReactivate(<?php echo $row["id"];?>);"  >Reactivate</a>
   <?php } ?>
   </td>
   <td><?php 
if($row["approve_status"]==0) {?>  <a href="deactivateofaccount.php?id=<?=$row['id'];?>&action=edit<? echo $pg;?>">Add Details</a>
		<!--<td width="12%" align="center">&nbsp;<a href="services.php?id=<?php echo $row['id'];?>&action=delete">Delete</a></td>-->
<?php } ?>
</td>

</tr> <?php $i++; }?>
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
 
<?php
include("include/footer.php"); ?>



 