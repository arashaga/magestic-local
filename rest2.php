<?php 
require_once('utility.php');
require_once('config.php');

// if (isset($_POST['customerEmail'])){
// 	echo json_encode(array('resultino' => $_POST['customerEmail']));
// 	exit();
// }

if(isset($_POST['date']) and isset($_POST['customerEmail'])){

	if((check_customer_allowance(getDBH(),$_POST['date'] ,$_POST['customerEmail'])))
	{
		echo json_encode(array('res' => 'yes'));
		exit();
	}else{
		echo json_encode(array('res' => 'no'));
	exit();
	}
}
echo json_encode(array('res' => 'Specify the fields!'));
exit();
?>