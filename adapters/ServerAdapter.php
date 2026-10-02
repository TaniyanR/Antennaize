<?php
declare(strict_types=1);
final class ServerAdapter implements PlatformAdapter{
 public function key():string{return'server';}
 public function validate(array $a):array{return['ok'=>!empty($a['host']),'message'=>!empty($a['host'])?'設定済み':'hostを設定してください'];}
 public function publish(array $a,array $p):array{return['ok'=>true,'message'=>'Server型はDB/API更新を即時公開します'];}
}
