<?php
$DOC_ROOT = empty($_SERVER['DOCUMENT_ROOT']) ? str_replace('/admin/library/main', '', dirname(__FILE__)) : $_SERVER['DOCUMENT_ROOT'];
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';


header("Content-type: text/plain; charset=UTF-8");

// 店舗リスト
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//日付け設定
$_POST['hope_date'] = $_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$hope_date = $_POST['hope_date'];
$pre_date = date("Y-m-d", strtotime($hope_date." -1day"));
$next_date = date("Y-m-d", strtotime($hope_date." +1day"));

// 顧客ID
if(isset($_POST['customer_id'])){
  $customer_id = $_POST['customer_id'];
}

// room_list部屋情報設定
function room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName,$name_switch = null){
  $counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;
  // $medical_rooms = $shop['medical_rooms'] ? $shop['medical_rooms'] : $MedicalRooms;
  $vip_rooms = $shop['vip_rooms'] ? $shop['vip_rooms'] : $VIPRooms;
  $ninety_time_rooms = $shop['ninety_time_rooms'];
  $sixty_time_rooms = $shop['sixty_time_rooms'];
  $thirty_time_rooms = $shop['thirty_time_rooms'];
  $special_rooms = $shop['special_rooms'];


  $_POST['shop_id'] = $shop_id;
  $_POST['hope_date'] = $hope_date;
  $room_availability_url = $_SERVER['DOCUMENT_ROOT'].'/admin/library/main/room_availability.php';
  include($room_availability_url);

  for ($i = 1; $i <= $counseling_rooms; $i++) {
    $no = "1".$i;
    $room_list[$no] = $CounselingRoomName.$i;
  }
  if($shop['vip_rooms']){
    for ($i = 1; $i <= $vip_rooms; $i++) {
      $no = "2".$i;
      $room_list[$no] = $VIPRoomName.$i;
      if($name_switch == 'on'){$room_list[$no] .= "<br />パック";}
    }
  }
  // 新宿店施術ルームを４に
  $m_room2="3".($shop['pack_rooms']+2);
  for ($i = 1; $i <= $ninety_time_rooms; $i++) {
    //if($_POST['shop_id']==9 && $i==5) continue;
    $no = "3".$i;
    $room_list[$no] = $ninetyTimeRoomsName.$i;
  //   if($i<=$shop['pack_rooms'] && $name_switch == 'on'){
  //     $room_list[$no] .= "<br />パック";
  //   }else if($name_switch == 'on'){
  //     $room_list[$no] .= "<br />新規枠+旧月額";
  // };
  }


for ($i = 1; $i <= $sixty_time_rooms; $i++) {
    $no = "5" . $i;
    $room_list[$no] = $sixtyTimeRoomsName . $i;
}
for ($i = 1; $i <= $thirty_time_rooms; $i++) {
    $no = "6" . $i;
    $room_list[$no] = $thirtyTimeRoomsName . $i;
}


if ($shop['chiropractic_flg'] == 1) {
    for ($i = 1; $i <= $special_rooms; $i++) {
        $no = "7" . $i;
        $room_list[$no] = $specialRoomsName . $i;
    }
}


  // その他ルーム4
  for ($i = 1; $i <= $OtherRooms; $i++) {
    $no = "4".$i;
    // if($i == 1){
    //  $room_list[$no] = "新規枠";
    // }else{
      $room_list[$no] = $OtherRoomName.$i;
    // }
  }
  return $room_list;
}
//reservation予約情報取得
function search($shop_id,$shop,$hope_date){
  $table = "reservation";
  // 検索条件の設定------------------------------------------------------------------------
  $dWhere .= " AND  r.hope_date='".$hope_date."'";
  // $dWhere .= " AND  r.shop_id='".($shop_id ? $shop_id : 1)."'";
  $dWhere .= " AND  r.shop_id='".$shop_id."'";

  // データの取得,店舗ID、部屋ID、日付け、時間、所要時間
  $dSql = "SELECT r.room_id,r.hope_time,r.length,r.type,r.course_id,r.part,u.type as course,c.ctype FROM ". $table ." as r LEFT JOIN course as u ON r.course_id=u.id , customer as c WHERE c.id=r.customer_id AND c.del_flg=0 AND r.del_flg = 0 AND r.type<>3".$dWhere." ORDER BY r.room_id , r.hope_time ";//cancel: status = 3
  $dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

  $i=1;
  while ( $result = $dRtn->fetch_assoc() ) {
    $data[$result['room_id']][$i]['hope_time'] = $result['hope_time'];
    $data[$result['room_id']][$i]['length'] = $result['length'];
    $data[$result['room_id']][$i]['type'] = $result['type'];
    $data[$result['room_id']][$i]['course'] = $result['course'];
    $data[$result['room_id']][$i]['ctype'] = $result['ctype'];
    $data[$result['room_id']][$i]['part'] = $result['part'];
    $i++;
  }
  return $data;
}

