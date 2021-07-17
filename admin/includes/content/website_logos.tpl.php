<form method="post">
    <div class="row">
        <div class="col-12">
            <?php echo draw_form_buttons('save'); ?>
        </div>
    </div>
    <div class="alert alert-danger my-3" role="alert">
        <strong><?php echo COMMON_NOTE; ?></strong> <?php echo WEBSITE_LOGO_IMG_MSG; ?>
    </div>
    <div class="mt-4">
        <?php
            echo form_element(LABEL_SITE_LOGO, 'file', 'site_logo', $wLogosData['site_logo'], array('data-ajax'=>DIR_HTTP_ADMIN.FILE_ADMIN_AJAX_UPLOADER, 'data-src-path'=>DIR_WS_WEBSITE_LOGOS, 'data-http-path'=>DIR_HTTP_WEBSITE_LOGOS, 'form_group_class'=>'no-gutters', 'allow_delete'=>false));
            
            echo form_element(LABEL_SITE_FAVICON, 'file', 'site_favicon', $wLogosData['site_favicon'], array('data-ajax'=>DIR_HTTP_ADMIN.FILE_ADMIN_AJAX_UPLOADER, 'data-src-path'=>DIR_WS_WEBSITE_LOGOS, 'data-http-path'=>DIR_HTTP_WEBSITE_LOGOS, 'form_group_class'=>'no-gutters', 'allow_delete'=>true));
        ?>
    </div>
</form>