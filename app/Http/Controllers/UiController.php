<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\CommonHelper;
use Carbon\Carbon;

class UiController extends Controller {
  public function showIndex(Request $request){
    $battle_records = DB::table('battle_record')
    ->select('battle_record.*')
    ->orderBy('datetime', 'desc')
    ->limit(10)
    ->get();
    return view('index')->with('battle_records', $battle_records);
  }
  
  public function getBattleResult(Request $request){
    $data = $request->all();
    $chara1 = $data['chara1'];
    $chara2 = $data['chara2'];
    $magic = $data['magic'];

    //进攻方
    $battle_result = CommonHelper::getBattleResult($chara1, $magic, $chara2);
    $current_time = Carbon::now();
    $current_timestamp = $current_time->timestamp;

    $result_text = "{$chara1}使用咒語 {$magic} 攻击{$chara2}，实际命中率{$battle_result['实际命中率']}，实际暴击率{$battle_result['实际暴击率']}, 实际伤害值{$battle_result['实际伤害值']}";

    $battle_result_fields = [
      'chara1' => $chara1,
      'chara2' => $chara2,
      'battle_result' => $result_text,
      'datetime' => $current_timestamp,
    ];

    $insert_result = DB::table('battle_record')
    ->insert($battle_result_fields);

    return redirect('/');
  }
}