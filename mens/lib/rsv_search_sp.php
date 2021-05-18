<?php

//30分後の予約？フリー？
//前提：希望店舗と希望日程があり
//デフォルト：店舗順
//$_GET['nittei1']="2014/01/31";
//$_GET['tenpo1'] = "新宿本店";

//店舗リスト
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
  $shop_list[$result['id']] = $result['name'];
  $shop_id_list[$result['name']] = $result['id'];
}

//データ整形
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
                $shop_id_list[$_GET['tenpo10']],
                $shop_id_list[$_GET['tenpo11']], 
                $shop_id_list[$_GET['tenpo12']], 
                $shop_id_list[$_GET['tenpo13']],
                $shop_id_list[$_GET['tenpo14']], 
                $shop_id_list[$_GET['tenpo15']], 
                $shop_id_list[$_GET['tenpo16']],
                $shop_id_list[$_GET['tenpo17']], 
                $shop_id_list[$_GET['tenpo18']], 
                $shop_id_list[$_GET['tenpo19']], 
                $shop_id_list[$_GET['tenpo20']], 
                $shop_id_list[$_GET['tenpo21']], 
                $shop_id_list[$_GET['tenpo22']], 
                $shop_id_list[$_GET['tenpo23']], 
                $shop_id_list[$_GET['tenpo24']], 
                $shop_id_list[$_GET['tenpo25']], 
                $shop_id_list[$_GET['tenpo26']], 
                $shop_id_list[$_GET['tenpo27']], 
                $shop_id_list[$_GET['tenpo28']], 
                $shop_id_list[$_GET['tenpo29']],
                $shop_id_list[$_GET['tenpo30']],
                $shop_id_list[$_GET['tenpo31']],
                $shop_id_list[$_GET['tenpo32']],
                $shop_id_list[$_GET['tenpo33']],
                $shop_id_list[$_GET['tenpo34']],
                $shop_id_list[$_GET['tenpo35']],
                $shop_id_list[$_GET['tenpo36']],
                $shop_id_list[$_GET['tenpo37']],
                $shop_id_list[$_GET['tenpo38']],
                $shop_id_list[$_GET['tenpo39']],
                $shop_id_list[$_GET['tenpo40']]
                );
arsort($tennpo);

//HTMLデータ格納
$table = "reservation";
$length = $_GET[nunzu]>1 ? 3:2 ;//一人：１時間、二人：１時間半

$rsv_list = array();


//$rsv_list[] = array('html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr><th>日付</th><th>時間</th><th>店舗</th><th class="nobrd">予約</th></tr></table>' );

if($_GET['sort']=="time"){
  $rsv_list = getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime);
}else{
  $rsv_list = getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime);
}



