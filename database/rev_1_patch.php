<?php

custom_query("INSERT INTO admin(admin_id, admin_username, admin_password, is_superadmin) values(NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', '1')");

custom_query("INSERT INTO `site_language` (`language_id`, `language_name`, `language_flag`, `status`) VALUES (NULL, 'English', 'us.png', '1');");

custom_query("INSERT INTO admin_pages(page_id, page_name) values(NULL, 'welcome.php')");

custom_query("INSERT INTO admin_pages(page_id, page_name) values(NULL, 'user_listing.php')");

custom_query("INSERT INTO `admin_pages` (`page_id`, `page_name`) VALUES (NULL, 'language_listing.php');");

$sectionname = getSectionId('Dashboard');
if($sectionname == 0) {
    $sectionname = custom_query("INSERT INTO admin_section(section_id, icon_class, sort_order, page_ids) values(NULL, 'ti-dashboard', '0', '".getPageId('welcome.php')."')");
    custom_query("INSERT INTO admin_section_description(auto_id, section_id, section_heading, language_id) values(NULL, '".$sectionname."', 'Dashboard', '1')");
}
$sectionname = getSectionId('Website Customers');
if($sectionname == 0) {
    $sectionname = custom_query("INSERT INTO admin_section(section_id, icon_class, sort_order, page_ids) values(NULL, 'fa fa-users', '1', '".getPageId('user_listing.php')."')");
    custom_query("INSERT INTO admin_section_description(auto_id, section_id, section_heading, language_id) values(NULL, '".$sectionname."', 'Website Customers', '1')");
}
$sectionname = getSectionId('Personlization');
if($sectionname == 0) {
    $sectionname = custom_query("INSERT INTO `admin_section` (`section_id`, `icon_class`, `sort_order`, `page_ids`) VALUES (NULL, 'ti-settings', '2', '".getPageId('language_listing.php')."');");
    custom_query("INSERT INTO `admin_section_description` (`auto_id`, `section_id`, `section_heading`, `language_id`) VALUES (NULL, '".$sectionname."', 'Personlization', '1');");
}

$menuname = getMenuId('Customers');
if($menuname == 0) {
    $menuname = custom_query("INSERT INTO admin_section_menu(menu_id, section_id, sort_order, action_page) values(NULL, '".getSectionId('Website Customers')."', '0', '".getPageId('user_listing.php')."')");
    custom_query("INSERT INTO admin_section_menu_description(auto_id, menu_id, menu_name, language_id) values(NULL, '".$menuname."', 'Customers', '1')");
}
$menuname = getMenuId('Languages');
if($menuname == 0) {
    $menuname = custom_query("INSERT INTO `admin_section_menu` (`menu_id`, `section_id`, `sort_order`, `action_page`) VALUES (NULL, '".getSectionId('Personlization')."', '0', '".getPageId('language_listing.php')."');");
    custom_query("INSERT INTO `admin_section_menu_description` (`auto_id`, `menu_id`, `menu_name`, `language_id`) VALUES (NULL, '".$menuname."', 'Languages', '1');");
}

custom_query("INSERT INTO `admin_menu_action` (`action_id`, `constant_name`, `title`, `section_menu_id`) VALUES (NULL, 'COMMON_ADD_LANGUAGE', 'Add Language', '5');");



$admin_constants = array();
$admin_constants[] = array(
    'name' => 'COMMON_USERNAME',
    'section' => 'common',
    'value' =>  array(
        1 => 'Username',
    ),
);
$admin_constants[] = array(
    'name' => 'COMMON_PASSWORD',
    'section' => 'common',
    'value' =>  array(
        1 => 'Password',
    ),
);
$admin_constants[] = array(
    'name' => 'COMMON_SIGNIN',
    'section' => 'common',
    'value' =>  array(
        1 => 'SIGN IN',
    ),
);
$admin_constants[] = array(
    'name' => 'LOGIN_FORGOT_PASSWORD',
    'section' => 'common',
    'value' =>  array(
        1 => 'Forgot Password?',
    ),
);
$admin_constants[] = array(
    'name' => 'COMMON_SUBMIT',
    'section' => 'common',
    'value' =>  array(
        1 => 'Submit',
    ),
);
$admin_constants[] = array(
    'name' => 'COMMON_DASHBOARD',
    'section' => 'common',
    'value' =>  array(
        1 => 'Dashboard',
    ),
);
$admin_constants[] = array(
    'name' => 'COMMON_CUSTOMERS',
    'section' => 'Customers',
    'value' =>  array(
        1 => 'Customers',
    ),
);
manage_admin_constants($admin_constants);

?>