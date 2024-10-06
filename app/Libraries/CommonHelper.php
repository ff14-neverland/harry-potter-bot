<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;

class CommonHelper {
  public static function loadCharaStatus($chara){
    $chara_status = DB::table('chara')
    ->select('chara.*')
    ->where('chara.name', $chara)
    ->first();
    $status_info = [
      '名稱' => $chara_status->name,
      '攻擊' => $chara_status->atk,
      '防禦' => $chara_status->def,
      '速度' => $chara_status->speed,
    ];
    return $status_info;
  }

  public static function loadMagicStatus($magic){
    $magic_status = DB::table('magic')
    ->select('magic.*')
    ->where('magic.magic', $magic)
    ->first();
    $status_info = [
      '名稱' => $magic_status->name,
      '攻擊' => $magic_status->atk,
      '命中' => $magic_status->hit,
      '暴擊' => $magic_status->critical,
    ];
    return $status_info;
  }

  public static function getBattleResult($chara1, $magic, $chara2){
    $chara1_status = DB::table('chara')
    ->select('chara.*')
    ->where('chara.name', $chara1)
    ->first();

    $chara2_status = DB::table('chara')
    ->select('chara.*')
    ->where('chara.name', $chara2)
    ->first();

    $magic_status = DB::table('magic')
    ->select('magic.*')
    ->where('magic.name', $magic)
    ->first();

    $real_hit = ($magic_status->hit + $chara1_status->speed) - ($chara2_status->speed *2);
    $real_critical = $magic_status->critical - (($chara2_status->def * 2) - $chara1_status->atk);
    $real_damage = ($chara1_status->atk - $chara2_status->def + $magic_status->atk);

    $battle_result = [
      '实际命中率' => $real_hit,
      '实际暴击率' => $real_critical,
      '实际伤害值' => $real_damage,
    ];
    return $battle_result;
  }
}
