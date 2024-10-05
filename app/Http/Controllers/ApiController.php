<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\CommonHelper;

class ApiController extends Controller {

  /* Show Status, Item and Spt */
  public function showStatus(Request $request){
    $chara = $request->get('chara');
    $status_info = CommonHelper::loadCharaStatus($chara);
    return response()->json($status_info);
  }

  public function startBattle(Request $request){
    $chara1 = $request->get('chara1');
    $chara2 = $request->get('chara2');

    //进攻方
    $chara1_result = CommonHelper::getBattleResult($chara1, $chara2);
    //反击方
    $chara2_result = CommonHelper::getBattleResult($chara2, $chara1);

    $battle_result = [
      'chara1' => $chara1_result,
      'chara2' => $chara2_result,
    ];
    return response()->json($battle_result);
  }
}
