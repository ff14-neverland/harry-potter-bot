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
    $data = $request->all();
    $chara1 = $data['chara1'];
    $chara2 = $data['chara2'];
    $magic = $data['magic'];

    //进攻方
    $battle_result = CommonHelper::getBattleResult($chara1, $magic, $chara2);

    return response()->json($battle_result);
  }
}
