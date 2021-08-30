<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?= $pageTitle; ?> :: Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/<?= SITE_FAVICON_EXT ?>" href="<?= SITE_FAVICON ?>">
        <?php addCss($adminCssArr) ?>
    </head>
    <body>
        <div class="alert-dismiss"></div>
        <!-- page container area start -->
        <div class="page-container">
            <!-- sidebar menu area start -->
            <?php require_once(DIR_WS_ADMIN_INCLUDES.'leftmenu.tpl.php'); ?>
            <!-- sidebar menu area end -->
            <!-- main content area start -->
            <div class="main-content">
                <!-- header area start -->
                <div class="header-area">
                    <div class="row align-items-center">
                        <!-- nav and search button -->
                        <div class="col-10 col-md-9 clearfix">
                            <div class="nav-btn pull-left">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <ul class="breadcrumbs pull-left pr-3">
                                <?php
                                if(!empty($breadcrumbArr)) {
                                    $breadcrumbArr = array_filter($breadcrumbArr);
                                    if(count($breadcrumbArr) == 1) { unset($breadcrumbArr[0]['link']); }
                                    foreach ($breadcrumbArr as $key => $value) {
                                        if(!empty($value)) {
                                            if(isset($value['link'])) {
                                                echo '<li><a href="'.$value['link'].'">'.$value['title'].'</a></li>';
                                            } else {
                                                echo '<li><span>'.$value['title'].'</span></li>';
                                            }
                                        }
                                    }
                                }
                                ?>
                            </ul>
                            <?php if(isDevMode()) { ?>
                                <div class="pt-1 pl-2">
                                [Admin :: Section: <?= ADMIN_SECTION_ID ?> Menu: <?= ADMIN_MENU_ID ?> Page: <?= ADMIN_PAGE_ID ?>]
                                </div>
                            <?php } ?>
                        </div>
                        <!-- profile info & task notification -->
                        <div class="col-2 col-md-3 clearfix">
                            <ul class="notification-area pull-right">
                                <li class="front-btn">
                                    <i class="ti-desktop" onclick="window.open('<?= SITE_URL ?>','_blank')"></i>
                                </li>
                                <li id="full-view"><i class="ti-fullscreen"></i></li>
                                <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- header area end -->
                <!-- page title area start -->
                <div class="page-title-area">
                    <div class="row align-items-center">
                        <div class="col-9 col-md-10">
                            <div class="heading-area">
                                <h4 class="page-title pull-left"><?= $headingLabel; ?></h4>
                            </div>
                            <i class="fa fa-refresh fa-spin d-none ajax_loader"></i>
                        </div>
                        <div class="col-3 col-md-2 clearfix">
                            <div class="user-profile pull-right">
                                <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?= SES_ADMIN_USERNAME ?><i class="fa fa-angle-down"></i></h4>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <a class="dropdown-item" href="<?= DIR_HTTP_ADMIN.FILE_ADMIN_WELCOME.'?action=logout' ?>">Log Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- page title area end -->
                <div class="main-content-inner">
                    <!-- button srea start -->
                    <div class="row"><div class="col-12 my-3">
                        <?php
                            if(file_exists(DIR_WS_ADMIN_CONTENTS.FILE_FILENAME_WITHOUT_EXT.'.tpl.php') && is_file(DIR_WS_ADMIN_CONTENTS.FILE_FILENAME_WITHOUT_EXT.'.tpl.php'))
                                require_once(DIR_WS_ADMIN_CONTENTS.FILE_FILENAME_WITHOUT_EXT.'.tpl.php');
                        ?>
                    </div></div>
                    <!-- button srea end -->
                </div>
            </div>
            <!-- main content area end -->
            <!-- footer area start-->
            <footer>
                <div class="footer-area">
                    <p>© <?= CONFIG_SITE_NAME ?> Copyright 2021. All right reserved.</p>
                </div>
            </footer>
            <!-- footer area end-->
        </div>
        <!-- page container area end -->
        <?php addJs() ?>
        <script><?php echo $globalJs; ?></script>
    </body>
</html>