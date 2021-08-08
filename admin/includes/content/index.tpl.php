<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login :: Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/<?php echo SITE_FAVICON_EXT ?>" href="<?php echo SITE_FAVICON ?>">
        <?php addCss($adminCssArr) ?>
    </head>
    <body>
        <!-- <div id="preloader">
            <div class="loader"></div>
        </div> -->
        <div class="login-area">
            <div class="container">
                <div class="login-box ptb--100">
                    <form method="POST" id="admin_login_frm">
                        <div class="login-form-head">
                            <h4><?php echo COMMON_SIGNIN; ?></h4>
                        </div>
                        <div class="login-form-body">
                            <?php
                                $label_col_class = 0;
                                $validation = array('required'=>COMMON_VALIDATE_REQUIRED);
                                echo form_element('', 'text', 'admin_username', '', '', array('placeholder'=>COMMON_USERNAME, 'validation'=>$validation, 'error'=>$err['username']));
                                echo form_element('', 'password', 'admin_password', '', '', array('placeholder'=>COMMON_PASSWORD, 'validation'=>$validation));
                                echo form_hidden('backurl', getValue('backurl'));
                            ?>
                            <div class="row mb-4 rmber-area justify-content-end">
                                <div class="col-6 text-right">
                                    <a href="#"><?php echo LOGIN_FORGOT_PASSWORD; ?></a>
                                </div>
                            </div>
                            <div class="submit-btn-area">
                                <button id="form_submit" type="submit" name="admin_login" value="yes"><?php echo COMMON_SUBMIT; ?>
                                    <i class="ti-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php addJs() ?>
        <script>
            $("#admin_login_frm").validate();
        </script>
    </body>
</html>