//出勤人数の取得
function count_member($hope_date,$shop_id){
  global $gShiftType;
  $shift_table = "shift";
  $shift_month = substr($hope_date,0,7);
  $current_day = date("j",strtotime($hope_date)); //月:先頭にゼロをつけない。
  $selected_field = "day".$current_day;
  // 検索条件の設定-------------------------------------------------------------------
  $dWhere =" WHERE del_flg=0 ";
  $dWhere .= " AND shop_id='".$shop_id."'";//shop_idの設定
  $dWhere .= " AND shift_month='".$shift_month."'";//月の設定
  $dWhere .= " AND day".$current_day." NOT IN(0,4,5,7,11,12,37,38,39,40,41,56,57,58,59,60,61,62,63,64)";//休み除外.基本休、希望休、欠勤、有休、忌引、夏季休暇
  $selecter = "staff_id,day".$current_day;
  // データの取得----------------------------------------------------------------------
  $dSql = "SELECT ".$selecter." FROM " . $shift_table . $dWhere." ORDER BY day".$current_day.",staff_id ";
  $dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

  $sql = $GLOBALS['mysqldb']->query( "select id,new_face,type from staff WHERE del_flg = 0  AND status=2 " );

  if($sql){
    while ( $result = $sql->fetch_assoc() ) {
      $staff_staus[$result['id']] = $result['new_face'];
      $staff_type[$result['id']] = $result['type'];
    }
  }
  if ( $dRtn3->num_rows >= 1 ){
    $i=0;
    while ( $data = $dRtn3->fetch_assoc() ) {
       if(!$staff_staus[$data['staff_id']] && $gShiftType[$data[$selected_field]]<>"欠") $i++;
    }
    return $i;
  }
}

