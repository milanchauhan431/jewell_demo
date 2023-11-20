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
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/images/favicon.png">
    <title>Login - <?=(!empty(SITENAME))?SITENAME:""?></title>
    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/css/style.css" rel="stylesheet">

    <style>
        html,body {
            overflow: hidden;
        }

        body {
            margin: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            background:url(https://riva.toxscube.com/assets/images/dashboard-bg.jpg) no-repeat center center; background-size:cover;
        }

        canvas {
            width: 100%;
            height: 100%;
            visibility: transparent;
            background: #fff;
        }

        .canvas-wrapper [class^="letter"] {
            -webkit-transition: opacity 3s ease;
            -moz-transition: opacity 3s ease;
            transition: opacity 3s ease;
        }

        .letter-0 {
            transition-delay: 0.2s;
        }

        .letter-1 {
            transition-delay: 0.4s;
        }

        .letter-2 {
            transition-delay: 0.6s;
        }

        .letter-3 {
            transition-delay: 0.8s;
        }

        .letter-4 {
            transition-delay: 1.0s;
        }

        .letter-5 {
            transition-delay: 1.2s;
        }

        .letter-6 {
            transition-delay: 1.4s;
        }

        .letter-7 {
            transition-delay: 1.6s;
        }

        .letter-8 {
            transition-delay: 1.8s;
        }

        .letter-9 {
            transition-delay: 2.0s;
        }

        .letter-10 {
            transition-delay: 2.2s;
        }

        .letter-11 {
            transition-delay: 2.4s;
        }

        .letter-12 {
            transition-delay: 2.6s;
        }

        .letter-13 {
            transition-delay: 2.8s;
        }

        .letter-14 {
            transition-delay: 3.0s;
        }

        .letter-15 {
            transition-delay: 3.2s;
        }

        .canvas-wrapper {
            visibility: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            background-color: #fff;
            border-radius: 2rem;
            width:33%;
            height:70%;
        }

        .canvas-wrapper.transition-in {
            visibility: visible;
        }

        .canvas-wrapper [class^="letter"] {
            opacity: 0;
        }

        .canvas-wrapper.transition-in [class^="letter"] {
            opacity: 1;
        }
        @media only screen and (max-width: 750px) {
         .canvas-wrapper {
            width:95%;
            height:85%;
          }
        }
    </style>
</head>

<body>
    <canvas></canvas>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>


        <div class="canvas-wrapper" style="">
            <div class="auth-box  text-center">
                <!-- Login Form -->
                <div id="loginform">

                    <!-- Logo -->
                    <div class="logo m-t-30">
                        <span class="db"><img src="<?=base_url()?>assets/images/logo_text.png" alt="logo" style="max-height:90px;" /></span>
                        <h5 class="font-medium bg-grey  pad-5" style="margin:10px 0px 20px 0px;">Sign In</h5>
                    </div>
                    
                    <div class="row m-b-30 m-l-20 m-r-20">
                        <div class="col-12">
                            <form class="form-horizontal m-t-20" id="loginform" action="<?=base_url('login/auth');?>" method="post">
                                <?php if($errorMsg = $this->session->flashdata('loginError')): ?>
                                    <div class="error errorMsg"><?=$errorMsg?></div>
                                <?php endif; ?>

                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="Username"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="user_name" id="user_name" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="Username">
                                </div>
								 <?=form_error('user_name')?>
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="Password"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="Password">
                                </div>
								<?=form_error('password')?>
                                <div class="form-group row mt-4">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox float-left">
                                            <input type="checkbox" class="filled-in chk-col-success" value="lsRememberMe" id="rememberMe" onclick="lsRememberMe();">
                                            <label class="" for="rememberMe">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <a href="javascript:void(0)" id="to-recover" class="text-dark float-right m-r-20"><i class="fa fa-lock m-r-5"></i> Forgot password?</a>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-primary waves-effect btn-rounded waves-light btn-block" type="submit"> Sign In</button>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Recover Form -->
                <div id="recoverform">
                    <!-- Logo -->
                    <div class="logo m-t-30">
                        <span class="db"><img src="<?=base_url()?>assets/images/logo_text.png" alt="logo" style="max-height:90px;" /></span>
                        <h5 class="font-medium bg-grey  pad-5" style="margin:10px 0px 20px 0px;">Recover Password</h5>
                        <span>Enter your Email and instructions will be sent to you!</span>
                    </div>

                    <div class="row  m-l-20 m-r-20">
                        <form class="col-12" action="#">
                            <!-- email -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control form-control-lg" type="email" required="" placeholder="Enter your email">                                    
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-20 mb-2">
                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-primary" type="submit" name="action">Submit</button>                                    
                                </div>
                                <div class="col-12 text-center m-t-10">
                                    <a href="javascript:void(0)" id="to-login" class="text-dark"><i class="fa fa-lock m-r-5"></i> Sign In</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-poweredby font-medium bg-grey pad-5">Powered By : FINTER ERP</div>
    </div>
    <script src="<?=base_url()?>assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?=base_url()?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?=base_url()?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/custom/login.js"></script>
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    $("#recoverform").fadeOut();
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    $('#to-login').on("click", function() {
        $("#recoverform").fadeOut();
        $("#loginform").slideDown();
    });

    const rmCheck = document.getElementById("rememberMe"),
        emailInput = document.getElementById("user_name");

    if (localStorage.checkbox && localStorage.checkbox !== "") {
        rmCheck.setAttribute("checked", "checked");
        emailInput.value = localStorage.username;
    } else {
        rmCheck.removeAttribute("checked");
        emailInput.value = "";
    }

    function lsRememberMe() {
        if (rmCheck.checked && emailInput.value !== "") {
            localStorage.username = emailInput.value;
            localStorage.checkbox = rmCheck.value;
        } else {
            localStorage.username = "";
            localStorage.checkbox = "";
        }
    }
    </script>
</body>

</html>