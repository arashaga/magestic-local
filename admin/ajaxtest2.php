<?php
/**
 * ajax_page_data.dist.php, jui_datagrid ajax fetch page data template script
 *
 * Sample php file getting totalrows and page data
 *
 * @version 0.9.1 (20 Oct 2013)
 * @author Christos Pontikis http://pontikis.net
 * @license  http://opensource.org/licenses/MIT MIT license
 **/

// PREVENT DIRECT ACCESS (OPTIONAL) --------------------------------------------
// $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
// strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
// if(!$isAjax) {
// 	print 'Access denied - not an AJAX request...' . ' (' . __FILE__ . ')';
// 	exit;
// }

// REQUIRED --------------------------------------------------------------------
require_once ('jui_filter_rules.php');                       // CONFIGURE
require_once ('jui_datagrid.php');                           // CONFIGURE
/**
 *  Database Settings you have to provide as an argument to jui_datagrid class
 *
 *  MANDATORY:
 *  rdbms
 *  use_prepared_statements, pst_placeholder (if use_prepared_statements is false, pst_placeholder is ignored)
 *  db_conn (connection object) or database connection settings
 *
 */

/**
 * Page settings
 */
$page_settings = array(
	"selectCountSQL" => "SELECT count(id) as totalrows FROM signin",                                 // CONFIGURE
	"selectSQL" => "SELECT s.id, s.lname, s.fname, s.email, s.date_loggedin
	                  FROM signin s",
	//"selectSQL" => "SELECT * FROM signin",                                      // CONFIGURE
	"page_num" => $_POST['page_num'],
	"rows_per_page" => $_POST['rows_per_page'],
	"columns" => $_POST['columns'],
	//"columns" => '5',
	"sorting" =>  isset($_POST['sorting']) ? $_POST['sorting'] : array(),
	"filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
);

$jdg = new jui_datagrid( $page_settings, $_POST['debug_mode'] == "yes" ? true : false);

echo json_encode($jdg->get_page_data());
?>