//各店舗予定の表示
function availability($shop,$room_list_name,$data,$shop_id,$hope_date,$open_date,$close_date,$gWday,$gClosed,$gParty,$gPartyTime,$gEndingDate,$gEndingShop,$gEndingTime,$table_switch,$customer_id,$color_switch){
  $strtotime_open_date = strtotime($open_date);
  $strtotime_close_date = strtotime($close_date);
  $strtotime_hope_date = strtotime($hope_date);

  // if(!$shop_id) $shop_id = 1;
  if ($room_list_name){
    $i = 1; // 横ライン
    $count_member = count_member($hope_date,$shop_id); //出勤人数
    if($count_member>0){
      $count_member .="人";
    }else{
      $count_member = "人数未定";
    }
    if($table_switch === 'table_on'){ //横並び3列で予約表を表示させる場合
      $echos = '<div class="d_tcell">';
    }else{
      $echos = '<div>';
    }
    $echos .= '<div class="schedule_box">';
    // if($customer_id !== ''){ // 顧客IDがある場合、店舗名に予約ページへのリンク追加
      // $echos .= '<a href="../reservation/edit.php?mode=new_rsv&shop_id='.$shop_id.'&hope_date='.$hope_date.'&customer_id='.$customer_id.'" target="_blank">';
    // }
    $day_num = (int)date('w',$strtotime_hope_date);
    if($day_num === 0){$date_class = ' hope_date';
    }else if($day_num === 6){$date_class = ' blue_date';
    }else{$date_class = '';}
    $echos .= '<div class="f_b_16'.$date_class.'">'.$shop["name"].'／'.$hope_date.'（'.$gWday[$day_num].'）'.$count_member.'</div>';//店舗名、日付、出勤人数取得
    if($customer_id !== ''){ // 顧客IDがある場合、店舗名に予約ページへのリンク追加
      $echos .= '</a>';
    }
    if(!$customer_id){// 顧客IDなし、店舗の予約表へリンク
      $echos .= '<a href="../main/?shop_id='.$shop_id.'&hope_date='.$hope_date.'">';
    }
    $echos .= '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="mini_table2">
          <tr>
            <td class="r_name th">Room</td>
            <td colspan="2" class="b_left th">11</td>
            <td colspan="2" class="b_left th">12</td>
            <td colspan="2" class="b_left th">13</td>
            <td colspan="2" class="b_left th">14</td>
            <td colspan="2" class="b_left th">15</td>
            <td colspan="2" class="b_left th">16</td>
            <td colspan="2" class="b_left th">17</td>
            <td colspan="2" class="b_left th">18</td>
            <td colspan="2" class="b_left th">19</td>
            <td colspan="2" class="b_left th">20</td>
          </tr>';//時間の表示
    foreach($room_list_name as $key => $room_name){
      //ルーム名の取得
      $room_name = str_replace("カウンセリング", "C", $room_name);
      $room_name = str_replace("トリートメント", "T", $room_name);
      //if($shop_id==9 && $key==35) $room_name = "HotPepper";
      //ルーム別背景色の出し分け
      if(strpos($room_name,"C") !== false){
        $tr_class = "c_room";
      }elseif(strpos($room_name,"他") !== false){
        $tr_class = "o_room";
      }else{
        $tr_class = "";
      }
      if($color_switch == 'color_on'){
        if(strpos($room_name,"VIP") !== false){
            $tr_class = "vip_room";
          }elseif(strpos($room_name,"パック") !== false){
            $tr_class = "p_room";
          }elseif(strpos($room_name,"月額") !== false){
            $tr_class = "m_room";
          }
      }
      $echo = '<tr class="'.$tr_class.'">';
      $echo .= '<td>'.$room_name.'</td>';
      $j = 1;//縦ライン

      if(is_array($data[$key])){
        $last_position = 1;
        foreach($data[$key] as $sub_key => $sub_val){
          $space = $sub_val['hope_time']- $last_position;
          //前、中、空き室表示
          for ($k=1; $k <= $space; $k++) {
            if( array_key_exists($hope_date,$gClosed) && in_array($shop_id,$gClosed[$hope_date]) || $open_date &&  $strtotime_open_date>$strtotime_hope_date || $close_date && $strtotime_close_date < $strtotime_hope_date){
              $echo .= '<td class="fill_black b_left" ></td>';
            }else{
              $echo .= '<td '.($j%2 ? 'class="b_left"' : '').' ></td>';
            }
            $j++;
          }
          //月額制かパックで表示色の変更
          if($sub_val['type']==="2"){//トリートメントの場合
            if($sub_val['course']==="1"){//月額
                // $echo .= '<td '.'class="fill_month2'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
              if($sub_val['part']==="1"){//上半身
                $echo .= '<td '.'class="fill_month1'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
              }elseif($sub_val['part']==="2"){//下半身
                $echo .= '<td '.'class="fill_month2'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
              }else{
                $echo .= '<td '.'class="fill_pack'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
              }
            }elseif($sub_val['course']==="0" || $sub_val['ctype']<4){//パック
              $echo .= '<td '.'class="fill_pack'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
            }else{//パック・月額以外
              $echo .= '<td '.'class="fill_ng'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
            }
          }else{//トリートメント以外
            $echo .= '<td '.'class="fill'.($j%2 ? ' b_left' : '').'" colspan="'.$sub_val['length'].'" ></td>';
          }
          $last_position = $sub_val['hope_time'] + $sub_val['length'];
          $j += $sub_val['length'];
        }
        //後ろ空き室表示
        for ($k=1; $k <= (21-$last_position); $k++) {
          if(array_key_exists($hope_date,$gParty) && in_array($shop_id,$gParty[$hope_date]) && $j>=$gPartyTime){ //第一木曜日 19:00以後達成会
            $echo .= '<td class="fill_black b_left" ></td>';
          }elseif( array_key_exists($hope_date,$gClosed) && in_array($shop_id,$gClosed[$hope_date]) || $open_date &&  $strtotime_open_date>$strtotime_hope_date || $close_date && $strtotime_close_date < $strtotime_hope_date){
              $echo .= '<td class="fill_black b_left" ></td>';
          }elseif( ($hope_date >= $gEndingDate ) && in_array($shop_id,$gEndingShop) && $j>=$gEndingTime){
                  $echo .= '<td class="fill_black b_left" ></td>';
          }else{
            $echo .= '<td '.($j%2 ? 'class="b_left"' : '').'></td>';
          }
          $j++;
        }
      }else{
        //全行空き
        for ($k=1; $k <= 20; $k++) {
          if(array_key_exists($hope_date,$gParty) && in_array($shop_id,$gParty[$hope_date]) && $k>=$gPartyTime){ //第一木曜日 19:00以後達成会
            $echo .= '<td class="fill_black b_left" ></td>';
          }elseif( array_key_exists($hope_date,$gClosed) && in_array($shop_id,$gClosed[$hope_date]) || $open_date &&  $strtotime_open_date>$strtotime_hope_date || $close_date && $strtotime_close_date < $strtotime_hope_date){
            $echo .= '<td class="fill_black b_left" ></td>';
          }elseif( ($hope_date >= $gEndingDate ) && in_array($shop_id,$gEndingShop) && $k>=$gEndingTime){
                $echo .= '<td class="fill_black b_left" ></td>';
          }else{
            $echo .= '<td '.($j%2 ? 'class="b_left"' : '').'></td>';
          }
          $j++;
        }
      }
      $echo .= '</tr>';
      $echos .= $echo;
      $i++;
    }
    $echos .= '</table>';
    if(!$customer_id){// 顧客IDなし、店舗の予約表へリンク
      $echos .= '</a>';
    }
    $echos .= '</div></div>';
    echo $echos;
  }
}

