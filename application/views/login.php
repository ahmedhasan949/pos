<!DOCTYPE html>
<html dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->

        <title>CMS - ADMINISTRATOR</title>
        <!-- Custom CSS -->
        <link href="<?= base_url('application/views/assets/libs/flot/css/float-chart.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('application/views/dist/css/style.min.css'); ?>" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body> 
        <div class="main-wrapper">
            <!-- ============================================================== -->
            <!-- Preloader - style you can find in spinners.css -->
            <!-- ============================================================== -->
            <div class="preloader">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Preloader - style you can find in spinners.css -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Login box.scss -->
            <!-- ============================================================== -->
            <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
                <div class="auth-box bg-dark border-top border-secondary">
                    <?php if (isset($err)) { ?>
                        <div class="alert <?= ($err['success']) ? 'alert-success' : 'alert-danger'; ?>">
                            <strong>  <?= $err['msg']; ?>  </strong>
                        </div>
                    <?php } ?>
                    <div id="loginform">
                        <div class="text-center p-t-20 p-b-20">
                            <span class="db"><img src="<?= base_url(); ?>application/views/assets/images/logo.png" alt="logo" /></span>
                        </div>
                        <!-- Form -->
                        <form class="form-horizontal m-t-20" id="loginform" action="<?= site_url('auth/login') ?>" method="POST"> 

                            <div class="row p-b-30">
                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required="">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row border-top border-secondary">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="p-t-20">
                                            <a href="#register_modal" class="btn btn-info" data-toggle="modal">Register Account</a>
                                           <!--<button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button>-->
                                            <button class="btn btn-success float-right" type="submit">Login</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>


        <!-- ============================================================== -->
        <!-- All Required js -->
        <!-- ============================================================== -->
        <script src="<?= base_url('application/views/assets/libs/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="<?= base_url('application/views/assets/libs/popper.js/dist/umd/popper.min.js') ?>"></script>
        <script src="<?= base_url('application/views/assets/libs/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <!-- ============================================================== -->
        <!-- This page plugin js -->
        <!-- ============================================================== -->
        <script>

            $('[data-toggle="tooltip"]').tooltip();
            $(".preloader").fadeOut();

            $('#to-recover').on("click", function () {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });
            $('#to-login').click(function () {
                $("#recoverform").hide();
                $("#loginform").fadeIn();
            }); 
           

        </script> 

        <div class="modal fade" id="register_modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->

                <div class="modal-content">
                    <form class="form-validate form-horizontal" id="registration_form" method="post" action="#">
                        <div class="modal-header">
                            <h4 class="modal-title">Registration</h4> 
                            <button type="button" class="close" data-dismiss="modal">&times;</button>  
                        </div>
                        <div class="modal-body" style="background-color: white;">
                            <div class="alert alert_modal" style="display:none;"></div>    


                            <div class="col-lg-12"> 
                                <div class="panel-body">   

                                    <div class="form-group row">
                                        <label for="module_name" class="control-label col-lg-3">Full Name <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="fullname" name="fullname" minlength="5" type="text" required value="" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="module_title" class="control-label col-lg-3">Email<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control " id="email" type="email" name="email" required value="" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="module_method_location" class="control-label col-lg-3">Username<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="username" name="username" minlength="5" type="text" required value="" />
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="control-label col-lg-3">Password <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="password" name="password" minlength="6" type="password" required value="" />
                                        </div>
                                    </div> 

                                </div> 
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                            <button type="button" class="btn btn-success submit_modal">Submit</button> 
                        </div>
                    </form> 
                </div>

            </div>
        </div>
        
        <script>
            
             $(".submit_modal").click(function () { 

                var fullname = $('#fullname').val();
                var email = $('#email').val();
                var username = $('#username').val();
                var password = $('#password').val();

               

                $.ajax({
                    type: 'POST',
                    url: '<?= site_url('auth/register') ?>',
                    data: {
                        fullname: fullname,
                        email: email,
                        username: username,
                        password: password                      
                    }, 
                    dataType: 'json',
                    success: function (data) {


                        if (data.success == 'yes') {

                            $('.alert_modal').removeClass('alert-danger');
                            $('.alert_modal').addClass('alert-success');
                            $('.alert_modal').show();
                            $('.alert_modal').html(data.msg);
                            
                            setTimeout(function () {
                                $('.alert_modal').slideUp('slow');
                                $("#register_modal").modal("hide");
                                 $('#registration_form').trigger("reset");
                            }, 1000);

                        } else if (data.success == 'no') {
                            $('.alert_modal').addClass('alert-danger');
                            $('.alert_modal').show();
                            $('.alert_modal').html(data.msg);
                            setTimeout(function () {
                                $('.alert_modal').slideUp('slow');
                            }, 3000);
                        } else if (data.success == false) {
                            swal('', data.msg, 'error');
                        }

                    }
                });
            });
            
            
        </script>


    </body> 
</html>

