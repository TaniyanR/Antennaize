<?php
declare(strict_types=1);
require_once __DIR__.'/../app/ranking.php';
$lock=fopen(sys_get_temp_dir().'/antennaize-rank.lock','c');if(!$lock||!flock($lock,LOCK_EX|LOCK_NB))exit("already running\n");rebuild_rankings();echo "rankings rebuilt\n";flock($lock,LOCK_UN);fclose($lock);
