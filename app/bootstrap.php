<?php
declare(strict_types=1);
$configFile=__DIR__.'/../config/config.php';
if(!is_file($configFile)){$configFile=__DIR__.'/../config/config.example.php';}
$config=require $configFile;
date_default_timezone_set((string)($config['app']['timezone']??'Asia/Tokyo'));
function config(?string $key=null,mixed $default=null):mixed{global $config;if($key===null)return $config;$v=$config;foreach(explode('.',$key) as $s){if(!is_array($v)||!array_key_exists($s,$v))return $default;$v=$v[$s];}return $v;}
function db():PDO{static $pdo;if($pdo instanceof PDO)return $pdo;$d=config('db');$dsn=sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',$d['host'],$d['port'],$d['name'],$d['charset']);$pdo=new PDO($dsn,$d['user'],$d['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);return $pdo;}
function e(?string $v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function base_url(string $path=''):string{return rtrim((string)config('app.base_url',''),'/').'/'.ltrim($path,'/');}
function secure_headers():void{if(headers_sent())return;header('X-Content-Type-Options: nosniff');header('X-Frame-Options: SAMEORIGIN');header('Referrer-Policy: strict-origin-when-cross-origin');header('Permissions-Policy: geolocation=(), microphone=(), camera=()');}
function start_secure_session():void{if(session_status()===PHP_SESSION_ACTIVE)return;$secure=!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off';session_set_cookie_params(['httponly'=>true,'secure'=>$secure,'samesite'=>'Lax','path'=>'/']);session_start();}
function csrf_token():string{start_secure_session();if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function verify_csrf():void{start_secure_session();$t=(string)($_POST['csrf']??'');if(!hash_equals((string)($_SESSION['csrf']??''),$t)){http_response_code(419);exit('CSRF token mismatch');}}
function admin_required():void{start_secure_session();if(empty($_SESSION['admin_id'])){header('Location: '.base_url('admin/login0929.php'));exit;}}
function normalize_url(string $url):string{$url=trim($url);$p=parse_url($url);if(!$p||!isset($p['scheme'],$p['host']))return '';$scheme=strtolower($p['scheme']);if(!in_array($scheme,['http','https'],true))return '';$host=strtolower($p['host']);$path=$p['path']??'/';$q=[];if(!empty($p['query'])){parse_str($p['query'],$q);foreach(array_keys($q) as $k){if(preg_match('/^(utm_|fbclid$|gclid$)/i',$k))unset($q[$k]);}}$out=$scheme.'://'.$host.$path;if($q)$out.='?'.http_build_query($q);return rtrim($out,'/');}
function url_hash(string $url):string{return hash('sha256',normalize_url($url));}
function client_ip_hash():string{return hash('sha256',(string)($_SERVER['REMOTE_ADDR']??'0.0.0.0').'|Antennaize');}
secure_headers();
