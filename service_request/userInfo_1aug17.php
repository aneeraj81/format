<?php
error_reporting(0);
ob_start();
session_start();
include("../connection.php");
//include_once(__DOCUMENT_ROOT.'/sqlconnection.php');

$q=$_GET["user_id"];
$veh_reg=$_GET["veh_reg"];
$row_id=$_GET["row_id"];
$inst_id=$_GET["inst_id"];
$comment=$_GET["comment"];
if(isset($_GET['action']) && $_GET['action']=='countDeletedImei')
{	
	$sql="select imei from deletion where user_id=".$row_id;	
	
	$row=select_query($sql);

	echo json_encode($row);
}

if(isset($_GET['action']) && $_GET['action']=='getAllImei')
{	
	$userId=$_GET['userId'];
	$sql="select imei from deletion where user_id=".$userId;	
	
	$row=select_query($sql);

	echo json_encode($row);
}

if(isset($_GET['action']) && $_GET['action']=='imeistatus')
{	
	$imei=$_GET['imeiNo'];

	$sql="SELECT device_status from inventory.device where device_imei=".$imei;	
	
	$row=select_inventory_query($sql);

	if($row[0]['device_status'] == '103'){

		echo "Cleint Office";
	}
	else if($row[0]['device_status'] == '65'){

		echo "Device Installed";
	}
	else if($row[0]['device_status'] == '57'){

		echo "G-TRAC";
	}
	else if($row[0]['device_status'] == '63'){

		echo "G-TRAC";
	}
	else if($row[0]['device_status'] == '64'){

		echo "G-TRAC";
	}
	else if($row[0]['device_status'] == '116'){

		echo "G-TRAC";
	}
	else{

		echo "Under Process";
	}

}

if(isset($_GET['action']) && $_GET['action']=='imeiDeviceType')
{	
	$imei=$_GET['imeiNo'];
	$sql="select imei from deletion where user_id=".$userId;	
	
	$row=select_query($sql);

	echo json_encode($row);
}

// if(isset($_GET['action']) && $_GET['action']=='deviceName')
// {	
// 	//$tt=$_GET["user_id"];
// 		// $sql1="SELECT id FROM addclient WHERE Userid='".$tt."'";	
// 		// $row1=select_query($sql1);	
// 		$sql2="SELECT device_type as dev_type_id,name as deviceType FROM new_account_model_master as newmodel LEFT JOIN device_types as dtype ON newmodel.device_type=dtype.id WHERE new_account_reqid=".$q;	
// 		//echo $sql2; die;
// 		$row2=select_query($sql2);	
// 		echo json_encode($row2);
// }
// if(isset($_GET['action']) && $_GET['action']=='modelname')
// {	
// 	$userId = $_GET['user_id'];

// //	echo "SELECT device_model from internnew_account_model_master WHERE device_type='".$userId."' LIMIT 1";die();

// 	$sql="SELECT device_model from new_account_model_master WHERE device_type='".$userId."' LIMIT 1";
	
// 	$row=select_query($sql);

// 	echo $row[0]['device_model'];
// }

if(isset($_GET['action']) && $_GET['action']=='deviceName')
{	
	$userId=$_GET["user_id"];
		//$sql1="SELECT id FROM addclient WHERE Userid='".$tt."'";	
		//$row1=select_query($sql1);	
		$sql2="SELECT dtype.id as dev_type_id,dtype.device_type as deviceType FROM new_account_model_master as newmodel LEFT JOIN device_type as dtype ON newmodel.device_type=dtype.id WHERE new_account_reqid='".$userId."'";	
		//echo $sql2; die;
		$row2=select_query($sql2);	
		echo json_encode($row2);
}
if(isset($_GET['action']) && $_GET['action']=='modelname')
{	
		$dev_type_id=$_GET["dev_type"];
		$userId1=$_GET["user_id"]; 
		//$sql1="SELECT id FROM addclient WHERE Userid=".$user_id1;	
		//$row1=select_query($sql1);	
		$sql2="SELECT dm.id as model_id,dm.device_model as model_name from new_account_model_master as newmodel inner join device_model as dm  ON newmodel.device_model=dm.id WHERE newmodel.new_account_reqid='".$userId1."' and dm.parent_id='".$dev_type_id."'" ;
		//echo $sql2; die;
		$row2=select_query($sql2);	
		echo json_encode($row2);
}



if(isset($_GET['action']) && $_GET['action']=='salespersonname')
{
	$sql="SELECT name AS 'sales_person_name' FROM addclient ac LEFT JOIN sales_person sp ON ac.sales_person_id = sp.id  WHERE ac.Userid=".$q;

	$row=select_query($sql);

	echo $row[0]["sales_person_name"];
}
if(isset($_GET['action']) && $_GET['action']=='deletevehiclebackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.deletion  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus=select_query("update internalsoftware.deletion set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id);
	
	echo "Comment added Successfully";
}
if(isset($_GET['action']) && $_GET['action']=='devicechangebackComment')
{
   
	$query = "SELECT forward_back_comment FROM internalsoftware.device_change  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus=select_query("update internalsoftware.device_change set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id);
	
	echo "Comment added Successfully";
}
if(isset($_GET['action']) && $_GET['action']=='InstallationbackComment')
{

	$query = "SELECT sales_comment FROM internalsoftware.installation_request  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.installation_request set sales_comment='".$row[0]["sales_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."',installation_status='8' where id=".$row_id;
	select_query($Updateapprovestatus);
}
if(isset($_GET['action']) && $_GET['action']=='InstallationConfirm')
{
     $query = "SELECT * FROM internalsoftware.installation_request  where id=".$row_id;
     $row=select_query($query);
     $approve_inst = $row[0]["installation_approve"];
     $time_status = $row[0]["atime_status"];
   
    for($N=1;$N<=$approve_inst;$N++)
    {       
        if($time_status == "Till")
        {
            $installation = "INSERT INTO internalsoftware.installation(`inst_req_id`, `req_date`, `request_by`, sales_person, `user_id`, 
			`company_name`, no_of_vehicals, location, model, time, contact_number, installed_date, status, contact_person, dimts,demo, 
			veh_type,comment,immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status,`inter_branch`, 
			branch_type, instal_reinstall, approve_status, installation_approve, approve_date, fuel_sensor, bonnet_sensor, rfid_reader, 
			speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button,mode_of_payment,device_price,device_price_vat,
			device_price_total,device_rent_Price,device_rent_service_tax,DTotalREnt,demo_time,rent_status,rent_month,rent_payment) 
			VALUES('".$row[0]["id"]."','".$row[0]["req_date"]."','".$row[0]["request_by"]."','".$row[0]["sales_person"]."', 
			'".$row[0]["user_id"]."', '".$row[0]["company_name"]."','1','".$row[0]["location"]."','".$row[0]["model"]."','".$row[0]["time"]."',
			'".$row[0]["contact_number"]."','".$row[0]["installed_date"]."', 1,'".$row[0]["contact_person"]."','".$row[0]["dimts"]."',
			'".$row[0]["demo"]."','".$row[0]["veh_type"]."','".$row[0]["comment"]."','".$row[0]["immobilizer_type"]."',
			'".$row[0]["payment_req"]."','".$row[0]["required"]."','".$row[0]["IP_Box"]."','".$row[0]["branch_id"]."','1',
			'".$row[0]["Zone_area"]."','".$row[0]["atime_status"]."','".$row[0]["inter_branch"]."','".$row[0]["branch_type"]."',
			'".$row[0]["instal_reinstall"]."','".$row[0]["approve_status"]."','1','".$row[0]["approve_date"]."','".$row[0]["fuel_sensor"]."',
			'".$row[0]["bonnet_sensor"]."','".$row[0]["rfid_reader"]."','".$row[0]["speed_alarm"]."',
			'".$row[0]["door_lock_unlock"]."','".$row[0]["temperature_sensor"]."','".$row[0]["duty_box"]."','".$row[0]["panic_button"]."',
			'".$row[0]["mode_of_payment"]."','".$row[0]["device_price"]."','".$row[0]["device_price_vat"]."','".$row[0]["device_price_total"]."',
			'".$row[0]["device_rent_Price"]."','".$row[0]["device_rent_service_tax"]."','".$row[0]["DTotalREnt"]."','".$row[0]["demo_time"]."',
			'".$row[0]["rent_status"]."','".$row[0]["rent_month"]."','".$row[0]["rent_payment"]."')";
               
            $execute_inst=select_query($installation);
        }
       
        if($time_status == "Between")
        {
            $installation = "INSERT INTO internalsoftware.installation(`inst_req_id`, `req_date`, `request_by`, sales_person, `user_id`, 
			`company_name`, no_of_vehicals, location, model, time, totime, contact_number,installed_date, status, contact_person, dimts,demo, 
			veh_type,comment, immobilizer_type, payment_req,required, IP_Box,branch_id,installation_status, Zone_area,atime_status, inter_branch,
			branch_type, instal_reinstall, approve_status, installation_approve, approve_date, fuel_sensor, bonnet_sensor, rfid_reader, 
			speed_alarm, door_lock_unlock, temperature_sensor, duty_box, panic_button, mode_of_payment, device_price, device_price_vat, 
			device_price_total, device_rent_Price,device_rent_service_tax,DTotalREnt,demo_time,rent_status,rent_month,rent_payment) 
			VALUES('".$row[0]["id"]."', '".$row[0]["req_date"]."',
			'".$row[0]["request_by"]."','".$row[0]["sales_person"]."', '".$row[0]["user_id"]."', '".$row[0]["company_name"]."','1',
			'".$row[0]["location"]."','".$row[0]["model"]."','".$row[0]["time"]."','".$row[0]["totime"]."','".$row[0]["contact_number"]."',
			'".$row[0]["installed_date"]."',1,'".$row[0]["contact_person"]."','".$row[0]["dimts"]."','".$row[0]["demo"]."',
			'".$row[0]["veh_type"]."','".$row[0]["comment"]."','".$row[0]["immobilizer_type"]."','".$row[0]["payment_req"]."',
			'".$row[0]["required"]."','".$row[0]["IP_Box"]."','".$row[0]["branch_id"]."','1','".$row[0]["Zone_area"]."',
			'".$row[0]["atime_status"]."','".$row[0]["inter_branch"]."','".$row[0]["branch_type"]."','".$row[0]["instal_reinstall"]."',
			'".$row[0]["approve_status"]."','1','".$row[0]["approve_date"]."','".$row[0]["fuel_sensor"]."','".$row[0]["bonnet_sensor"]."',
			'".$row[0]["rfid_reader"]."','".$row[0]["speed_alarm"]."','".$row[0]["door_lock_unlock"]."','".$row[0]["temperature_sensor"]."',
			'".$row[0]["duty_box"]."','".$row[0]["panic_button"]."','".$row[0]["mode_of_payment"]."','".$row[0]["device_price"]."',
			'".$row[0]["device_price_vat"]."','".$row[0]["device_price_total"]."','".$row[0]["device_rent_Price"]."',
			'".$row[0]["device_rent_service_tax"]."','".$row[0]["DTotalREnt"]."','".$row[0]["demo_time"]."','".$row[0]["rent_status"]."',
			'".$row[0]["rent_month"]."','".$row[0]["rent_payment"]."')";
               
            $execute_inst=select_query($installation);
        }
    }
           
    $Updateapprovestatus="update internalsoftware.installation_request set installation_status=1 where id=".$row_id;
    select_query($Updateapprovestatus);
   
}

/*if(isset($_GET['action']) && $_GET['action']=='FFCDeviceRslt')
{
    $ffc_imei = $_GET["ffc_imei"];
    $ffc_details = mssql_fetch_array(mssql_query("select old_client_name,ffc_date,old_veh from device_replace_ffc where imei_no=".$ffc_imei));

    //echo $ffc_data = $ffc_details["old_client_name"]."~".$ffc_details["old_veh"]."~".$ffc_details["ffc_date"];
    echo $ffc_data = 'harish'."~".'HR30A4567'."~".'2015-05-30 15:34:27';
}*/

