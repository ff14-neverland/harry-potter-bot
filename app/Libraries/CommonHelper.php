<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;

class CommonHelper {
  public static function loadAllChara(){
    $status_infos = [];
    $charas = DB::table('zh')
    ->select('zh.*')
    ->get();

    foreach($charas as $chara){
      $status_info = [
        '名称' => $chara->zh,
        'HP' => $chara->hp,
        '力量' => $chara->ll,
        '魔力' => $chara->ml,
        '技巧' => $chara->jq,
        '幸运' => $chara->xy,
        '速度' => $chara->sd,
        '防御' => $chara->fy,
        '魔防' => $chara->mf,
        '支援' => $chara->zy,
        '其他' => $chara->qtxx,
      ];
      $status_infos[] = $status_info;
    }
    return $status_infos;
  }

  public static function loadCharaStatus($chara){
    $chara_status = DB::table('zh')
    ->select('zh.*')
    ->where('zh.zh', $chara)
    ->first();
    $status_info = [
      '名称' => $chara_status->zh,
      'HP' => $chara_status->hp,
      '力量' => $chara_status->ll,
      '魔力' => $chara_status->ml,
      '技巧' => $chara_status->jq,
      '幸运' => $chara_status->xy,
      '速度' => $chara_status->sd,
      '防御' => $chara_status->fy,
      '魔防' => $chara_status->mf,
      '支援' => $chara_status->zy,
      '其他' => $chara_status->qtxx,
    ];
    return $status_info;
  }

  public static function getBattleResult($chara1, $chara2){
    $chara1_status = DB::table('zh')
    ->select('zh.*')
    ->where('zh.zh', $chara1)
    ->first();

    $chara2_status = DB::table('zh')
    ->select('zh.*')
    ->where('zh.zh', $chara2)
    ->first();

    //物理伤害：A力量-B防御，最小0
    $phyical_damage = $chara1_status->ll - $chara2_status->fy;
    if($phyical_damage < 0){
      $phyical_damage = 0;
    }
    //魔法伤害：A魔力-B魔防，最小0
    $magical_damage = $chara1_status->ml - $chara2_status->mf;
    if($magical_damage < 0){
      $magical_damage = 0;
    }
    //命中率：（A技巧-B速度）*10 +80，最大100，最小0
    $hit_rate = ($chara1_status->jq - $chara2_status->sd) * 10 + 80;
    if($hit_rate < 0){
      $hit_rate = 0;
    }elseif($hit_rate > 100){
      $hit_rate = 100;
    }
    //暴击率：（A技巧*5 + A幸运*3 - B幸运*10 + 10），最大100，最小0
    $critical_rate = (($chara1_status->jq * 5) + ($chara1_status->xy * 3)) - ($chara2_status->xy * 10) + 10;
    if($critical_rate < 0){
      $critical_rate = 0;
    }elseif($critical_rate > 100){
      $critical_rate = 100;
    }

    //追击：如果A速度-B速度≥5 不追击：如果A速度-B速度＜5，这两条只选择显示一种。
    $pursue = FALSE;
    if($chara1_status->sd - $chara2_status->sd >= 5){
      $pursue = TRUE;
    }

    $battle_result = [
      'phyical_damage' => $phyical_damage,
      'magical_damage' => $magical_damage,
      'hit_rate' => $hit_rate,
      'critical_rate' => $critical_rate,
      'pursue' => $pursue,
    ];
    return $battle_result;
  }

