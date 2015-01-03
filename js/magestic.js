/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function emailStatus(state){
                    //email switch
                
                  //  console.log(this); // DOM element
                  //  console.log(event); // jQuery event
                    console.log(state);// true | false
                    
                    
                    data = {
                        action :{
                        "email-checkbox":state
                    }
                    };

                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: 'http://tvoop.us/magestic/admin/ajax.php',
                      //  dataType: "json",
                        // contentType: "application/json; charset=utf-8",
                        data: data,

                        success: function (msg)
                        {
                           // alert(msg);

                        },
                        error: function (response, desc, err)
                        {
              
                            alert('error');
                          

                        },
                        statusCode: {
                            400: function () {
                                alert("page not found");
                            }
                        }
                    })
                            .done(function (msg) {
                              console.log(msg);
                            })
                            .fail(function () {

                            });
              
}

function smsStatus(){
                    //email switch
                $('input[name="email-checkbox"]').on('switchChange.bootstrapSwitch', function (event, state) {
                    console.log(this); // DOM element
                    console.log(event); // jQuery event
                    console.log(state);// true | false
                    
                    
                    data = {
                        action :{
                        "email-checkbox":state
                    }
                    };

                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: 'http://tvoop.us/magestic/admin/ajax.php',
                      //  dataType: "json",
                        // contentType: "application/json; charset=utf-8",
                        data: data,

                        success: function (msg)
                        {
                           // alert(msg);

                        },
                        error: function (response, desc, err)
                        {
              
                            alert('error');
                          

                        },
                        statusCode: {
                            400: function () {
                                alert("page not found");
                            }
                        }
                    })
                            .done(function (msg) {
                              console.log(msg);
                            })
                            .fail(function () {

                            });
                });
}

function stripeStatus(){
                    //email switch
                $('input[name="email-checkbox"]').on('switchChange.bootstrapSwitch', function (event, state) {
                    console.log(this); // DOM element
                    console.log(event); // jQuery event
                    console.log(state);// true | false
                    
                    
                    data = {
                        action :{
                        "email-checkbox":state
                    }
                    };

                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: 'http://tvoop.us/magestic/admin/ajax.php',
                      //  dataType: "json",
                        // contentType: "application/json; charset=utf-8",
                        data: data,

                        success: function (msg)
                        {
                           // alert(msg);

                        },
                        error: function (response, desc, err)
                        {
              
                            alert('error');
                          

                        },
                        statusCode: {
                            400: function () {
                                alert("page not found");
                            }
                        }
                    })
                            .done(function (msg) {
                              console.log(msg);
                            })
                            .fail(function () {

                            });
                });
}