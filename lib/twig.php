<?php
date_default_timezone_set("Asia/Kolkata");
require_once DIR_WS_VENDOR."autoload.php";

use Twig\Extra\Intl\IntlExtension;

$loader = new \Twig\Loader\FilesystemLoader([DIR_WS_TEMPLATES, DIR_WS_TEMPLATES_CONTENT]);
$twig = new \Twig\Environment($loader);
$twig->addExtension(new IntlExtension());
$twig->registerUndefinedFunctionCallback(function ($name) {
    if(function_exists($name)) {
        return new \Twig\TwigFunction($name, $name);
    }
    return false;
});

$twig->addGlobal('twc', $twc);
$phpFunction = get_defined_functions();

foreach ($phpFunction['internal'] as $key => $value) {
    $filter = new \Twig\TwigFilter($value, $value);
    $twig->addFilter($filter);
}
foreach ($phpFunction['user'] as $key => $value) {
    $filter = new \Twig\TwigFilter($value, $value);
    $twig->addFilter($filter);
}

$twd = array();