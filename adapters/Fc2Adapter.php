<?php
declare(strict_types=1);
final class Fc2Adapter implements PlatformAdapter{
 public function key():string{return'fc2';}
 public function validate(array $a):array{$c=json_decode((string)($a['platform_config']??'{}'),true)?:[];$ok=!empty($c['endpoint'])&&!empty($c['username'])&&!empty($c['password']);return['ok'=>$ok,'message'=>$ok?'設定済み':'FC2連携 endpoint / username / password を設定してください'];}
 public function publish(array $a,array $p):array{throw new RuntimeException('FC2投稿Transportは接続先仕様確認後に有効化します。HTML/CSS管理と公開APIは利用可能です。');}
}
