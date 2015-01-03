<?php 
require_once('utility.php');
require_once(dirname(__FILE__) . '/config.php');

// if (isset($_POST['fname'])){
// 	echo json_encode(array('resultino' => $_POST['fname']));
// 	exit();
// }

if(isset($_POST['date']) and isset($_POST['fname']) and isset($_POST['lname'])){

	if((check_customer_allowance(getDBH(),$_POST['date'] ,$_POST['fname'] ,$_POST['lname'])))
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