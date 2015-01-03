<?php
require_once 'dacapo.class.php'; // simple database wrapper
require_once 'jui_filter_rules.php';
require_once 'bs_grid.php';
 
$db_settings = array(
  'rdbms' => 'MYSQLi',
  'db_server' => 'localhost',
  'db_user' => 'root',
  'db_passwd' => 'biashead',
  'db_name' => 'magestic',
  'db_port' => '3306',
  'charset' => 'utf8',
  'use_pst' => true, // use prepared statements
  'pst_placeholder' => 'question_mark'
);
 
$ds = new dacapo($db_settings, null);
 
$page_settings = array(
  "selectCountSQL" => "SELECT count(id) as totalrows FROM signin",
  "selectSQL" => "SELECT s.id as id, s.lname, s.fname, s.email, s.date_loggedin
                  FROM signin s",
  "page_num" => $_POST['page_num'],
  "rows_per_page" => $_POST['rows_per_page'],
  "columns" => $_POST['columns'],
  "sorting" => isset($_POST['sorting']) ? $_POST['sorting'] : array(),
  "filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
);
 
//print_r($page_settings);
$jfr = new jui_filter_rules($ds);
$jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);
 
$data = $jdg->get_page_data();

 
// data conversions (if necessary)
foreach($data['page_data'] as $key => $row) {
  // this will convert Lastname to a link
  $data['page_data'][$key]['lname'] = "<a href=\"/test/{$row['id']}\">{$row['lname']}</a>";
  // this will format date_updated (attention date_convert is a local function)
  $data['page_data'][$key]['dateIloggedin'] = date_convert($row['date_loggedin'], 'UTC', 'YmdHis', 'UTC', 'd/m/Y H:i:s');
}
 
echo json_encode($data);