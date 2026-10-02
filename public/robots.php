<?php
declare(strict_types=1);
require_once __DIR__.'/../app/bootstrap.php';
$host=strtolower(preg_replace('/:\d+$/','',(string)($_SERVER['HTTP_HOST']??'')));header('Content-Type: text/plain; charset=utf-8');echo "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: https://".$host."/sitemap.xml\n";
