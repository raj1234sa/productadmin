<?php
$AdminSectionMaster = new AdminSectionMaster();
$AdminSectionMaster->setFrom('admin_section');
$AdminSectionMaster->setOrderBy("sort_order ASC");
$adminSectionData = $AdminSectionMaster->getAdminSection('yes');

$finalLeftMenuData = array();
$finalLeftMenuData[-1] = array(
    'section_id' => 0,
    'section_title' => COMMON_DASHBOARD,
    'section_icon' => 'ti-dashboard',
    'section_url' => DIR_HTTP_ADMIN.FILE_ADMIN_WELCOME,
);
foreach ($adminSectionData as $key => $value) {
    $AdminMenuMaster = new AdminMenuMaster();
    $AdminMenuMaster->setOrderBy("sort_order ASC");
    $AdminMenuMaster->setJoin('LEFT JOIN admin_pages ON admin_pages.page_id = admin_section_menu.action_page');
    $AdminMenuMaster->setWhere('AND section_id = :section_id', $value['section_id'], 'int');
    $AdminMenuMaster->setWhere('AND status = :status', '1', 'string');
    $adminMenuData = $AdminMenuMaster->getAdminMenu('yes');

    $finalLeftMenuData[$key] = array(
        'section_id' => $value['section_id'],
        'section_title' => $value['section_heading'],
        'section_icon' => $value['icon_class'],
        'children' => array(),
    );

    foreach ($adminMenuData as $menu) {
        $finalLeftMenuData[$key]['children'][] = array(
            'menu_id' => $menu['menu_id'],
            'menu_title' => $menu['menu_name'],
            'action_page' => $menu['page_name'],
            'page_id' => $menu['page_id'],
        );
    }
}

if(defined('SITE_LOGO') && SITE_LOGO == '') {
    $logo_text = "<a href='".DIR_HTTP_ADMIN.FILE_ADMIN_WEBSITE_LOGOS."' class='btn btn-primary'>Upload Logo Here</a>";
} else {
    $logo_text = "<a href='".DIR_HTTP_ADMIN.FILE_ADMIN_WELCOME."'><img src='".SITE_LOGO."' alt='logo'></a>";
}
// echo '<pre>'; print_r($finalLeftMenuData); echo '</pre>'; exit;
?>
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <?php echo $logo_text; ?>
            
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <?php
                    foreach ($finalLeftMenuData as $section) {
                        $menuData = $section['children'];
                        $section_title = $section['section_title'];
                        $section_icon = $section['section_icon'];
                        $section_url = empty($section['section_url']) ? 'javascript:void(0)' : $section['section_url'];
                        $section_active = (ADMIN_SECTION_ID == $section['section_id']);

                        $section_active_class = ($section_active) ? 'active' : '';
                        $section_class = $section_active ? 'aria-expanded="true"' : 'aria-expanded="false"';
                        $menu_expand = $section_active ? 'in' : '';

                        if(!empty($section_icon)) {
                            $icon_text = '<i class="'.$section_icon.'"></i>';
                        }
                        if(!empty($section_title)) {
                            $title_text = '<span>'.$section_title.'</span>';
                        }
                        ?>
                        <li class="<?php echo $section_active_class ?>">
                            <a href="<?php echo $section_url; ?>" <?php echo $section_class; ?>>
                                <?php echo $icon_text; echo $title_text; ?>
                            </a>
                            <?php
                            if(!empty($menuData)) {
                                echo '<ul class="collapse '.$menu_expand.'">';
                                foreach ($menuData as $menu) {
                                    $menuUrl = $menu['action_page'];
                                    $menuTitle = $menu['menu_title'];
                                    $menu_active_class = ($section_active && in_array($menu['page_id'], ADMIN_ALLOWED_PAGE_ID)) ? 'active' : '';

                                    if(!empty($menuUrl)) {
                                        $menuUrl = DIR_HTTP_ADMIN.$menuUrl;
                                    }
                                    ?>
                                    <li class='<?php echo $menu_active_class ?>'><a href="<?php echo $menuUrl; ?>"><?php echo $menuTitle; ?></a></li>
                                    <?php
                                }
                                echo '</ul>';
                            }
                            ?>
                        </li>
                        <?php
                        }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>