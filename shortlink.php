<?php
declare(strict_types=1);
/**
 * Handler
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/public')));

include_once './vendor/composer/vendor/autoload.php';

use srag\Plugins\Hub2\Shortlink\Handler;

$shortlink = new Handler($_GET['q']);
$shortlink->storeQuery();
$shortlink->tryILIASInit();
$shortlink->process();
