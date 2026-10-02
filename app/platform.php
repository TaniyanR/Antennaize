<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';
interface PlatformAdapter{public function key():string;public function validate(array $antenna):array;public function publish(array $antenna,array $payload):array;}
function adapter_for(string $type):PlatformAdapter{$map=['livedoor'=>[__DIR__.'/../adapters/LivedoorAdapter.php','LivedoorAdapter'],'fc2'=>[__DIR__.'/../adapters/Fc2Adapter.php','Fc2Adapter'],'server'=>[__DIR__.'/../adapters/ServerAdapter.php','ServerAdapter']];if(!isset($map[$type]))throw new InvalidArgumentException('未対応プラットフォーム');require_once $map[$type][0];$c=$map[$type][1];return new $c();}
