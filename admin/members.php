<?php require_once '../utility.php';
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/favicon.ico">


        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>

        <!--DATATABLE-->

        <script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>


        <title>Signin Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../css/signin.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="../bootstrap/dist/js/ie-emulation-modes-warning.js"></script>
        <link href="../bootstrap/plugins/switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
        <script src="../bootstrap/js/modal.js"></script>
        <script src="../bootstrap/plugins/switch/dist/js/bootstrap-switch.min.js"></script>
        <script src="../js/magestic.js"></script>


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!--  DATAGRID plugin ALL REQUIRED-->
        <link rel="stylesheet" type="text/css" href="../js/jquery.bs_grid.min.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/plugins/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/plugins/jui_filter_rules/minified/jquery.jui_filter_rules.min.css">
        <script type="text/javascript" src="../js/jquery.bs_grid.min.js"></script>
        <script type="text/javascript" src="../js/localization/en.min.js"></script>
        <script></script>

        <!--Modal Stuff-->

        <script>

            $(function () {
              //  var action = {};
                
                $("[name='email-checkbox']").bootstrapSwitch();
                $("[name='sms-checkbox']").bootstrapSwitch();
                $("[name='stripe-checkbox']").bootstrapSwitch();

                $('#usermanmodalwindow').on('show.bs.modal', function (event) {

                    $('#rfctable').dataTable({
                        "bProcessing": true,
                        "bServerSide": false, //important
                        "sAjaxDataProp": "data",
                        "sAjaxSource": 'http://tvoop.us/magestic/admin/ajax.php?format=json',
                        "columns": [
                            {"data": "id"},
                            {"data": "lname"},
                            {"data": "fname"}
                        ]
                    });
                    //
                });

                $('#reportmodalwindow').on('show.bs.modal', function (event) {

                    $('#rfctable').dataTable({
                        "bProcessing": true,
                        "bServerSide": false, //important
                        "sAjaxDataProp": "data",
                        "sAjaxSource": 'http://tvoop.us/magestic/admin/ajax.php?format=json',
                        "columns": [
                            {"data": "id"},
                            {"data": "lname"},
                            {"data": "fname"}
                        ]
                    });
                    //
                });

              $('input[name="email-checkbox"]').on('switchChange.bootstrapSwitch', function (event, state) {
                  emailStatus(state);
              });
              

            });//end of $()




        </script>
    </head>

    <body>

        <div class="container">
            <h2 class="form-signin-heading">HELLO MASTER!</h2>


            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>
                            <button class="btn btn-lg btn-primary btn-block" type="submit" id="usermanbtn" 
                                    data-toggle="modal" data-target="#usermanmodalwindow">User Management</button>
                        </td>
                        <td>
                            <button class="btn btn-lg btn-primary btn-block" type="submit" id="reportbtn"
                                    data-toggle="modal" data-target="#reportmodalwindow">Get The listing</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3><span class="label label-default">Turn Email Confirmation On or Off</span></h3>

                        </td>
                        <td>
                            <input type="checkbox" name="email-checkbox" <?php echo emailStatus();?>>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3><span class="label label-default">Turn Stripe On or Off</span></h3>

                        </td>
                        <td>
                            <input type="checkbox" name="stripe-checkbox" checked>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3><span class="label label-default">Turn SMS turn on or Off</span></h3>

                        </td>
                        <td>
                            <input type="checkbox" name="sms-checkbox" checked>
                        </td>
                    </tr>
                </table>
            </div>


            <!--div class="col-xm-4 col-md-4 col-sm-offset-4">
                <button class="btn btn-lg btn-primary btn-block" type="submit" id="usermanbtn" data-toggle="modal" data-target="#usermanmodalwindow">User Management</button>
            </div>    
            <div class="row">
                <div  class="col-xs-6  col-md-6">
                    <h3><span class="label label-default">Email Setting</span></h3>
                </div>
                <div class="col-xs-6  col-md-6">
                    
                    <input type="checkbox" name="email-checkbox" checked>
                </div>
            </div>
            <div class="col-xm-4 col-md-4 col-sm-offset-4">
                <div  class="col-xs-6  col-md-6">
                    <h3><span class="label label-default">Email Setting</span></h3>
                </div>
                <div class="col-xs-6  col-md-6">
                    
                    <input type="checkbox" name="email-checkbox" checked>
                </div>
            </div>
            <div class="col-sm-4 col-sm-offset-4">
              
                <input type="checkbox" name="sms-checkbox" checked>
            </div>
            <div class="col-sm-4 col-sm-offset-4">
             
                <input type="checkbox" name="stripe-checkbox" checked>
            </div-->
            <!--  user management datagrid -->



        </div> <!-- /container -->


<!-- Modal Definitions (tabbed over for <pre>) -->



        <!--Modal STuff-->
        <div class="modal fade" id="usermanmodalwindow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">User Management</h4>
                    </div>
                    <div class="modal-body">
                        <div id="RFCTableContainer" class=".col-xs-12 .col-md-8">
                            <table id="rfctable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>RFC Number</th>
                                        <th>RFC title</th>
                                        <th>RFC Questions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>id</th>
                                        <th>fname</th>
                                        <th>lname</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!---->



        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../bootstrap/dist/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