  public static function getLevelUpResult($chara){
    $chara_status = DB::table('zh')
    ->select('zh.*')
    ->where('zh.zh', $chara)
    ->first();

    $level_up_result = [
      'hp' => FALSE,
      'power' => FALSE,
      'magical' => FALSE,
      'skill' => FALSE,
      'luck' => FALSE,
      'speed' => FALSE,
      'defense' => FALSE,
      'magical_defense' => FALSE,
    ];

    //所有成長率
    $hp_growth_rate = $chara_status->hpcz;
    $power_growth_rate = $chara_status->llcz;
    $magical_growth_rate = $chara_status->mlcz;
    $skill_growth_rate = $chara_status->jqcz;
    $luck_growth_rate = $chara_status->xycz;
    $speed_growth_rate = $chara_status->sdcz;
    $defense_growth_rate = $chara_status->fycz;
    $magical_defense_growth_rate = $chara_status->mfcz;

    //擲骰決定升級結果
    $hp_growth_result = mt_rand(1, 100);
    $level_up_result['hp_growth_result'] = $hp_growth_result;

    $power_growth_result = mt_rand(1, 100);
    $level_up_result['power_growth_result'] = $power_growth_result;

    $magical_growth_result = mt_rand(1, 100);
    $level_up_result['magical_growth_result'] = $magical_growth_result;

    $skill_growth_result = mt_rand(1, 100);
    $level_up_result['skill_growth_result'] = $skill_growth_result;

    $luck_growth_result = mt_rand(1, 100);
    $level_up_result['luck_growth_result'] = $luck_growth_result;

    $speed_growth_result = mt_rand(1, 100);
    $level_up_result['speed_growth_result'] = $speed_growth_result;

    $defense_growth_result = mt_rand(1, 100);
    $level_up_result['defense_growth_result'] = $defense_growth_result;

    $magical_defense_growth_result = mt_rand(1, 100);
    $level_up_result['magical_defense_growth_result'] = $magical_defense_growth_result;

    /*
    随机生成8个数字，范围1~100，分别和这8项比对，小于等于这个数则算作该项成功，大于这个数算作该项失败。
    例如：名字A的“HP”成长率为50，随机到50则HP+1，随机到51则不加。
    */
    if($hp_growth_result <= $hp_growth_rate){
      $level_up_result['hp'] = TRUE;
    }
    if($power_growth_result <= $power_growth_rate){
      $level_up_result['power'] = TRUE;
    }
    if($magical_growth_result <= $magical_growth_rate){
      $level_up_result['magical'] = TRUE;
    }
    if($skill_growth_result <= $skill_growth_rate){
      $level_up_result['skill'] = TRUE;
    }
    if($luck_growth_result <= $luck_growth_rate){
      $level_up_result['luck'] = TRUE;
    }
    if($speed_growth_result <= $speed_growth_rate){
      $level_up_result['speed'] = TRUE;
    }
    if($defense_growth_result <= $defense_growth_rate){
      $level_up_result['defense'] = TRUE;
    }
    if($magical_defense_growth_result <= $magical_defense_growth_rate){
      $level_up_result['magical_defense'] = TRUE;
    }
    return $level_up_result;
  }

  public static function updateInfo($chara, $type, $text){
    switch ($type){
      case 'other':
      $update_field = [
        'qtxx' => $text,
      ];
      $update_result = DB::table('zh')
      ->where('zh.zh', $chara)
      ->update($update_field);
      return $update_field;
      break;

      case 'support':
      $update_field = [
        'zy' => $text,
      ];
      $update_result = DB::table('zh')
      ->where('zh.zh', $chara)
      ->update($update_field);
      return $update_field;
      break;
    }
  }

  //自動升級，用不到(。)
  public static function updateAbility($chara, $level_up_result){
    $chara_status = DB::table('zh')
    ->select('zh.*')
    ->where('zh.zh', $chara)
    ->first();

    $update_field = [
      'hp' => (int)$chara_status->hp,
      'll' => (int)$chara_status->ll,
      'ml' => (int)$chara_status->ml,
      'jq' => (int)$chara_status->jq,
      'xy' => (int)$chara_status->xy,
      'sd' => (int)$chara_status->sd,
      'fy' => (int)$chara_status->fy,
      'mf' => (int)$chara_status->mf,
    ];

    if($level_up_result['hp']){
      $update_field['hp'] += 1;
    }
    if($level_up_result['power']){
      $update_field['ll'] += 1;
    }
    if($level_up_result['magical']){
      $update_field['ml'] += 1;
    }
    if($level_up_result['skill']){
      $update_field['jq'] += 1;
    }
    if($level_up_result['luck']){
      $update_field['xy'] += 1;
    }
    if($level_up_result['speed']){
      $update_field['sd'] += 1;
    }
    if($level_up_result['defense']){
      $update_field['fy'] += 1;
    }
    if($level_up_result['magical_defense']){
      $update_field['mf'] += 1;
    }

    //修改资料库资料
    $update_result = DB::table('zh')
    ->where('zh.zh', $chara)
    ->update($update_field);

    return $update_field;

  }
}
