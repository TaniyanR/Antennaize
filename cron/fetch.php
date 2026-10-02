<?php
declare(strict_types=1);
require_once __DIR__.'/../app/rss.php';
$lock=fopen(sys_get_temp_dir().'/antennaize-fetch.lock','c');if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))exit("already running\n");echo json_encode(fetch_due_feeds(),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)."\n";flock($lock,LOCK_UN);fclose($lock);
