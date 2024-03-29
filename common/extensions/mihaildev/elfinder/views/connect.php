<?php
/**
 * @var array $options
 */

require_once(__DIR__ . '/../php/elFinderConnector.class.php');
require_once(__DIR__ . '/../php/elFinder.class.php');
require_once(__DIR__ . '/../php/elFinderVolumeDriver.class.php');
//require_once(__DIR__ . '/../php/elFinderVolumeLocalFileSystem.class.php');
//require_once(__DIR__ . '/../php/elFinderVolumeDropbox.class.php');
//require_once(__DIR__ . '/../php/elFinderVolumeFTP.class.php');
//require_once(__DIR__ . '/../php/elFinderVolumeMySQL.class.php');
require_once(__DIR__ . '/../php/elFinderVolumeS3.class.php');
//require_once(__DIR__ . '/../php/elFinderVolumeMySQL.class.php');

// run elFinder
$connector = new elFinderConnector(new elFinder($options));
$connector->run();