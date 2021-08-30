<form method="post">
    <div class="row">
        <div class="col-12">
            <?php echo drawFormButtons('save'); ?>
        </div>
    </div>
    <div class="alert alert-danger my-3" role="alert">
        <strong><?php echo COMMON_NOTE; ?></strong> <?php echo WEBSITE_LOGO_IMG_MSG; ?>
    </div>
    <div class="mt-4">
        <?php
            $siteLogoHtml = "<span id='imgpreview_site_logo' class='ml-2'>".drawImge(DIR_HTTP_WEBSITE_LOGOS.$wLogosData['site_logo'], DIR_WS_WEBSITE_LOGOS.$wLogosData['site_logo'], array('width'=>'100','class'=>'image_zoom'))."</span>";
            echo formElement(LABEL_SITE_LOGO, 'file', 'logo[site_logo]', $wLogosData['site_logo'], '', array('data-ajax'=>DIR_HTTP_ADMIN.FILE_ADMIN_AJAX_UPLOADER, 'data-src-path'=>DIR_WS_TEMP_IMAGES, 'data-http-path'=>DIR_HTTP_TEMP_IMAGES, 'html'=>$siteLogoHtml, 'form_group_class'=>'no-gutters', 'id'=>'site_logo', 'data-delete'=>"false"));
            
            if($wLogosData['site_favicon'] && file_exists(DIR_WS_WEBSITE_LOGOS.$wLogosData['site_favicon'])) {
                $faviconHtml = "<span id='imgpreview_site_favicon' class='ml-2'><i class='ti-trash delete'></i>".drawImge(DIR_HTTP_WEBSITE_LOGOS.$wLogosData['site_favicon'], DIR_WS_WEBSITE_LOGOS.$wLogosData['site_favicon'], array('width'=>'100','class'=>'image_zoom'))."</span>";
            } else {
                $faviconHtml = "<span id='imgpreview_site_favicon' class='ml-2'>".drawNoimge(array('width'=>'100'))."</span>";
            }
            echo formElement(LABEL_SITE_FAVICON, 'file', 'logo[site_favicon]', $wLogosData['site_favicon'], '', array('data-ajax'=>DIR_HTTP_ADMIN.FILE_ADMIN_AJAX_UPLOADER, 'data-src-path'=>DIR_WS_TEMP_IMAGES, 'html'=>$faviconHtml, 'data-http-path'=>DIR_HTTP_TEMP_IMAGES, 'form_group_class'=>'no-gutters', 'id'=>'site_favicon'));
        ?>
    </div>
</form>