//関数*************************************************************************
function getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime){
   $is_nittei = false;
    //$rsv_list[] = array('html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr><th>日付</th><th>カウンセリング<br>予約時間</th><th>店舗</th><th>予約</th></tr>' );
  //希望店舗ループ
  foreach ($tennpo as $keys => $value) {
    if(!$value) continue; 
    $shop = Get_Table_Row("shop"," WHERE del_flg = 0  AND status=2 and id = '".addslashes($value)."'");

    //希望日程ループ
    $gTimeToday = $gTime2;
    foreach ($nittei as $keys1 => $value1) {
       /*if(!$value1 || $value1=="2014/04/23") continue; //暫定

      if($value==28 && $value1<"2015/07/10") continue;
      if($value==27 && $value1<"2015/06/27") continue;
      if($value==26 && $value1<"2015/06/27") continue;
      if($value==25 && $value1<"2015/06/10") continue;
      if($value==24 && $value1<"2015/06/05") continue;
      if($value==23 && $value1<"2015/05/26") continue;
      if($value==22 && $value1<"2015/05/29") continue; //横浜西口駅前店が05/29前予約禁止？
      if($value==21 && $value1<"2015/05/12") continue; //四条河原町店が05/12前予約禁止
      if($value==20 && $value1<"2015/04/29") continue; //秋葉原店が04/29前予約禁止
      if($value==19 && $value1<"2015/04/28") continue; //梅田店が04/28前予約禁止
      if($value==17 && $value1<"2015/04/25") continue; //神戸元町店が04/25前予約禁止
      if($value==18 && $value1<"2015/04/10") continue; //宇都宮店が04/10前予約禁止
      if($value==16 && $value1<"2015/03/31") continue; //心斎橋店が03/31前予約禁止
      if($value==15 && $value1<"2015/03/14") continue; //渋谷宮益坂店が03/14前予約禁止
      
      if($value1>='2014/12/29' && $value1<='2015/01/03') continue;*/

      

      if(!$value1 ) continue; 
      $hope_date = str_replace("/", "-", $value1);

      //オープン前と非営業日に予約不可
      if( array_key_exists($hope_date,$gClosed) && in_array($shop['id'],$gClosed[$hope_date])  || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($hope_date) ){
        continue;
      }

      $room_list = array();
      for ($i = 1; $i <= $shop['counseling_rooms'] ; $i++) $room_list[] = "1".$i;
      for ($i = 1; $i <= $shop['vip_rooms']; $i++)    $room_list[] = "2".$i;


      if($hope_date=="2014-02-28") $time_list  = array(  3=>"12:00" ,  5=>"13:00" );
      //大宮店19時後予約禁止
//      elseif($hope_date=="2014-09-10" && $value==5)  $time_list  = array(   3=>"12:00" , 5=>"13:00" ,  7=>"14:00" , 9=>"15:00" , 11=>"16:00" ,  13=>"17:00" , 15=>"18:00" );

      //達成会：19時後予約禁止
      elseif(  array_key_exists($hope_date,$gParty) && in_array($value,$gParty[$hope_date]) ){
          if($length==2){
            $time_list = array(1=>"11:00",  3=>"12:00",  5=>"13:00",  7=>"14:00",  9=>"15:00", 11=>"16:00");
          }else{
            $time_list = array(1=>"11:00",  3=>"12:00",  5=>"13:00",  7=>"14:00",  9=>"15:00");
          }
      }  
      else $time_list = array(
          1=>"11:00",  3=>"12:00",  5=>"13:00",  7=>"14:00",  9=>"15:00", 
          11=>"16:00",  13=>"17:00",  15=>"18:00",  17=>"19:00",  19=>"20:00"
      );

      //当時前unset
      if($hope_date==date("Y-m-d")){
        foreach ($time_list as $key => $val){
           if ($val<=date("H:i")) unset($time_list[$key]);
        }
      }

      //営業時間表ループ
      foreach($time_list as $keys2 => $value2){

        //希望店舗ルームループ
        $is_empty = false;
        foreach ($room_list as $keys3 => $value3) {
          //カウンセリング1,早番、遅番,新宿本店,池袋店,新宿南口店除外
          //if( $value > 2 && $s<>6  && $value3==11 && ($keys2<4 || $keys2>16) )continue;

          //カウンセリング1,$time_list～:30,新宿本店,池袋店,新宿南口店除外                               //カウンセリング1以外、$time_list～:00 
          //if(($value > 2 && $value<>6 && $value3 == 11 && (strrpos($value2, ':30'))) || ( ( ( ($value <= 2 || $value==6) && $value3 == 11) || $value3 != 11) && (strrpos($value2, ':00'))))
          //{ 
            // length=3(1時間半の場合)の場合の カンセリング1 19:30、 カンセリング1以外　21:00を超えない対処 
           // if(!(in_array($value2, array("18:30", "20:00")) && ($length >= 2))){

              $sql  = " WHERE  type<>3 and type<>14 and type<>21 and type<>22 and del_flg=0 and hope_date='".addslashes($hope_date)."' AND shop_id=".$value." AND room_id=".$value3;

              $sql .= " AND (hope_time<".$keys2 ." AND hope_time+length>".$keys2 ;//予約開始時間と比較(重なりあり)
              //$sql .= " OR hope_time>=".$keys2." AND hope_time<".($keys2+$length) ;
              $sql .= " OR hope_time<".($keys2+$length) ." AND hope_time+length>".($keys2+$length) ;//予約終了時間と比較(重なりあり)
              $sql .= " OR hope_time>=".$keys2 ." AND hope_time+length<=".($keys2+$length) . ")";//中にあり

              $empty[$value3] = Get_Table_Row($table,$sql) ? 0 : 1;
              //最初のROOMの空きがあれば
              if($empty[$value3]) {
                $is_empty = true;
                break;
              }
           // }
          //}
        }


        //終了時間＞２１時
        if(($keys2+$length)>21)$is_empty = false;

        //VIP予約時間：13時から18時
        // if( ($value3==21 || $value3==14) && ($keys2<5 || $keys2>15) ) $is_empty = false;

        //カウンセリング4,早番、遅番
        //if( $value3==11 && ($keys2<5 || $keys2>15) ) $is_empty = false;

         if($value == 2 && $value3==13 && $value1=="2014/05/09" && $keys2<5) $is_empty = false; //池袋のカウンセリング３，5/9、11,12時のみ

        if($is_empty) {
            $rsv_list[] = array(
              'html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr>
                 <td class="date"><span class="'.getDateClass($value1).'">'.getDateYobi2($value1).'</span></td>
                     <td class="time"><span>'.$value2.'</span></td>
                     <td class="salon"><span>'.$shop_list[$value].'</span></td>
                     <td class="nobrd"><p><a href="#step3" onclick="return result(\''.$shop_list[$value].'\',\''.$value1.'\',\''.$value2.'\',\''.$value.'\',\''.$length.'\');" class="yoyakubi"><img src="../img/counseling/btn_select_on.gif" width="100%" alt="選択"></a></p></td>
                 </tr>'
            );
            $is_nittei = true;//別の日程に指定
        }
      }   
    }
  }
   $rsv_list[] = array('html' => '</table>' );

     //別の日程に指定
  if(!$is_nittei && ($_GET['tenpo1'] || $_GET['tenpo2'] || $_GET['tenpo3'] || $_GET['tenpo4'] || $_GET['tenpo5'] || 
                     $_GET['tenpo6'] || $_GET['tenpo7'] || $_GET['tenpo8'] || $_GET['tenpo9'] || $_GET['tenpo10'] || 
                     $_GET['tenpo11'] || $_GET['tenpo12'] || $_GET['tenpo13'] || $_GET['tenpo14'] || $_GET['tenpo15'] || 
                     $_GET['tenpo16'] || $_GET['tenpo17'] || $_GET['tenpo18'] || $_GET['tenpo19'] || $_GET['tenpo20'] || 
                     $_GET['tenpo21'] || $_GET['tenpo22'] || $_GET['tenpo23'] || $_GET['tenpo24'] || $_GET['tenpo25'] || 
                     $_GET['tenpo26'] || $_GET['tenpo27'] || $_GET['tenpo28'] || $_GET['tenpo29'] || $_GET['tenpo30'] ||
                     $_GET['tenpo31'] || $_GET['tenpo32'] || $_GET['tenpo33'] || $_GET['tenpo34'] || $_GET['tenpo35'] || 
                     $_GET['tenpo36'] || $_GET['tenpo37'] || $_GET['tenpo38'] || $_GET['tenpo39'] || $_GET['tenpo40']
										 )
      && ($_GET['nittei1'] || $_GET['nittei2'] || $_GET['nittei3'])){
   $rsv_list = array();
    $rsv_list[] = array(
              'html' => ' <p class="search_error error"><a href="#step1" class="btn_grd">再度ご予約の空き状況検索する</a><br>別の日程をご選択ください。</p>'
              );

 }
  return $rsv_list;
}

function getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTime){
    $rsv_list[] = array('html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr><th>日付</th><th>カウンセリング<br>予約時間</th><th>店舗</th><th>予約</th></tr>' );
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
        
        foreach ($room_list as $keys3 => $value3) {
            $sql  = " WHERE type<>3 and del_flg=0 and hope_date='".addslashes($hope_date)."' AND shop_id=".$value." AND room_id=".$value3;

            $sql .= " AND (hope_time<".$keys2 ." AND hope_time+length>".$keys2 ;//予約開始時間と比較(重なりあり)
            $sql .= " OR hope_time>=".$keys2." AND hope_time<".($keys2+$length) ;
            $sql .= " OR hope_time<".($keys2+$length) ." AND hope_time+length>".($keys2+$length) ;//予約終了時間と比較(重なりあり)
            $sql .= " OR hope_time>=".$keys2 ." AND hope_time+length<=".($keys2+$length) . ")";//中にあり

            $empty[$value3] = Get_Table_Row($table,$sql) ? 0 : 1;
            //最初のROOMの空きがあれば
            if($empty[$value3]) {
              $is_empty = true;
              break;
            } 
        }
        //終了時間＞２１時
        if(($keys2+$length)>21)$is_empty = false;
        if($is_empty) {
            $rsv_list[] = array(
              'html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr>
                 <td class="date"><span class="'.getDateClass($value1).'">'.getDateYobi2($value1).'</span></td>
                     <td class="time"><span>'.$value2.'</span></td>
                     <td class="salon"><span>'.$shop_list[$value].'</span></td>
                     <td class="nobrd"><p><a href="#step3" onclick="return result(\''.$shop_list[$value].'\',\''.$value1.'\',\''.$value2.'\',\''.$value.'\');" class="yoyakubi"><img src="../img/counseling/btn_select_on.gif" width="100%" alt="選択"></a></p></td>
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