<?php
declare(strict_types=1);
require_once __DIR__.'/../app/bootstrap.php';
$dir=__DIR__.'/../storage/cache';if(!is_dir($dir))mkdir($dir,0775,true);$ids=db()->query('SELECT id FROM antennas WHERE is_active=1')->fetchAll(PDO::FETCH_COLUMN);foreach($ids as $id){$s=db()->prepare('SELECT id,title,url,image_url,published_at FROM articles WHERE antenna_id=? AND is_deleted=0 ORDER BY COALESCE(published_at,created_at) DESC LIMIT 100');$s->execute([(int)$id]);file_put_contents($dir.'/antenna-'.(int)$id.'-latest.json',json_encode($s->fetchAll(),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),LOCK_EX);}echo "cache built\n";
