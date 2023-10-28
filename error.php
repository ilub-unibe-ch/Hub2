<?php
declare(strict_types=1);
/**
 * ErrorHandler
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
chdir(substr(__FILE__, 0, strpos(__FILE__, '/Customizing')));
include_once("./include/inc.header.php");
header("Location: /error.php");
