<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('header')
<body>
  @include('menu')
  <div class="main-content">
    <div class="card">
      <div class="card-header">
        生成战斗结果
      </div>
      <div class="card-body">
        <form action="/ui/battle" method="post">
          <div class="form-group">
            <label for="chara1">角色1</label>
            <input type="text" class="form-control" name="chara1" id="chara1" placeholder="角色1姓名" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">角色2</label>
            <input type="text" class="form-control" name="chara2" id="chara2" placeholder="角色2姓名" required>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">咒語</label>
            <input type="text" class="form-control" name="magic" id="magic" placeholder="咒語名稱" required>
          </div>
          <button type="submit" class="btn btn-primary">开始</button>
        </form>
      </div>
    </div>
    <div class="card battle-result">
      <div class="card-header">
        战斗记录
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col">进攻方</th>
              <th scope="col">防守方</th>
              <th scope="col">结果</th>
              <th scope="col">时间</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($battle_records as $battle_record)
              <tr>
                <td>{!! $battle_record->chara1 !!} </td>
                <td>{!! $battle_record->chara2 !!} </td>
                <td>{!! $battle_record->battle_result !!} </td>
                <td>{{ Carbon\Carbon::createFromTimestamp($battle_record->datetime, 'Asia/Hong_Kong') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