// 予約表へのリンクを作成する
function reserve_link($shop_id,$hope_date){
  $target_data ='../main/index.php?shop_id='.$shop_id.'&hope_date='.$hope_date;
  $echos .= '<a href="'.$target_data.'" target="_blank">';
  $echos .= '<span class="f_b_16">この日の予約表を確認する</span>';
  $echos .= '</a>';
  echo $echos;
}

// 選択可能部屋数のみ取得 reservation/edit.php用
function select_option($room_list_name){
  foreach($room_list_name as $key => $room_name){
    //ルーム名の取得
    //if($shop_id==9 && $key==35) $room_name = "HotPepper";
    //ルーム別背景色の出し分け
    // if(strpos($room_name,"カウンセリング") !== false){
    //   $tr_class = "c_room";
    //   }elseif(strpos($room_name,"VIP") !== false){
    //     $tr_class = "vip_room";
    //   }elseif(strpos($room_name,"トリートメント") !== false){
    //     $tr_class = "p_room";
      // }elseif(strpos($room_name,"月額") !== false){
      //   $tr_class = "m_room";
    //   }elseif(strpos($room_name,"他") !== false){
    //     $tr_class = "o_room";
    //   }else{
    //     $tr_class = "";
    // }
    $echo = '<option class="'.$tr_class.'" value="'.$key.'">'.$room_name.'</option>';
    $echos .= $echo;
  }
  echo $echos;
}

