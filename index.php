<?php

require_once('lib/common.php');



echo $twig->render(FILE_MAIN_INTERFACE, ['twd'=>$twd, 'twc'=>$twc]);

?>