if(isset($_GET['action']) && $_GET['action']=='serviceComment')
{
	$query = "SELECT service_comment FROM internalsoftware.device_change  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.device_change set service_comment='".$row[0]["service_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."', device_change_status='1' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

/*if(isset($_GET['action']) && $_GET['action']=='InstallationClosed')
{
    $Updateapprovestatus="update internalsoftware.installation set inst_close_reason='".date("Y-m-d H:i:s")." - ".$comment."',installation_status='5' where id=".$row_id;
    mysql_query($Updateapprovestatus);
}*/


if(isset($_GET['action']) && $_GET['action']=='AccCreationServiceComment')
{
    $query = "SELECT sales_comment FROM internalsoftware.new_account_creation  where id=".$row_id;
    $row=select_query($query);
     
    $Updateapprovestatus="update internalsoftware.new_account_creation set sales_comment='".$row[0]["sales_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."', acc_creation_status='1' where id=".$row_id;
   
    select_query($Updateapprovestatus);
    echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='NewDeviceAddComment')
{
    $query = "SELECT service_comment FROM internalsoftware.new_device_addition where id=".$row_id;
    $row=select_query($query);
     
    $Updateapprovestatus="update internalsoftware.new_device_addition set service_comment='".$row[0]["service_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."', new_device_status='1' where id=".$row_id;
   
    select_query($Updateapprovestatus);
    echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='renew_serviceComment')
{
    $query = "SELECT service_comment FROM internalsoftware.renew_dimts_imei  where id=".$row_id;
    $row=select_query($query);
     
    $Updateapprovestatus="update internalsoftware.renew_dimts_imei set service_comment='".$row[0]["service_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."', renew_dimts_status='1' where id=".$row_id;
   
    select_query($Updateapprovestatus);
    echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='DevicecloseComment')
{
    $Updateapprovestatus="update internalsoftware.device_change set close_comment='".date("Y-m-d H:i:s")." - " .$comment."',service_comment='".date("Y-m-d H:i:s")." - " .$comment."',final_status=1,close_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
   
    select_query($Updateapprovestatus);
}

if(isset($_GET['action']) && $_GET['action']=='newdeviceadditionbackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.new_device_addition  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.new_device_addition set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}
if(isset($_GET['action']) && $_GET['action']=='vehiclenochangebackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.vehicle_no_change  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.vehicle_no_change set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='vehicleserviceComment')
{
     $query = "SELECT service_comment FROM internalsoftware.vehicle_no_change  where id=".$row_id;
     $row=select_query($query);

    $Updateapprovestatus="update internalsoftware.vehicle_no_change set service_comment='".$row[0]["service_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."', vehicle_status='1' where id=".$row_id;
   
    select_query($Updateapprovestatus);
    echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='vehiclecloseComment')
{
   
    $Updateapprovestatus="update internalsoftware.vehicle_no_change set close_comment='".date("Y-m-d H:i:s")." - " .$comment."',final_status=1,close_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
   
    select_query($Updateapprovestatus);
}

if(isset($_GET['action']) && $_GET['action']=='simchangebackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.sim_change  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.sim_change set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='SimcloseComment')
{
   
    $Updateapprovestatus="update internalsoftware.sim_change set close_comment='".date("Y-m-d H:i:s")." - " .$comment."',final_status=1,close_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
   
    select_query($Updateapprovestatus);
}

if(isset($_GET['action']) && $_GET['action']=='simchangeserviceComment')
{
	$query = "SELECT service_comment FROM internalsoftware.sim_change  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.sim_change set service_comment='".$row[0]["service_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."',sim_change_status=1 where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='devicelostbackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.device_lost  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.device_lost set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}
if(isset($_GET['action']) && $_GET['action']=='deactivatesimbackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.deactivate_sim  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.deactivate_sim set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='dimtsimeibackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.dimts_imei  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.dimts_imei set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='RenewdimtsimeibackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.renew_dimts_imei  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.renew_dimts_imei set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - ".$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='aacountcreationbackComment')
{
	$query = "SELECT forward_back_comment FROM internalsoftware.new_account_creation  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.new_account_creation set forward_back_comment='".$row[0]["forward_back_comment"]."<br/>".date("Y-m-d H:i:s")." - " .$comment."' where id=".$row_id;
	
	select_query($Updateapprovestatus);
	echo "Comment added Successfully";
}

if(isset($_GET['action']) && $_GET['action']=='RenewDimtsImiei')
{
   
	$query = "SELECT * FROM internalsoftware.dimts_imei  where id=".$row_id;
	$row=select_query($query);
	
	$Updateapprovestatus="update internalsoftware.dimts_imei set renew_status='Y',renew_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
	select_query($Updateapprovestatus);
	
	$renew_query="INSERT INTO internalsoftware.`renew_dimts_imei` (`date`,sales_manager,acc_manager,`client`,`user_id`,`veh_reg`, `device_imei_7`,device_imei_15,port_change,reason) VALUES ('".date("Y-m-d H:i:s")."','".$row[0]["sales_manager"]."','".$row[0]["acc_manager"]."','".$row[0]["client"]."','".$row[0]["user_id"]."','".$row[0]["veh_reg"]."','".$row[0]["device_imei_7"]."','".$row[0]["device_imei_15"]."','".$row[0]["port_change"]."','".$comment."')";
	
	select_query($renew_query);     
}

if(isset($_GET['action']) && $_GET['action']=='getdata')
{
 
  	$result="select services.id as id,services.id,veh_reg from matrix.services where services.id in
		  (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
			select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";

	$data=select_query_live_con($result);
	
	$msg=' <select name="veh_reg" id="<?php echo $select_id?>" onchange="getdeviceImei(this.value,\'TxtDeviceIMEI\');getInstaltiondate(this.value,\'date_of_install\');getdevicemobile(this.value,\'Devicemobile\');">
	<option value="0">Select Vehicle No</option>';

	for($i=0;$i<count($data);$i++)
	  {
		if($i%3==0) {
		$msg .="</tr><tr>";
		}
	  $msg .="<option value='".$data[$i]['veh_reg']."'>".$data[$i]['veh_reg']."</option>"; 
	 
	  }
	 
	  $msg .="</select>";
	 
	  echo $msg;
}


if(isset($_GET['action']) && $_GET['action']=='getdatareplce')
{
 
  $result="select services.id as id,services.id,veh_reg from matrix.services
			where services.id in (select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
			select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";

	$data=select_query_live_con($result);
	$num_row = count($data);
	
	$msg=' <select name="veh_reg_replce" id="veh_reg_replce" onchange="getdeviceImei(this.value,\'replaceDeviceIMEI\');getInstaltiondate(this.value,\'replacedate_of_install\');getInstaltiondate(this.value,\'replacedate_oInstaltiondate_install\');getdevicemobile(this.value,\'replaceDevicemobile\');">
	<option value="0">Select Vehicle No</option>';


	if($num_row > 0){
	
	for($i=0;$i<count($data);$i++)
	  {
	
		$vehreg=str_replace(" ",",",$data[$i]['veh_reg']);
		if($i%3==0) {
		}
	  $msg .="<option value=".$vehreg.">".$data[$i]['veh_reg']."</option>"; 
	 
	  }
	 
	}

   $msg .="</select>";
 
   echo $msg;
}
 
if(isset($_GET['action']) && $_GET['action']=='ReInstalltion')
{
 
    $UserId=$_GET["user_id"];
       
    $vehicle_id_row = select_query_live_con("SELECT sys_service_id FROM matrix.group_services WHERE active=0 AND sys_group_id=(SELECT sys_group_id FROM matrix.group_users where sys_user_id='".$UserId."')");
   
    $veh_id_get = "";
	for($re=0;$re<count($vehicle_id_row);$re++)
    {
        $veh_id_get.= $vehicle_id_row[$re]['sys_service_id']."','";
    }
    $veh_id_data=substr($veh_id_get,0,strlen($veh_id_get)-3);
   
    $device_get_row = select_query_live_con("SELECT id,sys_device_id FROM matrix.services WHERE id IN ('".$veh_id_data."')");
   
    $sys_device_id = "";
	for($de=0;$de<count($device_get_row);$de++)
    {
        $sys_device_id.= $device_get_row[$de]['sys_device_id']."','";
    }
    $sys_device_id_data=substr($sys_device_id,0,strlen($sys_device_id)-3);
   
    $result = select_query_live_con("SELECT device_imei FROM matrix.device_mapping WHERE device_id IN ('".$sys_device_id_data."')"); 
    
	$msg.=" <select  name='deleted_imei'  id='deleted_imei'>
		<option value=''>Select imei</option>";
	 
	for($r=0;$r<count($result);$r++)
	{
		
		$device_imei = str_replace("_","",$result[$r]['device_imei']);
		if($device_imei !='')
		{
			$msg .="<option value=".$device_imei.">".$device_imei."</option>";
		}
	
	}
 
  $msg .="</select></td></tr>";
    
  echo $msg;
}



if(isset($_GET['action']) && $_GET['action']=='total')
{
	$result="select services.id as id,services.id,veh_reg from matrix.services where services.id in
		(select distinct sys_service_id from matrix.group_services where active=true and sys_group_id in (
		select sys_group_id from matrix.group_users where sys_user_id=(".$q.")))";
												   
	$data=select_query_live_con($result);
	
	echo count($data);
}

if(isset($_GET['action']) && $_GET['action']=='companyname')
{
	 
	$sql="select `group`.name as company from matrix.group_users left join matrix.`group` on group_users.sys_group_id=`group`.id where group_users.sys_user_id=".$q;

	$row=select_query_live_con($sql);

	echo trim($row[0]["company"]);
}

if(isset($_GET['action']) && $_GET['action']=='pricing')
{
         
	$sql="SELECT * FROM internalsoftware.new_account_creation WHERE user_name=(SELECT UserName FROM internalsoftware.addclient WHERE Userid='".$q."') ORDER BY id DESC limit 1";
	$row=select_query($sql);
	
	if($row[0]["mode_of_payment"] == 'Cheque')
	{
		echo $row[0]["mode_of_payment"].'##'.$row[0]["device_price"].'##'.$row[0]["device_rent_Price"]; 
	}
	else if($row[0]["mode_of_payment"] == 'Cash' || $row[0]["mode_of_payment"] == 'Lease')
	{
		echo $row[0]["mode_of_payment"].'##'.$row[0]["device_price_total"].'##'.$row[0]["DTotalREnt"]; 
	}
	else if($row[0]["mode_of_payment"] == 'Demo')
	{
		echo $row[0]["mode_of_payment"].'##'.$row[0]["demo_time"]; 
	}
	else if($row[0]["mode_of_payment"] == 'FOC' || $row[0]["mode_of_payment"] == 'Trip Based')
	{
		echo $row[0]["mode_of_payment"].'##'.$row[0]["device_price"].'##'.$row[0]["device_rent_Price"];
	}
	else
	{
		echo $row[0]["mode_of_payment"].'##'.$row[0]["device_price"].'##'.$row[0]["device_rent_Price"];
	}
		
		
}
	
if(isset($_GET['action']) && $_GET['action']=='creationdate')
{
	 
	$sql="select * from matrix.users where id=".$q;

	$row=select_query_live_con($sql);

	echo date("d-M-Y",strtotime($row[0]["sys_added_date"]));
}

if(isset($_GET['action']) && $_GET['action']=='deviceImei')
{
   
	$sql1="select imei from matrix.devices where id in
	(select sys_device_id from matrix.services where veh_reg='".str_replace(","," ",$veh_reg)."') limit 1";
	$row=select_query_live_con($sql1);
	
	echo $row[0]["imei"];
}
   
   
if(isset($_GET['action']) && $_GET['action']=='deviceMobile')
{
	 
	$sql1="select mobile_no from matrix.mobile_simcards where id in ( select sys_simcard from matrix.devices where id in (select sys_device_id from matrix.services where veh_reg='".str_replace(","," ",$veh_reg)."'))";
	$row=select_query_live_con($sql1);
	
	echo $row[0]["mobile_no"];
}


if(isset($_GET['action']) && $_GET['action']=='installermobile')
{
	$sql2="select installer_mobile as installer_mobile from installer where inst_id=".$inst_id;

	$row2=select_query($sql2);

	echo $row2[0]["installer_mobile"];
}


if(isset($_GET['action']) && $_GET['action']=='Instaltiondate')
{
	 
	$sql="select sys_created from matrix.services where veh_reg='".str_replace(","," ",$veh_reg)."' limit 1";

	$row=select_query_live_con($sql);

	echo date("Y-m-d",strtotime($row[0]["sys_created"]));
}

if(isset($_GET['action']) && $_GET['action']=='checkUser')
{
	 
	$row = select_query_live_con("select sys_username from matrix.users where sys_username='".$q."' ");
	
	if(count($row)>0){echo "User Exists";}
	else {echo "";}
}
   
   
if(isset($_GET['action']) && $_GET['action']=='dimts_imeiclose')
{
    $Updateapprovestatus="update internalsoftware.dimts_imei set final_status=1 ,close_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
    select_query($Updateapprovestatus);
    echo "Successfully closed";
}

if(isset($_GET['action']) && $_GET['action']=='Renewdimts_imeiclose')
{
    $Updateapprovestatus="update internalsoftware.renew_dimts_imei set final_status=1 ,close_date='".date("Y-m-d H:i:s")."' where id=".$row_id;
    select_query($Updateapprovestatus);
    echo "Successfully closed";
}

if(isset($_GET['action']) && $_GET['action']=='Port_change')
{
    $Updateapprovestatus="update internalsoftware.renew_dimts_imei set port_change_status='Yes' where id=".$row_id;
    select_query($Updateapprovestatus);
    echo "Successfully Submit";
}
   
if(isset($_GET['action']) && $_GET['action']=='debugComment')
{
	$Comment_by=$_SESSION['username'];

	$Updateapprovestatus="insert into matrix.comment(service_id,comment,comment_by) values('".$row_id."','".addslashes($comment)."','".$Comment_by."')";
	//if(select_query($Updateapprovestatus))
	echo "Comment Added Successfully";
   
}

if(isset($_GET['action']) && $_GET['action']=='getmodel')
{
 	$getmodel = $_GET["model"];
  	$model_query = "select * from internalsoftware.device_model where `status`=1 and parent_id='".$q."' order by device_model asc ";
	$model_data = select_query($model_query);
	
	$msg=' <select name="modelno[]" id="modelno" style="width:150px" ><option value="">Select Model No</option>';

	for($m=0;$m<count($model_data);$m++)
	  {
	 	   if($model_data[$m]['device_model']==$getmodel){ $selected="selected";}
           else { $selected=""; }
		 
		 $msg .="<option value='".$model_data[$m]['device_model']."' ".$selected.">".$model_data[$m]['device_model']."</option>"; 
	  }
	 
	  $msg .="</select>";
	 
	  echo $msg;
}
 

if(isset($_GET['action']) && $_GET['action']=='getrowSales')
{
		$RowId=$_GET["RowId"];
		$tablename=$_GET["tablename"];
	   
 ?>
<style type="text/css">
#databox {
	width:840px;
	height:700px;
	margin: 30px auto auto;
	border:1px solid #bfc0c1;
	font-family:Arial, Helvetica, sans-serif;
	font-size:13px;
	font-weight:normal;
	color:#3f4041;
}
.heading {
	font-family:Arial, Helvetica, sans-serif;
	font-size:30px;
	font-weight:700;
	word-spacing:5px;
	text-align:center;
	color:#3E3E3E;
	background-color:#ECEFE7;
	margin-bottom:10px;
}
.dataleft {
	float:left;
	width:400px;
	height:400px;
	margin-left:10px;
	border-right:1px solid #bfc0c1;
}
.dataright {
	float:right;
	width:400px;
	height:400px;
	margin-left:19px;
}
.dataleft2 {
	float:left;
	width:400px;
	/*height:200px;*/
	margin-left:10px;
	border-right:1px solid #bfc0c1;
}
.dataright2 {
	float:right;
	width:400px;
	/*height:200px;*/
	margin-left:19px;
}
.datacenter {
	margin-top:350px;
	width:800px;
	/*height:200px;*/
	margin-left:10px;
}
td {
	padding-right:20px;
	padding-left:20px;
}
</style>
<?php

if($tablename=="device_change")
	{
		$query = "SELECT * FROM ".$tablename." where id=".$RowId;
        $row=select_query($query);

       ?>

<div id="databox">
  <div class="heading"> View Device Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?php echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?php echo $row[0]["sales_manager"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?php echo $row[0]["client"];?></td>
      </tr>
      <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?php echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI</td>
        <td><?php echo $row[0]["device_imei"];?></td>
      </tr>
      <tr>
        <td>Veh Num</td>
        <td><?php echo $row[0]["reg_no"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?php echo $row[0]["mobile_no"];?></td>
      </tr>
      <tr>
        <td>Date of installation </td>
        <td><?php echo $row[0]["date_of_install"];?></td>
      </tr>
      <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["rdd_username"];
		$rowuser_old=select_query($sql);
	  ?>
      <tr>
        <td>Replaced Device Details</td>
        <td>---------------------------</td>
      </tr>
      <tr>
        <td>Client User</td>
        <td><?php echo $rowuser_old[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Client Name</td>
        <td><?php echo $row[0]["rdd_companyname"];?></td>
      </tr>
      <tr>
        <td>Device Type</td>
        <td><?php echo $row[0]["rdd_device_type"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?php echo $row[0]["rdd_device_model"];?></td>
      </tr>
      <tr>
        <td>Vehicle No</td>
        <td><?php echo $row[0]["rdd_reg_no"];?></td>
      </tr>
      <tr>
        <td>IMEI</td>
        <td><?php echo $row[0]["rdd_device_imei"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?php echo $row[0]["rdd_device_mobile_num"];?></td>
      </tr>
      <tr>
        <td>Date of installation </td>
        <td><?php echo $row[0]["rdd_date_replace"];?></td>
      </tr>
      <tr>
        <td>Installer Name </td>
        <td><?php echo $row[0]["inst_name"];?></td>
      </tr>
      <tr>
        <td>Billing</td>
        <td><?php echo $row[0]["billing"];?></td>
      </tr>
      <tr>
        <td>Billing Amount</td>
        <td><?php echo $row[0]["billing_amount"];?></td>
      </tr>
      <tr>
        <td>Service Support Comment</td>
        <td><?php echo $row[0]["service_support_com"];?></td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td><strong>Process Pending </strong></td>
        <td><strong>
          <?php  if(($row[0]["device_change_status"]==2 && $row[0]["rdd_device_type"]!="New") || (($row[0]["support_comment"]!="" || ($row[0]["admin_comment"]!="" && $row[0]["rdd_device_type"]!="New")) && $row[0]["service_comment"]=="")){echo "Reply Pending at Request Side";}   
 elseif($row[0]["rdd_device_imei"]=="" && $row[0]["rdd_reason"]=="" && $row[0]["approve_status"]==0){echo "Request Not Completely Generate.";}
 elseif($row[0]["account_comment"]=="" && $row[0]["pay_status"]=="" && $row[0]["rdd_reason"]!="" && $row[0]["approve_status"]==0){echo "Pending at Accounts";} 
 elseif($row[0]["rdd_device_type"]=="New" && ($row[0]["service_support_com"]=='' || $row[0]["device_change_status"]==2) && $row[0]["approve_status"]==0){echo "Pending at Delhi Service Support Login";}
 elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["device_change_status"]==1) 
 {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
 elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["pay_status"]!="") && $row[0]["final_status"]==0 && $row[0]["device_change_status"]==1)
 {echo "Pending Admin Approval";}
 elseif($row[0]["approve_status"]==1 && $row[0]["device_change_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
 elseif($row[0]["final_status"]==1){echo "Process Done";}?>
          </strong></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?php echo $row[0]["rdd_reason"];?></td>
      </tr>
      
      <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
      <tr>
        <td>Payment Pending </td>
        <td><?php echo $row[0]["pay_status"];?></td>
      </tr>
      <tr>
        <td>Account Comment</td>
        <td><?php echo $row[0]["account_comment"];?></td>
      </tr>
      <tr>
        <td>Service Comment</td>
        <td><?php echo $row[0]["service_comment"];?></td>
      </tr>
      <tr>
        <td>Support Comment</td>
        <td><?php echo $row[0]["support_comment"];?></td>
      </tr>
      <tr>
        <td>Admin Comment</td>
        <td><?php echo $row[0]["admin_comment"];?></td>
      </tr>
      <tr>
        <td>Req Forwarded to</td>
        <td><?php echo $row[0]["forward_req_user"];?></td>
      </tr>
      <tr>
        <td>Forward Comment</td>
        <td><?php echo $row[0]["forward_comment"];?></td>
      </tr>
      <tr>
        <td>F/W Request Back Comment</td>
        <td><?php echo $row[0]["forward_back_comment"];?></td>
      </tr>
      <tr>
        <td>Approval Date</td>
        <td><?php
		if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
		{
		echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
		}
		else
		{
			echo "";
		}
		
		?></td>
      </tr>
      <?php if($row[0]["close_comment"]!=""){?>
      <tr>
        <td>Duplicate Close Reason</td>
        <td><?php echo $row[0]["close_comment"];?></td>
      </tr>
      <?php } ?>
      <tr>
        <td>Closed Date</td>
        <td><?php
		if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
		{
		echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
		}
		else
		{
			echo "";
		}
		
		?></td>
      </tr>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="new_account_creation")
    {
    		$query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
			
			$ModelData = select_query("select * from new_account_model_master where is_active='0' and new_account_reqid='".$RowId."' ");
			$modelcount = count($ModelData);
			
			$oldModelData = select_query("select * from new_account_model_master where is_active='1' and new_account_reqid='".$RowId."' ");
			$oldmodelcount = count($oldModelData);
			
    ?>
<div id="databox">
  <div class="heading">New account creation</div>
  <div class="dataleft2">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date</td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <?php /*if($row[0]["account_manager"]=='saleslogin') {
		$account_manager=$row[0]["sales_manager"];
		}
		else {
		$account_manager=$row[0]["account_manager"];
		}*/
		?>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["account_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <tr>
          <td>Company</td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Potential</td>
          <td><?php echo $row[0]["potential"];?></td>
        </tr>
        <tr>
          <td>Contact Person</td>
          <td><?php echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Contact No.</td>
          <td><?php echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Billing Name</td>
          <td><?php echo $row[0]["billing_name"];?></td>
        </tr>
        <tr>
          <td>Billing Add</td>
          <td><?php echo $row[0]["billing_address"];?></td>
        </tr>
        <tr>
          <td>E-Mail ID</td>
          <td><?php echo $row[0]["email_id"];?></td>
        </tr>
        <tr>
          <td>User Name</td>
          <td><?php echo $row[0]["user_name"];?></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><?php echo $row[0]["user_password"];?></td>
        </tr>
        <tr>
          <td>Vehicle type</td>
          <td><?php echo $row[0]["vehicle_type"];?></td>
        </tr>
        <tr>
          <td>Dimts</td>
          <td><?php echo $row[0]["dimts"];?></td>
        </tr>
        <tr>
          <td>Dimts Fee status </td>
          <td><?php echo $row[0]["dimts_fee"];?></td>
        </tr>
        <tr>
          <td>Type of Organisation</td>
          <td><?php echo $row[0]["type_of_org"];?></td>
        </tr>      
       </tbody>
    </table>
  </div>
  <div class="dataright2">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>PAN No.</td>
          <td><?php echo $row[0]["pan_no"];?></td>
        </tr>        
        <tr>
          <td>Immobilizer</td>
          <td><?php echo $row[0]["immobilizer"];?></td>
        </tr>
        <tr>
          <td>AC</td>
          <td><?php echo $row[0]["ac_on_off"];?></td>
        </tr>
        <!-- <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["acc_creation_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["acc_creation_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && $row[0]["acc_creation_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["acc_creation_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["approve_status"]==1 && $row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
					</tr>
					<tr>
					  <td>Closed Date</td>
					  <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div>&nbsp;</div>
  <div class="datacenter">
	<table cellspacing="2" cellpadding="2" border="1">
      <tbody>
		<?php if($modelcount>0){?>
        <tr>
              <th align="left">SrNo.</th>
              <th align="left">DeviceType</th>
              <th align="left">modelType</th>
              <th align="left">AccountType</th>
              <th align="left">PaymentMode</th>
              <th align="left">RentPlan</th>
        </tr>
        <tr>
        	<td colspan="6" style="background-color:#FF6"><font style="color:#000;font-weight:bold;">Pending Model for Approval</font></td>
        </tr>
       <?php for($gm=0;$gm<$modelcount;$gm++){
				
			   if($ModelData[$gm]["rent_month"] == '1'){$plan = 'Monthly';}
			   elseif($ModelData[$gm]["rent_month"] == '3'){$plan = 'Quarterly';}
			   elseif($ModelData[$gm]["rent_month"] == '6'){$plan = 'HalfYearly';}
			   elseif($ModelData[$gm]["rent_month"] == '12'){$plan = 'Yearly';}
			   else{$plan = '--';}
			   
			   $getdevice = select_query("SELECT * FROM internalsoftware.device_type  WHERE id=".$ModelData[$gm]["device_type"]);
		?>
       <tr>
       		  <td><?php echo $gm+1;?></td>
              <td><strong><?php echo $getdevice[0]["device_type"];?></strong></td>
              <td><strong><?php echo $ModelData[$gm]["device_model"];?></strong></td>
              <td><strong><?php echo $ModelData[$gm]["account_type"];?></strong></td>
              <td><strong><?php echo $ModelData[$gm]["mode_of_payment"];?></strong></td>
              <td><strong><?php echo $plan;?></strong></td>
       </tr>
       <?php if($ModelData[$gm]["mode_of_payment"] == 'Billed'){ ?>
       <tr>    
              <td>&nbsp;</td>
              <td>DPrice - <?php echo $ModelData[$gm]["device_price"];?></td>
              <td>Status - <?php echo $ModelData[$gm]["device_status"];?></td>
              <td>Vat(5%) - <?php echo $ModelData[$gm]["device_price_vat"];?></td>
              <td>DTotal - <?php echo $ModelData[$gm]["device_price_total"];?></td>
        </tr>
        <tr>    
              <td>&nbsp;</td>
              <td>RPrice - <?php echo $ModelData[$gm]["device_rent_Price"];?></td>
              <td>Status - <?php echo $ModelData[$gm]["rent_status"];?></td>
              <td>STex(15%) - <?php echo $ModelData[$gm]["device_rent_service_tax"];?></td>
              <td>RTotal - <?php echo $ModelData[$gm]["DTotalREnt"];?> </td>
        </tr> 
       <?php } elseif($ModelData[$gm]["mode_of_payment"] == 'CashClient'){ ?> 
          <tr>    
              <td>&nbsp;</td>
              <td colspan="2">DPrice - <?php echo $ModelData[$gm]["device_price_total"];?></td>
              <td colspan="2">RPrice - <?php echo $ModelData[$gm]["DTotalREnt"];?></td>
          </tr>
        <?php } elseif($ModelData[$gm]["account_type"] == 'Foc'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="4">FOC Reason - <?php echo $ModelData[$gm]["foc_reason"];?></td>
          </tr>
       <?php } elseif($ModelData[$gm]["account_type"] == 'Demo'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="2">Demo Period - <?php echo $ModelData[$gm]["demo_time"]." Days";?></td>
          </tr>
       <?php } elseif($ModelData[$gm]["account_type"] == 'InternalTesting'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="2">Testing Period - <?php echo $ModelData[$gm]["testing_time"]." Days";?></td>
          </tr>
       <?php } 
	      } 
	   }
	   if($oldmodelcount>0){ 
	   ?>   
	   <tr>
        	<td colspan="6" style="background-color:#99FF66"><font style="color:#000;font-weight:bold;">Approved Model</font></td>
        </tr>
       <?php for($gd=0;$gd<$oldmodelcount;$gd++){
				
			   if($oldModelData[$gd]["rent_month"] == '1'){$plan = 'Monthly';}
			   elseif($oldModelData[$gd]["rent_month"] == '3'){$plan = 'Quarterly';}
			   elseif($oldModelData[$gd]["rent_month"] == '6'){$plan = 'HalfYearly';}
			   elseif($oldModelData[$gd]["rent_month"] == '12'){$plan = 'Yearly';}
			   else{$plan = '--';}
			   
			   $getdevice = select_query("SELECT * FROM internalsoftware.device_type  WHERE id=".$oldModelData[$gd]["device_type"]);
		?>
       <tr>
       		  <td><?php echo $gd+1;?></td>
              <td><strong><?php echo $getdevice[0]["device_type"];?></strong></td>
              <td><strong><?php echo $oldModelData[$gd]["device_model"];?></strong></td>
              <td><strong><?php echo $oldModelData[$gd]["account_type"];?></strong></td>
              <td><strong><?php echo $oldModelData[$gd]["mode_of_payment"];?></strong></td>
              <td><strong><?php echo $plan;?></strong></td>
       </tr>
       <?php if($oldModelData[$gd]["mode_of_payment"] == 'Billed'){ ?>
       <tr>    
              <td>&nbsp;</td>
              <td>DPrice - <?php echo $oldModelData[$gd]["device_price"];?></td>
              <td>Status - <?php echo $oldModelData[$gd]["device_status"];?></td>
              <td>Vat(5%) - <?php echo $oldModelData[$gd]["device_price_vat"];?></td>
              <td>DTotal - <?php echo $oldModelData[$gd]["device_price_total"];?></td>
        </tr>
        <tr>    
              <td>&nbsp;</td>
              <td>RPrice - <?php echo $oldModelData[$gd]["device_rent_Price"];?></td>
              <td>Status - <?php echo $oldModelData[$gd]["rent_status"];?></td>
              <td>STex(15%) - <?php echo $oldModelData[$gd]["device_rent_service_tax"];?></td>
              <td>RTotal - <?php echo $oldModelData[$gd]["DTotalREnt"];?> </td>
        </tr> 
       <?php } elseif($oldModelData[$gd]["mode_of_payment"] == 'CashClient'){ ?> 
          <tr>    
              <td>&nbsp;</td>
              <td colspan="2">DPrice - <?php echo $oldModelData[$gd]["device_price_total"];?></td>
              <td colspan="2">RPrice - <?php echo $oldModelData[$gd]["DTotalREnt"];?></td>
          </tr>
        <?php } elseif($oldModelData[$gd]["account_type"] == 'Foc'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="4">FOC Reason - <?php echo $oldModelData[$gd]["foc_reason"];?></td>
          </tr>
       <?php } elseif($oldModelData[$gd]["account_type"] == 'Demo'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="2">Demo Period - <?php echo $oldModelData[$gd]["demo_time"]." Days";?></td>
          </tr>
       <?php } elseif($oldModelData[$gd]["account_type"] == 'InternalTesting'){ ?>
		  <tr>    
              <td>&nbsp;</td>
              <td colspan="2">Testing Period - <?php echo $oldModelData[$gd]["testing_time"]." Days";?></td>
          </tr>
       <?php } 
	      } 	   
	   }
	   if($modelcount==0 && $oldmodelcount==0)
	   {
	   ?>
       <tr>
              <th align="left">AccountType</th>
              <th align="left">PaymentMode</th>
              <th align="left">DevicePrice</th>
              <th align="left">Total Price</th>
              <th align="left">Rent</th>
              <th align="left">Total Rent</th>
              <th align="left">RentMonth</th>
              <th align="left">RentStatus</th>
              <th align="left">DemoPeriod</th>
              <th align="left">FOCReason</th>
        </tr> 
       <tr>
              <td><strong><?php echo $row[0]["account_type"];?></strong></td>
              <td><strong><?php echo $row[0]["mode_of_payment"];?></strong></td>
              <td><?php echo $row[0]["device_price"];?></td>
              <td><?php echo $row[0]["device_price_total"];?></td>
              <td><?php echo $row[0]["device_rent_Price"];?></td>
              <td><?php echo $row[0]["DTotalREnt"];?></td>
              <td><?php if($row[0]["rent_month"]!=""){echo $row[0]["rent_month"]." Month";}?></td>
              <td><?php echo $row[0]["rent_status"];?></td>
              <td><?php if($row[0]["demo_time"]!=""){echo $row[0]["demo_time"];}?></td>
              <td><?php echo $row[0]["foc_reason"];?></td>
       </tr>
       <?php } ?>
       </tbody>
     </table>         
  </div>
</div>
<?php }
    else If($tablename=="imei_change")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">IMEI Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?php echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?php echo $row[0]["sales_manager"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?php echo $row[0]["client"];?></td>
      </tr>
      <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Veh Num</td>
        <td><?php echo $row[0]["vehicle"];?></td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?php echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI</td>
        <td><?php echo $row[0]["od_imei"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?php echo $row[0]["od_sim"];?></td>
      </tr>
      <tr>
        <td>Date of installation</td>
        <td><?php echo $row[0]["date_of_installation"];?></td>
      </tr>
      <tr>
        <td>Replaced IMEI Details</td>
        <td>---------------------------</td>
      </tr>
      <tr>
        <td>Device Model</td>
        <td><?php echo $row[0]["new_devicetype"];?></td>
      </tr>
      <tr>
        <td>IMEI</td>
        <td><?php echo $row[0]["new_device_imei"];?></td>
      </tr>
      <tr>
        <td>Device ID</td>
        <td><?php echo $row[0]["new_deviceid"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?php echo $row[0]["new_sim"];?></td>
      </tr>
      <tr>
        <td>Replace Date</td>
        <td><?php echo $row[0]["replace_date"];?></td>
      </tr>
      <tr>
        <td>Payment Status</td>
        <td><?php echo $row[0]["payment_status"];?></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?php echo $row[0]["reason"];?></td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Admin Approval</td>
          <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
					</tr>
					<tr>
					  <td>Closed Date</td>
					  <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }


    else If($tablename=="deactivate_sim")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Deactivate SIM</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?php echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?php echo $row[0]["sales_manager"];?></td>
      </tr>
      <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?php echo $row[0]["client"];?></td>
      </tr>
      <tr>
        <td>Veh No.</td>
        <td><?php echo $row[0]["vehicle"];?></td>
      </tr>
      <tr>
        <td>Device IMEI</td>
        <td><?php echo $row[0]["device_imei"];?></td>
      </tr>
      <tr>
        <td>Mobile Number</td>
        <td><?php echo $row[0]["device_sim"];?></td>
      </tr>
      <tr>
        <td>Present Status of Device</td>
        <td>---------------------------</td>
      </tr>
      <tr>
        <td>Location</td>
        <td><?php echo $row[0]["ps_of_location"];?></td>
      </tr>
      <tr>
        <td>Ownership</td>
        <td><?php echo $row[0]["ps_of_ownership"];?></td>
      </tr>
      <tr>
        <td>SIM Status</td>
        <td><?php echo $row[0]["sim_status"];?></td>
      </tr>
      <tr>
        <td>Change Date</td>
        <td><?php echo $row[0]["change_date"];?></td>
      </tr>
      <tr>
        <td>Reason</td>
        <td><?php echo $row[0]["replace_date"];?></td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["account_comment"]=="" && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
   
   
    else If($tablename=="comment")
        {
        //"select * from comment where service_id='".$service_id."' order by date desc"
         
    ?>
<div >
  <div style=" padding-left: 50px;">
    <h1>Comment</h1>
  </div>
  <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
      <tr>
        <td><?php

		$data=select_query_live_con("select * from matrix.comment where service_id='".$RowId."' order by date desc");
		 
		if(count($data)>0)
		{
		echo '<table cellspacing="0" cellpadding="0" border="1" width="100%" >
			 
				<tr  ><th>Date</th><th>Comment By</th><th>Comment</th></tr>';
		for($c=0;$c<count($data);$c++)
			{
		
		 echo '<tr ><td>'. $data[$c]["date"]. '</td><td>'. $data[$c]["comment_by"]. '</td><td>'. $data[$c]["comment"]. '</td></tr>';
			/*echo '<div>'. $data[$c]["date"]. '<div>';
			echo '<br/>';
			echo '<div>Comment By--'. $data[$c]["comment_by"]. '<div>';
			echo '<br/>';
			echo '<div>'. $data[$c]["comment"]. '<div>';
			//echo '<div align="right"><a href="?d=true&id='.$data[$c]["id"].'" >Remove </a></div>';
		
			echo '<hr>&nbsp;</hr>';*/
			}
			echo '</table>';
		
		 }
		 else
			{
			 echo '<div> No Comments<div>';
		
			echo '<hr>&nbsp;</hr>';
			}
		 ?></td>
      </tr>
    </table>
  </div>
</div>
<?php }

    elseIf($tablename=="new_device_addition")
        {

            $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">View New device Addition</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tr>
        <td>Date</td>
        <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
      </tr>
      <tr>
        <td>Request By</td>
        <td><?php echo $row[0]["acc_manager"];?></td>
      </tr>
      <tr>
        <td>Account Manager</td>
        <td><?php echo $row[0]["sales_manager"];?></td>
      </tr>
      <tr>
        <td>Company</td>
        <td><?php echo $row[0]["client"];?></td>
      </tr>
      <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Client User Name </td>
        <td><?php echo $rowuser[0]["sys_username"];?></td>
      </tr>
      <tr>
        <td>Vehicle Name</td>
        <td><?php echo $row[0]["vehicle_no"];?></td>
      </tr>
      <tr>
        <td>Device Type </td>
        <td><?php echo $row[0]["device_type"];?></td>
      </tr>
      <tr>
        <td>OLD Company Name </td>
        <td><?php echo $row[0]["old_device_client"];?></td>
      </tr>
      <tr>
        <td>OLD Registration No </td>
        <td><?php echo $row[0]["old_vehicle_name"];?></td>
      </tr>
      <tr>
        <td>Device Model </td>
        <td><?php echo $row[0]["device_model"];?></td>
      </tr>
      <tr>
        <td>Device IMEI </td>
        <td><?php echo $row[0]["device_imei"];?></td>
      </tr>
      <?php
		if($row[0]["device_type"]=='Old' || $row[0]["device_type"]=='old')
		{
			$Deviceid=$row[0]["old_device_id"];
		}
		else {
			$Deviceid=$row[0]["device_id"];
		}
		
		?>
      <tr>
        <td>Device ID </td>
        <td><?php echo $Deviceid;?></td>
      </tr>
      <tr>
        <td>Device Mobile Number </td>
        <td><?php echo $row[0]["device_sim_num"];?></td>
      </tr>
      <tr>
        <td>OLD Date Of Installation </td>
        <td><?php echo $row[0]["olddate_of_installation"];?></td>
      </tr>
      <?php /*if($row[0]["device_type"]=='New'){
		$biliing_status=$row[0]["billing"];
		}
		else{
		$biliing_status=$row[0]["billing_if_old_device"];
		}*/
    	?>
      <tr>
        <td>Billing</td>
        <td><?php echo $row[0]["billing"];?></td>
      </tr>
      <tr>
        <td>Billing Reason</td>
        <td><?php echo $row[0]["billing_if_no_reason"];?></td>
      </tr>
      <tr>
        <td>Installer</td>
        <td><?php echo $row[0]["inst_name"];?></td>
      </tr>
      <tr>
        <td>Dimts</td>
        <td><?php echo $row[0]["dimts"];?></td>
      </tr>
      <tr>
        <td>Immobilizer </td>
        <td><?php echo $row[0]["immobilizer"];?></td>
      </tr>
      <tr>
        <td>AC </td>
        <td><?php echo $row[0]["ac"];?></td>
      </tr>
      <tr>
        <td>Payment Plan</td>
        <td><?php if($row[0]["rent_payment"] == 1){ echo 'Monthly';}
      else if($row[0]["rent_payment"] == 3){ echo 'Quarterly';}
      else if($row[0]["rent_payment"] == 6){ echo 'HalfYearly';}
      else if($row[0]["rent_payment"] == 12){ echo 'Yearly';}?></td>
      </tr>
      <tr>
        <td>Demo Period</td>
        <td><?php echo $row[0]["demo_time"];?></td>
      </tr>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Payment Mode</td>
          <td><?php echo $row[0]["mode_of_payment"];?></td>
        </tr>
        <tr>
          <td>Device Price </td>
          <td><?php echo $row[0]["device_price"];?></td>
        </tr>
        <tr>
          <td>Vat(5%) </td>
          <td><?php echo $row[0]["device_price_vat"];?></td>
        </tr>
        <tr>
          <td>Total Amount </td>
          <td><?php echo $row[0]["device_price_total"];?></td>
        </tr>
        <tr>
          <td>Rent Price</td>
          <td><?php echo $row[0]["device_rent_Price"];?></td>
        </tr>
        <tr>
          <td>Service Tex(15%) </td>
          <td><?php echo $row[0]["device_rent_service_tax"];?></td>
        </tr>
        <tr>
          <td>Total Rent</td>
          <td><?php echo $row[0]["DTotalREnt"];?></td>
        </tr>
        <tr>
          <td>Rent Status</td>
          <td><?php echo $row[0]["rent_status"];?></td>
        </tr>
        <tr>
          <td>Rent Month</td>
          <td><?php echo $row[0]["rent_month"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending</strong></td>
          <td><strong>
            <?php  if($row[0]["new_device_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($row[0]["new_device_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
    </table>
  </div>
</div>
<?php }
    elseIf($tablename=="installer")
        {

          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div >
  <div style=" padding-left: 50px;">
    <h1>View Installer Contact Info</h1>
  </div>
  <div class="table">
    <table cellspacing="2" cellpadding="2" style=" padding-left: 100px;width: 500px;">
      <tr>
        <td>Installer Name</td>
        <td><?php echo $row[0]["inst_name"];?></td>
      </tr>
      <tr>
        <td>Address</td>
        <td><?php echo $row[0]["address"];?></td>
      </tr>
      <tr>
        <td>E-mail</td>
        <td><?php echo $row[0]["email"];?></td>
      </tr>
      <tr>
        <td>Mobile No. </td>
        <td><?php echo $row[0]["installer_mobile"];?></td>
      </tr>
      <?php $sql="select * FROM internalsoftware.gtrac_branch where id=".$row[0]["branch_id"];
    $rowuser=select_query($sql);
    ?>
      <tr>
        <td>Branch Name</td>
        <td><?php echo $rowuser[0]["branch_name"];?></td>
      </tr>
      <tr>
        <td>Status</td>
        <td><?php echo $row[0]["status"];?></td>
      </tr>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="vehicle_no_change")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">View Vehicle Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No</td>
          <td><?php echo $row[0]["old_reg_no"];?></td>
        </tr>
        <tr>
          <td>New Registration No </td>
          <td><?php echo $row[0]["new_reg_no"];?></td>
        </tr>
        <tr>
          <td>Billing</td>
          <td><?php echo $row[0]["billing"];?></td>
        </tr>
        <tr>
          <td>Billing Reason</td>
          <td><?php echo $row[0]["billing_reason"];?></td>
        </tr>
        <tr>
          <td>Date </td>
          <td><?php echo $row[0]["numberchange_date"];?></td>
        </tr>
        <tr>
          <td>Vehicle No Change Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Client Request Reason </td>
          <td><?php echo $row[0]["vehicle_reason"];?></td>
        </tr>
        <tr>
          <td>Installer Name </td>
          <td><?php echo $row[0]["inst_name"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["reason"]=='Temperory no to Permanent no' || $row[0]["reason"]=='Personal no to Commercial no' || $row[0]["reason"]=='Commercial no to Personal no' || $row[0]["reason"]=='For Warranty Renuwal Purpose')
    {
        if($row[0]["vehicle_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["vehicle_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
    }
    else{
        if($row[0]["vehicle_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["new_reg_no"]=="" && $row[0]["reason"]=="" && $row[0]["approve_status"]==0){echo "Request Not Completely Generate.";}
        elseif($row[0]["account_comment"]=="" && $row[0]["payment_status"]=="" && $row[0]["reason"]!="" && $row[0]["approve_status"]==0){echo "Pending at Accounts";}
        elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["vehicle_status"]==1)   
        {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
        elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["payment_status"]!="") && $row[0]["final_status"]==0 && $row[0]["vehicle_status"]==1)
        {echo "Pending Admin Approval";}
        elseif($row[0]["approve_status"]==1 && $row[0]["vehicle_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
    } ?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment status</td>
          <td><?php echo $row[0]["payment_status"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <?php if($row[0]["close_comment"]!=""){?>
        <tr>
          <td>Duplicate Close Reason</td>
          <td><?php echo $row[0]["close_comment"];?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="dimts_imei")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">View Dimts IMEI Details</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Sales Manager </td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Vehicle No</td>
          <td><?php echo $row[0]["veh_reg"];?></td>
        </tr>
        <tr>
          <td>7 digit IMEI </td>
          <td><?php echo $row[0]["device_imei_7"];?></td>
        </tr>
        <tr>
          <td>15 digit IMEI</td>
          <td><?php echo $row[0]["device_imei_15"];?></td>
        </tr>
        <tr>
          <td>Changed to Port</td>
          <td><?php echo $row[0]["port_change"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!-- <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["dimts_status"]==2 || (($row[0]["imei_upload_reason"]!="" || $row[0]["admin_comment"]!="") && $row[0]["support_comment"]=="" && $row[0]["service_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["payment_status"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["dimts_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && $row[0]["payment_status"]!="" && $row[0]["final_status"]==0 && $row[0]["dimts_status"]==1)
    {echo "Pending Admin Approval";}
    elseif(($row[0]["approve_status"]==1 || $row[0]["account_comment"]!="") && $row[0]["dimts_status"]==1 && $row[0]["support_comment"]=="" && $row[0]["final_status"]!=1){echo "Pending at Tech Support for IMEI Uplode";}
    elseif(($row[0]["approve_status"]==1 || $row[0]["account_comment"]!="") && $row[0]["dimts_status"]==1 && $row[0]["repair_comment"]=="" && $row[0]["final_status"]!=1){echo "Pending at Repair For Port Change";}
    elseif($row[0]["support_comment"]!="" && $row[0]["repair_comment"]!="" && $row[0]["final_status"]!=1){echo "Process Not Closed Request End";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["payment_status"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Reason for Imei not uploading</td>
          <td><?php echo $row[0]["imei_upload_reason"];?></td>
        </tr>
        <tr>
          <td>Repair Comment</td>
          <td><?php echo $row[0]["repair_comment"];?></td>
        </tr>
        <tr>
          <td>Reason for Port not changing</td>
          <td><?php echo $row[0]["port_change_reason"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="renew_dimts_imei")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

 
 
    ?>
<div id="databox">
  <div class="heading">View Renew Dimts IMEI Details</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Sales Manager </td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Vehicle No</td>
          <td><?php echo $row[0]["veh_reg"];?></td>
        </tr>
        <tr>
          <td>7 digit IMEI </td>
          <td><?php echo $row[0]["device_imei_7"];?></td>
        </tr>
        <tr>
          <td>15 digit IMEI</td>
          <td><?php echo $row[0]["device_imei_15"];?></td>
        </tr>
        <tr>
          <td>Changed to Port</td>
          <td><?php echo $row[0]["port_change"];?></td>
        </tr>
        <tr>
          <td>Port Change Status</td>
          <td><?php echo $row[0]["port_change_status"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["renew_dimts_status"]==2 || ( $row[0]["admin_comment"]!="" && $row[0]["service_comment"]=="")){echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["payment_status"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["renew_dimts_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && $row[0]["payment_status"]!="" && $row[0]["final_status"]==0 && $row[0]["renew_dimts_status"]==1)
    {echo "Pending Admin Approval";}
    elseif(($row[0]["approve_status"]==1 || $row[0]["account_comment"]!="") && $row[0]["renew_dimts_status"]==1 && $row[0]["repair_comment"]=="" && $row[0]["port_change_status"]=="Yes" && $row[0]["final_status"]!=1){echo "Pending at Repair For Port Change";}
    elseif(($row[0]["repair_comment"]!="" || ($row[0]["port_change_status"]!="Yes" && ($row[0]["approve_status"]==1 || $row[0]["account_comment"]!="")))&& $row[0]["final_status"]!=1){echo "Process Not Closed Request End";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["payment_status"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <!--<tr><td>Support Comment</td><td><?php echo $row[0]["support_comment"];?></td></tr>
<tr><td>Reason for Imei not uploading</td><td><?php echo $row[0]["imei_upload_reason"];?></td></tr>-->
        <tr>
          <td>Repair Comment</td>
          <td><?php echo $row[0]["repair_comment"];?></td>
        </tr>
        <tr>
          <td>Reason for Port not changing</td>
          <td><?php echo $row[0]["port_change_reason"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="sim_change")
        {
            $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">View Mobile Number Change</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No</td>
          <td><?php echo $row[0]["reg_no"];?></td>
        </tr>
        <tr>
          <td>Old Mobile Number </td>
          <td><?php echo $row[0]["old_sim"];?></td>
        </tr>
        <tr>
          <td>New Mobile Number </td>
          <td><?php echo $row[0]["new_sim"];?></td>
        </tr>
        <tr>
          <td>Sim Change Date </td>
          <td><?php echo $row[0]["sim_change_date"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Installer Name </td>
          <td><?php echo $row[0]["inst_name"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Support Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php     if($row[0]["sim_change_status"]==2 || ($row[0]["support_comment"]!="" && $row[0]["service_comment"]==""))
        {echo "Reply Pending at Request Side";}
        elseif($row[0]["sim_change_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
        elseif($row[0]["final_status"]==1){echo "Process Done";}
     ?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <?php if($row[0]["close_comment"]!=""){?>
        <tr>
          <td>Duplicate Close Reason</td>
          <td><?php echo $row[0]["close_comment"];?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="device_lost")
        {
            $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?>
<div id="databox">
  <div class="heading">View Device Lost</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date</td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name</td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?php echo $row[0]["odd_reg_no"];?></td>
        </tr>
        <tr>
          <td>Device Model </td>
          <td><?php echo $row[0]["odd_device_model"];?></td>
        </tr>
        <tr>
          <td>Device IMEI </td>
          <td><?php echo $row[0]["odd_imei"];?></td>
        </tr>
        <tr>
          <td>Device Mobile Number </td>
          <td><?php echo $row[0]["odd_sim"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation </td>
          <td><?php echo $row[0]["odd_instaltion_date"];?></td>
        </tr>
        <tr>
          <td>New Device Detail:---</td>
          <td></td>
        </tr>
        <tr>
          <td>Device Model </td>
          <td><?php echo $row[0]["ndd_device_model"];?></td>
        </tr>
        <tr>
          <td>Device Id </td>
          <td><?php echo $row[0]["ndd_device_id"];?></td>
        </tr>
        <tr>
          <td>Device IMEI</td>
          <td><?php echo $row[0]["ndd_imei"];?></td>
        </tr>
        <tr>
          <td>Device Mobile Number </td>
          <td><?php echo $row[0]["ndd_sim"];?></td>
        </tr>
        <tr>
          <td>Date</td>
          <td><?php echo $row[0]["newdevice_addeddate"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["device_lost_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["service_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["odd_paid_unpaid"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["device_lost_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["odd_paid_unpaid"]!="") && $row[0]["final_status"]==0 && $row[0]["device_lost_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["device_lost_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Old Device Paid or Not</td>
          <td><?php echo $row[0]["odd_paid_unpaid"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php
    }


    else If($tablename=="deletion")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?>
<div id="databox">
  <div class="heading">Deletion Vehicle</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>date</td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <tr>
          <td>Registration No </td>
          <td><?php echo $row[0]["reg_no"];?></td>
        </tr>
        <tr>
          <td>Device Model </td>
          <td><?php echo $row[0]["device_model"];?></td>
        </tr>
        <tr>
          <td>Device IMEI </td>
          <td><?php echo $row[0]["imei"];?></td>
        </tr>
        <tr>
          <td>Device Mobile Number </td>
          <td><?php echo $row[0]["device_sim_no"];?></td>
        </tr>
        <tr>
          <td>Date Of Installation </td>
          <td><?php echo $row[0]["date_of_installation"];?></td>
        </tr>
        <tr>
          <td>Present Status of device</td>
          <td>----------------------</td>
        </tr>
        <tr>
          <td>Device Status</td>
          <td><?php echo $row[0]["device_status"];?></td>
        </tr>
        <tr>
          <td>Device Location </td>
          <td><?php echo $row[0]["vehicle_location"];?></td>
        </tr>
        <tr>
          <td>Contact person </td>
          <td><?php echo $row[0]["Contact_person"];?></td>
        </tr>
        <tr>
          <td>Deactivation of SIM </td>
          <td><?php echo $row[0]["deactivation_of_sim"];?></td>
        </tr>
        <tr>
          <td>Deletion date </td>
          <td><?php echo $row[0]["deletion_date"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["delete_veh_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["service_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["vehicle_location"]=="gtrack office" && $row[0]["stock_comment"]==""){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["odd_paid_unpaid"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["delete_veh_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["odd_paid_unpaid"]!="") && $row[0]["final_status"]==0 && $row[0]["delete_veh_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["delete_veh_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Old Device Paid or Not</td>
          <td><?php echo $row[0]["odd_paid_unpaid"];?></td>
        </tr>
        <tr>
          <td>Service Comment</td>
          <td><?php echo $row[0]["service_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }


    else If($tablename=="stop_gps")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Stop Gps</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["tot_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Vehicle to Stop GPS </td>
          <td><?php echo $row[0]["reg_no"];?></td>
        </tr>
        <tr>
          <td>Persent Status Of</td>
          <td>:---</td>
        </tr>
        <tr>
          <td>Location </td>
          <td><?php echo $row[0]["ps_of_location"];?></td>
        </tr>
        <tr>
          <td>OwnerShip </td>
          <td><?php echo $row[0]["ps_of_ownership"];?></td>
        </tr>
        <tr>
          <td>Data to Display </td>
          <td><?php echo $row[0]["data_display"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["stop_gps_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["stop_gps_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["stop_gps_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["stop_gps_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }


    else If($tablename=="start_gps")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Start Gps</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["tot_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Persent Status Of</td>
          <td>:---</td>
        </tr>
        <tr>
          <td>OwnerShip </td>
          <td><?php echo $row[0]["ps_of_ownership"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Vehicle to Start GPS </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["start_gps_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["start_gps_status"]==1)    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["start_gps_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["start_gps_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }


elseIf($tablename=="installation")
        {
   
    $query = "select * FROM internalsoftware.installation left join internalsoftware.re_city_spr_1 on installation.Zone_area=re_city_spr_1.id 
	where installation.id=".$RowId;
    $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Installation</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date: </td>
          <td><?php echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By: </td>
          <td><?php echo $row[0]["request_by"];?></td>
        </tr>
        <?php 
		$sales=select_query("select name FROM internalsoftware.sales_person where id='".$row[0]['sales_person']."' ");
		?>
        <tr>
          <td>Sales Person </td>
          <td><?php echo $sales[0]['name'];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
		$rowuser=select_query($sql);
		?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>No. Of Vehicales: </td>
          <td><?php echo $row[0]["no_of_vehicals"];?></td>
        </tr>
        <tr>
          <td>Approve Installation: </td>
          <td><?php echo $row[0]["installation_approve"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?php echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?php echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= select_query("select * FROM internalsoftware.tbl_city_name where branch_id='".$row[0]['inter_branch']."'");?>
        <tr>
          <td>Location: </td>
          <td><?php echo $city[0]["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Model:</td>
          <td><?php echo $row[0]["model"];?></td>
        </tr>
        <tr>
          <td>Available Time Status: </td>
          <td><?php echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Time: </td>
          <td><?php echo $row[0]["time"];?></td>
        </tr>
        <tr>
          <td>To Time: </td>
          <td><?php echo $row[0]["totime"];?></td>
        </tr>
        <tr>
          <td>Contact No.:</td>
          <td><?php echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Contact Person: </td>
          <td><?php echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Payment Mode</td>
          <td><?php echo $row[0]["mode_of_payment"];?></td>
        </tr>
        <tr>
          <td>Demo Period</td>
          <td><?php echo $row[0]["demo_time"];?></td>
        </tr>
        <tr>
          <td>Device Price </td>
          <td><?php echo $row[0]["device_price"];?></td>
        </tr>
        <tr>
          <td>Vat(5%) </td>
          <td><?php echo $row[0]["device_price_vat"];?></td>
        </tr>
        <tr>
          <td>Total Amount </td>
          <td><?php echo $row[0]["device_price_total"];?></td>
        </tr>
        <tr>
          <td>Rent Price</td>
          <td><?php echo $row[0]["device_rent_Price"];?></td>
        </tr>
        <tr>
          <td>Service Tex(15%) </td>
          <td><?php echo $row[0]["device_rent_service_tax"];?></td>
        </tr>
        <tr>
          <td>Total Rent</td>
          <td><?php echo $row[0]["DTotalREnt"];?></td>
        </tr>
        <tr>
          <td>Rent Status</td>
          <td><?php echo $row[0]["rent_status"];?></td>
        </tr>
        <tr>
          <td>Rent Month</td>
          <td><?php echo $row[0]["rent_month"];?></td>
        </tr>
        <tr>
          <td>Payment Plan</td>
          <td><?php if($row[0]["rent_payment"] == 1){ echo 'Monthly';}
			  else if($row[0]["rent_payment"] == 3){ echo 'Quarterly';}
			  else if($row[0]["rent_payment"] == 6){ echo 'HalfYearly';}
			  else if($row[0]["rent_payment"] == 12){ echo 'Yearly';}?></td>
        </tr>
        <tr>
          <td>DIMTS: </td>
          <td><?php echo $row[0]["dimts"];?></td>
        </tr>
        <tr>
          <td>Demo: </td>
          <td><?php echo $row[0]["demo"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?php echo $row[0]["instal_reinstall"];?></td>
        </tr>
        <tr>
          <td>Vehicle Type: </td>
          <td><?php echo $row[0]["veh_type"];?></td>
        </tr>
        <tr>
          <td>Immobilizer: </td>
          <td><?php echo $row[0]["immobilizer_type"];?></td>
        </tr>
        <tr>
          <td>Comment: </td>
          <td><?php echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Payment: </td>
          <td><?php echo $row[0]["payment_req"];?></td>
        </tr>
        <tr>
          <td>Fuel Sensor: </td>
          <td><?php echo $row[0]["fuel_sensor"];?></td>
        <tr>
          <td>Bonnet Sensor: </td>
          <td><?php echo $row[0]["bonnet_sensor"];?></td>
        <tr>
          <td>RFID Reader: </td>
          <td><?php echo $row[0]["rfid_reader"];?></td>
        <tr>
          <td>Speed Alarm: </td>
          <td><?php echo $row[0]["speed_alarm"];?></td>
        <tr>
          <td>Amount: </td>
          <td><?php echo $row[0]["amount"];?></td>
        <tr>
          <td>Payment Mode: </td>
          <td><?php echo $row[0]["pay_mode"];?></td>
        <tr>
          <td>Required.:</td>
          <td><?php echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box.: </td>
          <td><?php echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Door lock/unlock circuit: </td>
          <td><?php echo $row[0]["door_lock_unlock"];?></td>
        <tr>
          <td>Temperature Sensor: </td>
          <td><?php echo $row[0]["temperature_sensor"];?></td>
        <tr>
          <td>Duty Box: </td>
          <td><?php echo $row[0]["duty_box"];?></td>
        <tr>
          <td>Panic Button: </td>
          <td><?php echo $row[0]["panic_button"];?></td>
        </tr>
        <tr>
          <td>Contact Person No.: </td>
          <td><?php echo $row[0]["contact_person_no"];?></td>
        </tr>
        <tr>
          <td>Installation Made: </td>
          <td><?php echo $row[0]["installation_made"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?php echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?php echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?php echo $row[0]["inst_cur_location"];?></td></tr>  
-->
        <tr>
          <td>Installation Done At: </td>
          <td><?php echo $row[0]["rtime"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?php echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["installation_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
				elseif($row[0]["installation_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
				elseif($row[0]["approve_status"]==0 && $row[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
				elseif($row[0]["installation_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
				elseif($row[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
				elseif($row[0]["installation_status"]==2 ){echo "Assign To Installer";}
				elseif($row[0]["installation_status"]==11 ){echo "Request Forward to Repair Team";}
				elseif($row[0]["installation_status"]==3 ){echo "Back Installation";}
				elseif($row[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
				elseif($row[0]["installation_status"]==5 || $row[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        </tr>
        <?php if($_SESSION['BranchId']==1){?>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
		   
			?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php }


elseIf($tablename=="installation_request")
        {
   
    $query = "select * FROM internalsoftware.installation_request left join internalsoftware.re_city_spr_1 on 
	installation_request.Zone_area =re_city_spr_1.id where installation_request.id=".$RowId;
    $row=select_query($query);
    ?>
<div id="databox">
  <div class="heading">Installation Request</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td> Date: </td>
          <td><?php echo $row[0]["req_date"];?></td>
        </tr>
        <tr>
          <td>Request By: </td>
          <td><?php echo $row[0]["request_by"];?></td>
        </tr>
        <?php 
		$sales=select_query("select name FROM internalsoftware.sales_person where id='".$row[0]['sales_person']."' ");
		?>
        <tr>
          <td>Sales Person </td>
          <td><?php echo $sales[0]['name'];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
		$rowuser=select_query($sql);
		?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company_name"];?></td>
        </tr>
        <tr>
          <td>No. Of Vehicales: </td>
          <td><?php echo $row[0]["no_of_vehicals"];?></td>
        </tr>
        <tr>
          <td>Approve Installation: </td>
          <td><?php echo $row[0]["installation_approve"];?></td>
        </tr>
        <tr>
          <td>Area: </td>
          <td><?php echo $row[0]["name"];?></td>
        </tr>
        <?php if($row[0]['location']!=""){?>
        <tr>
          <td>Location: </td>
          <td><?php echo $row[0]["location"];?></td>
        </tr>
        <?php }else{ $city= select_query("select * FROM internalsoftware.tbl_city_name where branch_id='".$row[0]['inter_branch']."'");?>
        <tr>
          <td>Location: </td>
          <td><?php echo $city[0]["city"];?></td>
        </tr>
        <?php }?>
        <tr>
          <td>Model:</td>
          <td><?php echo $row[0]["model"];?></td>
        </tr>
        <tr>
          <td>Available Time Status: </td>
          <td><?php echo $row[0]["atime_status"];?></td>
        </tr>
        <tr>
          <td>Time: </td>
          <td><?php echo $row[0]["time"];?></td>
        </tr>
        <tr>
          <td>To Time: </td>
          <td><?php echo $row[0]["totime"];?></td>
        </tr>
        <tr>
          <td>Contact No.:</td>
          <td><?php echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Contact Person: </td>
          <td><?php echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Payment Mode</td>
          <td><?php echo $row[0]["mode_of_payment"];?></td>
        </tr>
        <tr>
          <td>Demo Period</td>
          <td><?php echo $row[0]["demo_time"];?></td>
        </tr>
        <tr>
          <td>Device Price </td>
          <td><?php echo $row[0]["device_price"];?></td>
        </tr>
        <tr>
          <td>Vat(5%) </td>
          <td><?php echo $row[0]["device_price_vat"];?></td>
        </tr>
        <tr>
          <td>Total Amount </td>
          <td><?php echo $row[0]["device_price_total"];?></td>
        </tr>
        <tr>
          <td>Rent Price</td>
          <td><?php echo $row[0]["device_rent_Price"];?></td>
        </tr>
        <tr>
          <td>Service Tex(15%) </td>
          <td><?php echo $row[0]["device_rent_service_tax"];?></td>
        </tr>
        <tr>
          <td>Total Rent</td>
          <td><?php echo $row[0]["DTotalREnt"];?></td>
        </tr>
        <tr>
          <td>Rent Status</td>
          <td><?php echo $row[0]["rent_status"];?></td>
        </tr>
        <tr>
          <td>Rent Month</td>
          <td><?php echo $row[0]["rent_month"];?></td>
        </tr>
        <tr>
          <td>Payment Plan</td>
          <td><?php if($row[0]["rent_payment"] == 1){ echo 'Monthly';}
			  else if($row[0]["rent_payment"] == 3){ echo 'Quarterly';}
			  else if($row[0]["rent_payment"] == 6){ echo 'HalfYearly';}
			  else if($row[0]["rent_payment"] == 12){ echo 'Yearly';}?></td>
        </tr>
        <tr>
          <td>DIMTS: </td>
          <td><?php echo $row[0]["dimts"];?></td>
        </tr>
        <tr>
          <td>Demo: </td>
          <td><?php echo $row[0]["demo"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Job: </td>
          <td><?php echo $row[0]["instal_reinstall"];?></td>
        </tr>
        <tr>
          <td>Vehicle Type: </td>
          <td><?php echo $row[0]["veh_type"];?></td>
        </tr>
        <tr>
          <td>Immobilizer: </td>
          <td><?php echo $row[0]["immobilizer_type"];?></td>
        </tr>
        <tr>
          <td>Comment: </td>
          <td><?php echo $row[0]["comment"];?></td>
        </tr>
        <tr>
          <td>Payment: </td>
          <td><?php echo $row[0]["payment_req"];?></td>
        </tr>
        <tr>
          <td>Fuel Sensor: </td>
          <td><?php echo $row[0]["fuel_sensor"];?></td>
        <tr>
          <td>Bonnet Sensor: </td>
          <td><?php echo $row[0]["bonnet_sensor"];?></td>
        <tr>
          <td>RFID Reader: </td>
          <td><?php echo $row[0]["rfid_reader"];?></td>
        <tr>
          <td>Speed Alarm: </td>
          <td><?php echo $row[0]["speed_alarm"];?></td>
        <tr>
          <td>Amount: </td>
          <td><?php echo $row[0]["amount"];?></td>
        <tr>
          <td>Payment Mode: </td>
          <td><?php echo $row[0]["pay_mode"];?></td>
        <tr>
          <td>Required.:</td>
          <td><?php echo $row[0]["required"];?></td>
        </tr>
        <tr>
          <td>IP Box.: </td>
          <td><?php echo $row[0]["IP_Box"];?></td>
        </tr>
        <tr>
          <td>Door lock/unlock circuit: </td>
          <td><?php echo $row[0]["door_lock_unlock"];?></td>
        <tr>
          <td>Temperature Sensor: </td>
          <td><?php echo $row[0]["temperature_sensor"];?></td>
        <tr>
          <td>Duty Box: </td>
          <td><?php echo $row[0]["duty_box"];?></td>
        <tr>
          <td>Panic Button: </td>
          <td><?php echo $row[0]["panic_button"];?></td>
        </tr>
        <tr>
          <td>Contact Person No.: </td>
          <td><?php echo $row[0]["contact_person_no"];?></td>
        </tr>
        <tr>
          <td>Installation Made: </td>
          <td><?php echo $row[0]["installation_made"];?></td>
        </tr>
        <tr>
          <td>Installer Name: </td>
          <td><?php echo $row[0]["inst_name"];?></td>
        </tr>
        <tr>
          <td>Installer Current Location: </td>
          <td><?php echo $row[0]["inst_cur_location"];?></td>
        </tr>
        <!--<tr><td>Change Installer Name: </td><td><?php echo $row[0]["inst_cur_location"];?></td></tr>  
-->
        <tr>
          <td>Installation Done At: </td>
          <td><?php echo $row[0]["rtime"];?></td>
        </tr>
        <tr>
          <td>Reason To Back Services:</td>
          <td><?php echo $row[0]["back_reason"];?></td>
        </tr>
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["installation_status"]==7 && ($row[0]["admin_comment"]!="" || $row[0]["sales_comment"]=="")){echo "Reply Pending at Request Side";}
				elseif($row[0]["installation_status"]==7 && $row[0]["admin_comment"]=="" ){echo "Pending Saleslogin for Information";}
				elseif($row[0]["approve_status"]==0 && $row[0]["installation_status"]==8 ){echo "Pending Admin Approval";}
				elseif($row[0]["installation_status"]==9 && $row[0]["approve_status"]==1 ){echo "Pending Confirmation at Request Person";}
				elseif($row[0]["installation_status"]==1 ){echo "Pending Dispatch Team";}
				elseif($row[0]["installation_status"]==2 ){echo "Assign To Installer";}
				elseif($row[0]["installation_status"]==11 ){echo "Request Forward to Repair Team";}
				elseif($row[0]["installation_status"]==3 ){echo "Back Installation";}
				elseif($row[0]["installation_status"]==15 ){echo "Pending Remaining Installation";}
				elseif($row[0]["installation_status"]==5 || $row[0]["installation_status"]==6){echo "Installation Close";}?>
            </strong></td>
        </tr>
        <?php if($_SESSION['BranchId']==1 || $row[0]["inter_branch"]==1){?>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td>Approval Date</td>
          <td><?php
    if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
    {
    echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
    }
    else
    {
        echo "";
    }
   
    ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php }
else If($tablename=="no_bills")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
           
           
    ?>
<div id="databox">
  <div class="heading">No Bills</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["client"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company_name"];?></td>
        </tr>
        <!--<tr><td>Vehicle Num</td><td><?php echo $row[0]["reg_no"];?></td></tr>-->
        
        <tr>
          <td>Vehicle Num </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>Total no of Vehicles </td>
          <td><?php echo $row[0]["tot_no_of_vehicles"];?></td>
        </tr>
        <tr>
          <td>Vehicles for no bill</td>
          <td><?php echo $row[0]["veh_no_bill"];?></td>
        </tr>
        <tr>
          <td>No Bill For </td>
          <td><?php echo $row[0]["rent_device"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Provision Bill </td>
          <td><?php echo $row[0]["provision_bill"];?></td>
        </tr>
        <tr>
          <td>Duration for Provision Bill </td>
          <td><?php echo $row[0]["duration"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["no_bill_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif(($row[0]["no_bill_issue"]=="Software Issue" && $row[0]["support_comment"]=="") || $row[0]["approve_status"]==1 && $row[0]["no_bill_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["no_bill_issue"]=="Service Issue" && $row[0]["no_bill_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["service_comment"]=="")
    {echo "Pending at Service Team";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["no_bill_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["no_bill_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
     
 
    else If($tablename=="discount_details")
        {
          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);
           
           
    ?>
<div id="databox">
  <div class="heading">Discount</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["client"];?></td>
        </tr>
        <!--<tr><td>Vehicle    for discount</td><td><?php echo $row[0]["reg_no"];?></td></tr>-->
        <tr>
          <td>Vehicle    for discount </td>
          <td><?php $vechile_no = explode(",",$row[0]["reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>Discount For</td>
          <td><?php echo $row[0]["rent_device"];?></td>
        </tr>
        <tr>
          <td>Month</td>
          <td><?php echo $row[0]["mon_of_dis_in_case_of_rent"];?></td>
        </tr>
        <tr>
          <td>Discount Amount </td>
          <td><?php echo $row[0]["dis_amt"];?></td>
        </tr>
        <tr>
          <td>After Discount </td>
          <td><?php echo $row[0]["amt_rec_after_dis"];?></td>
        </tr>
        <tr>
          <td>Before Discount </td>
          <td><?php echo $row[0]["amt_before_dis"];?></td>
        </tr>
        <tr>
          <td>Reason </td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
        <tr>
          <td>Service Action</td>
          <td><?php echo $row[0]["service_action"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["discount_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["discount_issue"]=="Software Issue" && $row[0]["approve_status"]==0 && $row[0]["software_comment"]=="" && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["discount_status"]==1){echo "Pending at Tech Support Login (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["discount_issue"]=="Software Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["software_comment"]=="")
    {echo "Pending at Tech Support Login";}
    elseif($row[0]["discount_issue"]=="Repair Cost Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["repair_comment"]=="")
    {echo "Pending at Repair Login";}
    elseif($row[0]["discount_issue"]=="Service Issue" && $row[0]["discount_status"]==1 && $row[0]["approve_status"]!=1 && $row[0]["service_comment"]=="")
    {echo "Pending at Service Support Login";}   
    elseif($row[0]["discount_status"]==1 && $row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]!=1){echo "Pending at Account Login";}   
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["discount_status"]==1)    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["discount_status"]==1)
    {echo "Pending Admin Approval";}       
    elseif($row[0]["approve_status"]==1 && $row[0]["final_status"]==0){echo "Pending at Account For Discounting";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php /*}
    else If($tablename=="dimts_imei")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);*/

 
 
    ?>
<!--<div id="databox">
<div class="heading">View Dimts IMEI Details</div>
<div class="dataleft"><table cellspacing="2" cellpadding="2">
    <tbody>
 
<tr><td>Date </td><td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td></tr>    
<tr><td>Request By</td><td><?php echo $row[0]["acc_manager"];?></td></tr>
<tr><td>Sales Manager </td><td><?php echo $row[0]["sales_manager"];?></td></tr>    
<?php /*$sql="select * from matrix.users  where id=".$row[0]["user_id"];
    $rowuser=select_query($sql);*/
    ?>
<tr><td>User Name     </td><td><?php echo $rowuser[0]["sys_username"];?></td></tr>   
<tr><td>Company Name </td><td><?php echo $row[0]["client"];?></td></tr>       
<tr><td>Vehicle No</td><td><?php echo $row[0]["veh_reg"];?></td></tr>        
<tr><td>7 digit IMEI </td><td><?php echo $row[0]["device_imei_7"];?></td></tr>   
<tr><td>15 digit IMEI</td><td><?php echo $row[0]["device_imei_15"];?></td></tr>
<tr><td>Changed to Port</td><td><?php echo $row[0]["port_change"];?></td></tr>


</tbody></table></div>
 
<div class="dataright">
<table cellspacing="2" cellpadding="2"><tbody>
 <tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>
 <tr><td>Admin Comment</td><td><?php echo $row[0]["admin_comment"];?></td></tr>
<tr><td>Req Forwarded to</td><td><?php echo $row[0]["forward_req_user"];?></td></tr>
<tr><td>Forward Comment</td><td><?php echo $row[0]["forward_comment"];?></td></tr>
<tr><td>F/W Request Back Comment</td><td><?php echo $row[0]["forward_back_comment"];?></td></tr><tr><td>Account Comment</td>  <td><?php echo $row[0]["account_comment"];?></td></tr>
 <tr><td>Payment Pending</td>  <td><?php echo $row[0]["payment_status"];?></td></tr>
<tr><td>Service Comment</td>  <td><?php echo $row[0]["service_comment"];?></td></tr>
<tr><td>Support Comment</td><td><?php echo $row[0]["support_comment"];?></td></tr>
<tr><td>Reason for Imei not uploading</td><td><?php echo $row[0]["imei_upload_reason"];?></td></tr>
<tr><td>Repair Comment</td><td><?php echo $row[0]["repair_comment"];?></td></tr>
<tr><td>Reason for Port not changing</td><td><?php echo $row[0]["port_change_reason"];?></td></tr>
<tr><td>Approval Date</td><td><?php
/*if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
}
else
{
    echo "";
}*/

?></td></tr>
<tr><td>Closed Date</td><td><?php
/*if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
{
echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
}
else
{
    echo "";
}*/

?></td>
    </tr></tbody>

    </table>
    </div>
    </div>     -->

<?php }
    elseIf($tablename=="sub_user_creation")
        {

          $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">Sub User Creation</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["main_user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["tot_no_of_vehicles"];?></td>
        </tr>
        <tr>
          <td>Vehicle to move </td>
          <td><?php echo $row[0]["reg_no_of_vehicle_to_move"];?></td>
        </tr>
        <tr>
          <td>Contact Person </td>
          <td><?php echo $row[0]["contact_person"];?></td>
        </tr>
        <tr>
          <td>Contact Number </td>
          <td><?php echo $row[0]["contact_number"];?></td>
        </tr>
        <tr>
          <td>Sub-User Name </td>
          <td><?php echo $row[0]["name"];?></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><?php echo $row[0]["req_sub_user_pass"];?></td>
        </tr>
        <tr>
          <td>Main User Separate</td>
          <td><?php echo $row[0]["billing_separate"];?></td>
        </tr>
        <tr>
          <td>Billing Name</td>
          <td><?php echo $row[0]["billing_name"];?></td>
        </tr>
        <tr>
          <td>Billing Address</td>
          <td><?php echo $row[0]["billing_address"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["sub_user_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["sub_user_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && $row[0]["final_status"]==0 && $row[0]["sub_user_status"]==1){echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["sub_user_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="deactivation_of_account")
        {
         $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">Deactivation Of Account</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["total_no_of_vehicles"];?></td>
        </tr>
        <tr>
          <td>Deactivate </td>
          <td><?php echo $row[0]["deactivate_temp"];?></td>
        </tr>
        <tr>
          <td>Alert Date </td>
          <td><?php echo $row[0]["alert_date"];?></td>
        </tr>
        <tr>
          <td>Delete From Debtors </td>
          <td><?php echo $row[0]["delete_form_debtors"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["deactivation_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["device_remove_status"]=="Y" && $row[0]["no_device_removed"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["pay_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["deactivation_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["pay_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["deactivation_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["deactivation_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Pending Amount</td>
          <td><?php echo $row[0]["pay_pending"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="del_form_debtors")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);

    ?>
<div id="databox">
  <div class="heading">Delete From Debtors</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date </td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["total_no_of_vehicle"];?></td>
        </tr>
        <tr>
          <td>Date Of Creation </td>
          <td><?php echo $row[0]["date_of_creation"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["del_debtors_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["device_remove_status"]=="Y" && $row[0]["no_device_removed"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Stock";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["del_debtors_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["del_debtors_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["del_debtors_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php }
    else If($tablename=="software_request")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?>
<div id="databox">
  <div class="heading">Software Request</div>
  <div class="dataleft">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Date</td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["main_user_id"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name</td>
          <td><?php echo $row[0]["company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle</td>
          <td><?php echo $row[0]["total_no_of_vehicles"];?></td>
        </tr>
        <tr>
          <td>Potential</td>
          <td><?php echo $row[0]["potential"];?></td>
        </tr>
        <tr>
          <td>Requested Alerts:---</td>
          <td></td>
        </tr>
        <tr>
          <td>Google Map</td>
          <td><?php echo $row[0]["rs_google_map"];?></td>
        </tr>
        <tr>
          <td>Admin </td>
          <td><?php echo $row[0]["rs_admin"];?></td>
        </tr>
        <tr>
          <td>
        <tr>
          <td>Type Of Alert</td>
          <td><?php echo $row[0]["alert"];?></td>
        </tr>
        <tr>
          <td>Requested Reports:---</td>
          <td></td>
        </tr>
        <tr>
          <td>
        <tr>
          <td>Reports</td>
          <td><?php echo $row[0]["reports"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        <tr>
          <td>Other Alert/ Info</td>
          <td><?php echo $row[0]["rs_others"];?></td>
        </tr>
        <tr>
          <td>Customize Report </td>
          <td><?php echo $row[0]["rs_customize_report"];?></td>
        </tr>
        <tr>
          <td>Alert Contact Number</td>
          <td><?php echo $row[0]["alert_contact"];?></td>
        </tr>
        <tr>
          <td>Client Contact Number </td>
          <td><?php echo $row[0]["client_contact_num"];?></td>
        </tr>
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["software_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["software_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && $row[0]["final_status"]==0 && $row[0]["software_status"]==1){echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["software_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php
    }

    else If($tablename=="transfer_the_vehicle")
        {
        $query = "SELECT * FROM ".$tablename." where id=".$RowId;
            $row=select_query($query);



    ?>
<div id="databox">
  <div class="heading">Transfer Vehicle</div>
  <div class="dataleft">
    <table cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td>Date</td>
          <td><?php echo date("d-M-Y h:i:s A",strtotime($row[0]["date"]));?></td>
        </tr>
        <tr>
          <td>Request By</td>
          <td><?php echo $row[0]["acc_manager"];?></td>
        </tr>
        <tr>
          <td>Account Manager</td>
          <td><?php echo $row[0]["sales_manager"];?></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["transfer_to_user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Client User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Company Name </td>
          <td><?php echo $row[0]["transfer_from_company"];?></td>
        </tr>
        <tr>
          <td>Total No Of Vehicle </td>
          <td><?php echo $row[0]["total_no_of_veh"];?></td>
        </tr>
        <!--<tr><td>Vehicle to move </td><td><?php echo $row[0]["transfer_from_reg_no"];?></td></tr> -->
        
        <tr>
          <td>Vehicle to move </td>
          <td><?php $vechile_no = explode(",",$row[0]["transfer_from_reg_no"]);
for($i=0;$i<=count($vechile_no);$i++){ if($i%3!=0){ echo $vechile_no[$i].", ";}else { echo "<br/>".$vechile_no[$i].", ";} }?></td>
        </tr>
        <tr>
          <td>Transfer To:--</td>
          <td></td>
        </tr>
        <?php $sql="SELECT Userid AS id,UserName AS sys_username FROM internalsoftware.addclient  WHERE Userid=".$row[0]["transfer_to_user"];
    $rowuser=select_query($sql);
    ?>
        <tr>
          <td>Transfer User Name </td>
          <td><?php echo $rowuser[0]["sys_username"];?></td>
        </tr>
        <tr>
          <td>Transfer Company Name </td>
          <td><?php echo $row[0]["transfer_to_company"];?></td>
        </tr>
        <tr>
          <td>Billing</td>
          <td><?php echo $row[0]["transfer_to_billing"];?></td>
        </tr>
        <tr>
          <td>Billing Name</td>
          <td><?php echo $row[0]["billing_name"];?></td>
        </tr>
        <tr>
          <td>Billing Address</td>
          <td><?php echo $row[0]["billing_address"];?></td>
        </tr>
        <tr>
          <td>Reason</td>
          <td><?php echo $row[0]["reason"];?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="dataright">
    <table cellspacing="2" cellpadding="2">
      <tbody>
        
        <!--<tr><td>Admin Approval</td>  <td><?if($row[0]["approve_status"]==1) echo "Approved"; else echo "Pending Approval"?></td></tr>-->
        <tr>
          <td><strong>Process Pending </strong></td>
          <td><strong>
            <?php  if($row[0]["transfer_veh_status"]==2 || (($row[0]["support_comment"]!="" || $row[0]["admin_comment"]!="") && $row[0]["sales_comment"]==""))
    {echo "Reply Pending at Request Side";}
    elseif($row[0]["account_comment"]=="" && $row[0]["total_pending"]=="" && $row[0]["approve_status"]==0 && $row[0]["final_status"]==0){echo "Pending at Accounts";}
    elseif($row[0]["approve_status"]==0 && $row[0]["forward_req_user"]!="" && $row[0]["forward_back_comment"]=="" && $row[0]["transfer_veh_status"]==1)   
    {echo "Pending Admin Approval (Req Forward to ".$row[0]["forward_req_user"].")";}
    elseif($row[0]["approve_status"]==0 && ($row[0]["account_comment"]!="" || $row[0]["total_pending"]!="") && $row[0]["final_status"]==0 && $row[0]["transfer_veh_status"]==1)
    {echo "Pending Admin Approval";}
    elseif($row[0]["approve_status"]==1 && $row[0]["transfer_veh_status"]==1 && $row[0]["final_status"]!=1){echo "Pending at Tech Support Team";}
    elseif($row[0]["final_status"]==1){echo "Process Done";}?>
            </strong></td>
        </tr>
        <tr>
          <td>Account Comment</td>
          <td><?php echo $row[0]["account_comment"];?></td>
        </tr>
        <tr>
          <td>Payment Pending</td>
          <td><?php echo $row[0]["total_pending"];?></td>
        </tr>
        <tr>
          <td>Sales Comment</td>
          <td><?php echo $row[0]["sales_comment"];?></td>
        </tr>
        <tr>
          <td>Support Comment</td>
          <td><?php echo $row[0]["support_comment"];?></td>
        </tr>
        <tr>
          <td>Admin Comment</td>
          <td><?php echo $row[0]["admin_comment"];?></td>
        </tr>
        <tr>
          <td>Req Forwarded to</td>
          <td><?php echo $row[0]["forward_req_user"];?></td>
        </tr>
        <tr>
          <td>Forward Comment</td>
          <td><?php echo $row[0]["forward_comment"];?></td>
        </tr>
        <tr>
          <td>F/W Request Back Comment</td>
          <td><?php echo $row[0]["forward_back_comment"];?></td>
        </tr>
        <tr>
          <td>Approval Date</td>
          <td><?php
			if($row[0]["approve_status"]==1 && $row[0]["approve_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["approve_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
        <tr>
          <td>Closed Date</td>
          <td><?php
			if($row[0]["final_status"]==1 && $row[0]["close_date"]!='')
			{
			echo date("d-M-Y h:i:s A",strtotime($row[0]["close_date"]));
			}
			else
			{
				echo "";
			}
			
			?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php
    }
    }
?>