//表示する
// reservation/edit.php ルームプルダウン表示用
if($_POST['action'] == 'edit'){
  $shop_id = $_POST['shop_id'];
  $shop = Get_Table_Row("shop"," WHERE id = '".$shop_id."'");
  $room_lists = room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName);
  select_option($room_lists);
}
// main/vacant_room.php
if($_POST['action'] == 'rooms'){
  if($_POST['choose']=='choose'){ // 期間指定検索
    $shop_id = $_POST['shop_id'];
    $shop = Get_Table_Row("shop"," WHERE id = '".$shop_id."'");
    $start_date = $_POST['hope_date1']; // 日付け選択左欄
    $start_date = new DateTime($start_date);
    $end_date = $_POST['hope_date2'];
    $end_date = new DateTime($end_date); // 日付け選択右欄
    $count_days = ($start_date -> diff($end_date) -> format('%a')); // 日付けの日数差を取得
    if($_POST['shop_id'] === ''){
      echo "店舗を選択してください";
    }else if($start_date > $end_date){
      echo "期間は過去日～未来日の形に指定してください。未来日～過去日は指定できません。";
    }else if($count_days >= 31){
      echo "日付け選択期間は最長1ヶ月間です";
    }else{
      //選択した店舗の予約状況を表示
      $i = 0;
      $reference_date = $start_date ->modify( '-1 days');
      while ($i <= $count_days) {
        $hope_date = $reference_date ->modify( '+1days') ->format('Y-m-d');
        $room_lists = room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName,'on');
        $data = search($shop_id,$shop,$hope_date);
        availability($shop,$room_lists,$data,$shop_id,$hope_date,$shop['open_date'],$shop['close_date'],$gWday,$gClosed,$gParty,$gPartyTime,$gEndingDate,$gEndingShop,$gEndingTime,'table_on','','color_on');
        $i++;
      }
    }
  }else if($_POST['choose']=='period'){ // reservation/edit.phpに表示する
    $shop_id = $_POST['shop_id'];
    $shop = Get_Table_Row("shop"," WHERE id = '".$shop_id."'");
    if($shop_id === ''){
      echo "店舗を選択してください";
    }else{
      $room_lists = room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName,'on');
      $data = search($shop_id,$shop,$hope_date);
      availability($shop,$room_lists,$data,$shop_id,$hope_date,$shop['open_date'],$shop['close_date'],$gWday,$gClosed,$gParty,$gPartyTime,$gEndingDate,$gEndingShop,$gEndingTime,'',$customer_id,'');
     reserve_link($shop_id,$hope_date);
    }
  }else{ // エリア別予約情報取得
    $shop_area = $_POST['shop_area'];
    $dSql2 = "SELECT id,pref,name_kana FROM shop WHERE area = ".$shop_area." AND del_flg = 0 AND status= 2 AND (close_date is null OR close_date ='' OR close_date >= '" . $hope_date . "') order by ".$gShops_priority;
    $dRtn2 = $GLOBALS['mysqldb']->query( $dSql2 ) or die('query error'.$GLOBALS['mysqldb']->error);
    $shop_count=0;
    while ( $result1 = $dRtn2->fetch_assoc() ) {
      $shops[$shop_count] = $result1['id'];
      $shop_count++;
    }
    //エリアごと全店舗の予約状況を表示
    $i = 0;
    while ( $i< $shop_count) {
      $shop_id = $shops[$i];
      $shop = Get_Table_Row("shop"," WHERE id = '".$shop_id."'");
      $room_lists = room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName,'on');
      $data = search($shop_id,$shop,$hope_date,'on');
      availability($shop,$room_lists,$data,$shop_id,$hope_date,$shop['open_date'],$shop['close_date'],$gWday,$gClosed,$gParty,$gPartyTime,$gEndingDate,$gEndingShop,$gEndingTime,'table_on','','color_on');
      $i++;
    }
  }
}
?>