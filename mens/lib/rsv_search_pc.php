<?php
// 店舗リスト
$shop_list = getDatalist_shop();

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
  $shop_list[$result['id']] = $result['name'];
  $shop_id_list[$result['name']] = $result['id'];
}

$mensdb = changedb();

// データ整形
$nittei = array($_GET['nittei1'],$_GET['nittei2'],$_GET['nittei3']);
asort($nittei);

$tennpo = array($shop_id_list[$_GET['tenpo1']],
                $shop_id_list[$_GET['tenpo2']],
                $shop_id_list[$_GET['tenpo3']],
                $shop_id_list[$_GET['tenpo4']],
                $shop_id_list[$_GET['tenpo5']],
                $shop_id_list[$_GET['tenpo6']],
                $shop_id_list[$_GET['tenpo7']],
                $shop_id_list[$_GET['tenpo8']],
                $shop_id_list[$_GET['tenpo9']],
                $shop_id_list[$_GET['tenpo10']]
                );

// 配列を逆順にソートする
arsort($tennpo);

// HTMLデータ格納
$table = "reservation";

// キャンペーン申込み時間 なし：60分 あり：120分
// $length = $_GET[hope_campaign_checked]==1 ? 2 :4;
// 一人：１時間、二人：１時間半
$length = $_GET[nunzu]>1 ? 3:2 ;
$rsv_list = array();


if($_GET['sort']=="time"){
  // $rsv_list = getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime,$gEndingShop,$gEndingDate,$gEndingTime);
  $rsv_list = getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2);
}else{
  // $rsv_list = getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime,$gEndingShop,$gEndingDate,$gEndingTime);
  $rsv_list = getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2);
}



// 関数*************************************************************************
// function getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime,$gEndingShop,$gEndingDate,$gEndingTime){
function getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2){
  $is_nittei = false;
  $rsv_list[] = array('html' => '<table class="date"><tbody><tr><th>日付</th><th>時間</th><th>店舗</th><th>予約</th></tr>' );

  // 希望店舗ループ
  foreach ($tennpo as $keys => $value) {
    if(!$value) continue;
    $shop = Get_Table_Row("shop"," WHERE del_flg = 0  AND status=2 and id = '".addslashes($value)."'");
    // 希望日程ループ ?
    $gTimeToday = $gTime2;
    foreach ($nittei as $keys1 => $value1) {
      if(!$value1) continue;
      $hope_date = str_replace("/", "-", $value1);
      // オープン前と非営業日に予約不可
      if( array_key_exists($hope_date,$gClosed) && in_array($shop['id'],$gClosed[$hope_date])  || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($hope_date) ){
        continue;
      };
      $room_list = array();
      for ($i = 1; $i <= $shop['counseling_rooms'] ; $i++) $room_list[] = "1".$i;
      for ($i = 1; $i <= $shop['vip_rooms']; $i++)    $room_list[] = "2".$i;
      if($hope_date=="2014-02-28") $time_list  = array(  3=>"12:00" ,  5=>"13:00" );
      // 達成会など：15時後予約禁止
      elseif(  array_key_exists($hope_date,$gParty) && in_array($value,$gParty[$hope_date]) ){
          if($length==2){
            $time_list = $gPartyTimeList1 ;
          }else{
            $time_list = $gPartyTimeList2 ;
          }
      }
      else $time_list = array(
          1=>"12:00",  3=>"13:00",  5=>"14:00",  7=>"15:00",  9=>"16:00",
          11=>"17:00",  13=>"18:00",  15=>"19:00",  17=>"20:00"
      );
      // 当時前unset
      if($hope_date==date("Y-m-d")){
        foreach ($time_list as $key => $val){
           if ($val<=date("H:i")) unset($time_list[$key]);
        }
      }
      // 営業時間表ループ
      $mensdb = changedb();
      foreach($time_list as $keys2 => $value2){
        // 希望店舗ルームループ
        $is_empty = false;
        foreach ($room_list as $keys3 => $value3) {
            // length=3(1時間半の場合)の場合の カンセリング1 19:30、 カンセリング1以外 21:00を超えない対処
           // if(!(in_array($value2, array("18:30", "20:00")) && ($length > 2))){
              $sql  = " WHERE  type<>3 and type<>14 and type<>21 and type<>22 and del_flg=0 and hope_date='".addslashes($hope_date)."' AND shop_id=".$value." AND room_id=".$value3;
              $sql .= " AND (hope_time<".$keys2 ." AND hope_time+length>".$keys2 ; // 予約開始時間と比較(重なりあり)
              $sql .= " OR hope_time<".($keys2+$length) ." AND hope_time+length>".($keys2+$length) ; // 予約終了時間と比較(重なりあり)
              $sql .= " OR hope_time>=".$keys2 ." AND hope_time+length<=".($keys2+$length) . ")"; // 中にあり
              $empty[$value3] = Get_Table_Row($table,$sql) ? 0 : 1;
              // 最初のROOMの空きがあれば
              if($empty[$value3]) {
                $is_empty = true;
                break;
              }
           // }
        }
        // 終了時間＞２１時
        if( ($keys2+$length)>21 ) $is_empty = false;
        if($is_empty) {
            $rsv_list[] = array(
              'html' =>
              '<tr>
                <td><span class="'.getDateClass($value1).'">'.getDateYobi2($value1).'</span></td>
                <td>'.$value2.'</td>
                <td>'.$shop_list[$value].'</td>
                <td>
                  <a href="#step2" onclick="return result(\''.$shop_list[$value].'\',\''.$value1.'\',\''.$value2.'\',\''.$value.'\',\''.$length.'\');" class="yoyakubi"><input type="button" value="選択する"></a></p>
                </td>
              </tr>'
            );
            // 別の日程に指定
            $is_nittei = true;
        }
      }
    }
  }
  $rsv_list[] = array('html' => '</tbody></table>' );

  // 別の日程に指定
  if(!$is_nittei && ($_GET['tenpo1'] || $_GET['tenpo2'] || $_GET['tenpo3'] || $_GET['tenpo4'] || $_GET['tenpo5'] ||
                     $_GET['tenpo6'] || $_GET['tenpo7'] || $_GET['tenpo8'] || $_GET['tenpo9'] || $_GET['tenpo10']
										 )
      && ($_GET['nittei1'] || $_GET['nittei2'] || $_GET['nittei3'])){
    $rsv_list = array();
    $rsv_list[] = array(
              'html' => '<p class="search_error error">別の日程をお選びください。<a href="#step1"><input type="button" value="再選択する"></a></p>'
              );
  }
  return $rsv_list;
}

function getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$gTime3,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2){
  $rsv_list[] = array('html' => '<table class="date"><tr><th>日付</th><th>時間</th><th>店舗</th><th>予約</th></tr>' );

  $gTimeToday = $gTime2;
  foreach ($nittei as $keys1 => $value1) {
    if(!$value1) continue;
    $hope_date = str_replace("/", "-", $value1);

    if($hope_date==date("Y-m-d")){
        foreach ($gTimeToday as $key => $val){
           if ($val<=date("H:i")) unset($gTimeToday[$key]);
        }
        $time_list = $gTimeToday;
    }else  $time_list = $gTime2;


    foreach($time_list as $keys2 => $value2){
      foreach ($tennpo as $keys => $value) {
        if(!$value) continue;
        $shop = Get_Table_Row("shop"," WHERE del_flg = 0  AND status=2 and id = '".addslashes($value)."'");

        //room_list
        for ($i = 1; $i <= $shop['counseling_rooms']; $i++) $room_list[] = "1".$i;
        for ($i = 1; $i <= $shop['vip_rooms']; $i++)    $room_list[] = "2".$i;
        $is_empty = false;
$mensdb = changedb();
        foreach ($room_list as $keys3 => $value3) {
            $sql  = " WHERE type<>3 and del_flg=0  hope_date='".addslashes($hope_date)."' AND shop_id=".$value." AND room_id=".$value3;

            $sql .= " AND (hope_time<".$keys2 ." AND hope_time+length>".$keys2 ; // 予約開始時間と比較(重なりあり)
            $sql .= " OR hope_time>=".$keys2." AND hope_time<".($keys2+$length) ;
            $sql .= " OR hope_time<".($keys2+$length) ." AND hope_time+length>".($keys2+$length) ; // 予約終了時間と比較(重なりあり)
            $sql .= " OR hope_time>=".$keys2 ." AND hope_time+length<=".($keys2+$length) . ")"; // 中にあり

            $empty[$value3] = Get_Table_Row($table,$sql) ? 0 : 1;

            // 最初のROOMの空きがあれば
            if($empty[$value3]) {
              $is_empty = true;
              break;
            }
        }
        // 終了時間＞21時
        if(($keys2+$length)>21)$is_empty = false;
        if($is_empty) {
            $rsv_list[] = array(
              'html' =>
              '<tr>
                <td><span class="'.getDateClass($value1).'">'.getDateYobi2($value1).'</span></td>
                <td>'.$value2.'</td>
                <td>'.$shop_list[$value].'</td>
                <td>
                  <a href="#step2" onclick="return result(\''.$shop_list[$value].'\',\''.$value1.'\',\''.$value2.'\',\''.$value.'\',\''.$length.'\');" class="yoyakubi"><input type="button" value="選択する"></a></p>
                </td>
              </tr>'
            );
        }
      }
    }
  }
   $rsv_list[] = array('html' => '</table>' );
  return $rsv_list;
}

function getDateYobi2($date){
  $week = array("日", "月", "火", "水", "木", "金", "土");
  $time = strtotime($date);
  $w = date("w", $time);

  return $date."（".$week[$w]."）";
}

function getDateClass($date){
  $w = date("w",  strtotime($date));
  if($w==0) return "sun";
  if($w==6) return "sat";
  else return false;
}

header('Content-type: application/json; charset=UTF-8');
echo json_encode($rsv_list);
?>
