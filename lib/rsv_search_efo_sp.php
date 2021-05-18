<?php
// 30分後の予約？フリー？
// 前提：希望店舗と希望日程があり
// デフォルト：店舗順
// $_GET['nittei1']="2014/01/31";
// $_GET['tenpo1'] = "新宿本店";

// 店舗リスト
$shop_sql = mysql_query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
$shop_list[0] = "-";
while ( $result = mysql_fetch_assoc($shop_sql) ) {
  $shop_list[$result['id']] = $result['name'];
  $shop_id_list[$result['name']] = $result['id'];
}

// データ整形
$nittei = array($_GET['nittei1'],$_GET['nittei2'],$_GET['nittei3'],$_GET['nittei4'],$_GET['nittei5'],$_GET['nittei6'],$_GET['nittei7']);
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
                $shop_id_list[$_GET['tenpo40']],
                $shop_id_list[$_GET['tenpo41']],
                $shop_id_list[$_GET['tenpo42']],
                $shop_id_list[$_GET['tenpo43']],
                $shop_id_list[$_GET['tenpo44']],
                $shop_id_list[$_GET['tenpo45']],
                $shop_id_list[$_GET['tenpo46']],
                $shop_id_list[$_GET['tenpo47']],
                $shop_id_list[$_GET['tenpo48']],
                $shop_id_list[$_GET['tenpo49']],
                $shop_id_list[$_GET['tenpo50']],
                $shop_id_list[$_GET['tenpo51']],
                $shop_id_list[$_GET['tenpo52']],
                $shop_id_list[$_GET['tenpo53']],
                $shop_id_list[$_GET['tenpo54']],
                $shop_id_list[$_GET['tenpo55']],
                $shop_id_list[$_GET['tenpo56']],
                $shop_id_list[$_GET['tenpo57']],
                $shop_id_list[$_GET['tenpo58']],
                $shop_id_list[$_GET['tenpo59']],
                $shop_id_list[$_GET['tenpo60']]
                );

// 配列を逆順にソートする
arsort($tennpo); 

// HTMLデータ格納
$table = "reservation";

// 一人：１時間、二人：１時間半
$length = $_GET[nunzu]==1 ? 2 :3;
$rsv_list = array();


// $rsv_list[] = array('html' => '<table id="tbl02" border="0" cellspacing="1" cellpadding="0"><tr><th>日付</th><th>カウンセリング<br>予約時間</th><th>店舗</th><th>予約</th></tr></table>' );

if($_GET['sort']=="time"){
  $rsv_list = getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2,$gEndingShop,$gEndingDate,$gEndingTime);
}else{
  $rsv_list = getTimeListByShop($table,$shop_list,$tennpo,$nittei,$gTime2,$length,$gParty,$gClosed,$gPartyTimeList1,$gPartyTimeList2,$gEndingShop,$gEndingDate,$gEndingTime,$shop_map);
}



// 関数*************************************************************************
function getTimeListByShop($table, $shop_list, $tennpo, $nittei, $gTime2, $length, $gParty, $gClosed, $gPartyTimeList1, $gPartyTimeList2, $gEndingShop, $gEndingDate, $gEndingTime, $shop_map) {
    $is_nittei = false;
    $search_enable_week = 5;	// 5週間後まで検索可能
    $search_count = $_GET['search_count'];
    
	// カレンダーのヘッダ部分に表示する月のテキストを作成
	$start_year  = substr($_GET['nittei1'], 0, 4);
	$end_year    = substr($_GET['nittei7'], 0, 4);
	$start_month = substr($_GET['nittei1'], 5, 2);
	$end_month   = substr($_GET['nittei7'], 5, 2);
	$disp_month_text = $start_year . "年" . $start_month . "月";
	if( $start_year != $end_year ){
		// 年跨ぎのパターン
		$disp_month_text .= " ～ " . $end_year . "年" . $end_month . "月";
	} else if( $start_month != $end_month ){
		// 月跨ぎのパターン
		$disp_month_text .= " ～ " . $end_month . "月";
	}
    // 希望店舗ループ
    foreach($tennpo as $keys => $value) {
        if (!$value) continue;
        $shop = Get_Table_Row("shop", " WHERE del_flg = 0  AND status=2 and id = '".addslashes($value)."'");
        if( isset( $shop['address'] ) ) {
        	// $shop['address']を読みやすい位置で改行する
        	$shop['address'] = ParseAddress($shop['address'], 36);
        }
        if( isset( $shop['access'] ) ) {
        	// $shop['access']を読みやすい位置で改行する
        	$shop['access'] = ParseAccess($shop['access'], 36);
        }
		// 店舗詳細部分作成
		if($search_count == 1){
			// まだオープン前(＝先行予約期間)の店舗かどうかを確認
			$info_message = '';
			if ($shop['open_date'] > date('Y-m-d')){
				$info_message = '<br><span class="shop_select_info">※' . $shop['name'] . 'でのご予約の場合は' . date('m月d日', strtotime($shop['open_date'])) . '以降をご選択下さい。</span>';
			}
			$mapLpPath = '../../img/map_lp/' . $shop_map[$shop['id']];
			$rsv_list[] = array('html' => '
				<div class="contents_block shop_detail_area">
					<div>
					<a onclick="SlideMove(\'slide_info\')" class="slide_block switch_image" data-target="slide_info" data-imgtarget1="down_image" data-imgtarget2="up_image">
					<div class="tit">
						<span class="normal_font6">ご予約店舗</span><br><span class="normal_font7">' . $shop['name'] . '</span>' . $info_message . '
					</div>
					</a>
						<div id="slide_info" hidden>
							<p class="img"><img src="' . $mapLpPath . '" alt=""></p>
							<div class="address">
								<span class="normal_font8">' . $shop['address'] . '</span><br>
								<span class="normal_font7">' . $shop['access'] . '</b></span>
							</div>
						</div>
					</div>
				</div>
			');
		}
		
		// カレンダーの上部品
		$next_index = $search_count+1;
		$prev_index = $search_count-1;
	    $rsv_list[] = array('html' => '
			<div id="page'. $search_count .'" class="datetime_page">
				<div class="pager_area">
					<ul>' . 
						(
							($search_count <= 1) ? 
								('<li class="inline_block gray_box font_gray prev">&#12296; 前週</li>') :
								('<li class="inline_block blue_box paginate prev" onclick="ChangeWeek(\'' . $prev_index . '\')"><a>&#12296; 前週</a></li>')
						)
						. '<li class="year">' . $disp_month_text . '</li>' . 
						(
							($search_count < $search_enable_week) ? 
								('<li class="inline_block blue_box paginate next" onclick="ChangeWeek(\'' . $next_index . '\')"><a>翌週  &#12297;</a></li>') :
								('<li class="inline_block gray_box font_gray next">翌週  &#12297;</li>')
						) .
					'</ul>
				</div>
				<div class="bg_check1">
				<div class="step02_area contents_block calendar_area">
	    ');
	    
		// カレンダーのヘッダ(日付)部分
		$rsv_list[] = array('html' => '
			<div class="contents_block text_center">
				<ul class="inline_block center_div">
					<li class="inline_block normal_font9 font_enable_brown">◎予約可能　</li>
					<li class="inline_block normal_font9 font_gray">－キャンセル待ち</li>
				</ul>
			</div>
			<table class="calender box_margin2">
				<thead>
					<tr>
						<td>日時</td>
						<td class="'.getDateClass($_GET['nittei1']).'">' . getDateYobi3($_GET['nittei1']) . '</td>
						<td class="'.getDateClass($_GET['nittei2']).'">' . getDateYobi3($_GET['nittei2']) . '</td>
						<td class="'.getDateClass($_GET['nittei3']).'">' . getDateYobi3($_GET['nittei3']) . '</td>
						<td class="'.getDateClass($_GET['nittei4']).'">' . getDateYobi3($_GET['nittei4']) . '</td>
						<td class="'.getDateClass($_GET['nittei5']).'">' . getDateYobi3($_GET['nittei5']) . '</td>
						<td class="'.getDateClass($_GET['nittei6']).'">' . getDateYobi3($_GET['nittei6']) . '</td>
						<td class="'.getDateClass($_GET['nittei7']).'">' . getDateYobi3($_GET['nittei7']) . '</td>
					</tr>
				</thead>
			</table>
	    ');

		// 検索方向が縦方向のため、trを並行して作成する
		$test = [];
		$tr_array = [];
		$tmp_time_list = array(
                1=>"11:00", 3=>"12:00", 5=>"13:00", 7=>"14:00", 9=>"15:00",
                11=>"16:00", 13=>"17:00", 15=>"18:00", 17=>"19:00", 19=>"20:00"
        ); // 検索する数は後の$time_listに従うが、枠を作っておく必要があるので固定数でtmp_time_listを作成する
        // 各trを格納するtr_arrayの初期化、左1列に時間が入る
        foreach($tmp_time_list as $keys2 => $value2){
        	if( !isset($tr_array[$key2]) ){
        		// カレンダーの左側縦一列(hh:mm)を作成
        		$tr_array[$keys2] = '<tr><td>' . $value2 . '</td>';
        	}
        }
        // 希望日程ループ
        $gTimeToday = $gTime2;
        foreach($nittei as $keys1 => $value1) {

            if (!$value1) continue;
            $hope_date = str_replace("/", "-", $value1);

            if ($hope_date == "2014-02-28") $time_list = array(3=>"12:00", 5=>"13:00");
            // 達成会：19時後予約禁止
            elseif(array_key_exists($hope_date, $gParty) && in_array($value, $gParty[$hope_date])){
                if ($length == 2) {
                    $time_list = $gPartyTimeList1;
                } else {
                    $time_list = $gPartyTimeList12;
                }
            }
			// 地方店舗の営業終了時間が20時 add by ka 20151225
			else $time_list = array(
                1=>"11:00", 3=>"12:00", 5=>"13:00", 7=>"14:00", 9=>"15:00",
                11=>"16:00", 13=>"17:00", 15=>"18:00", 17=>"19:00", 19=>"20:00"
            );
            
            // オープン前と非営業日に予約不可
            if (array_key_exists($hope_date, $gClosed) && in_array($shop['id'], $gClosed[$hope_date]) || $shop['open_date'] && strtotime($shop['open_date']) > strtotime($hope_date)) {
            	foreach($time_list as $keys2 => $value2){
            		$tr_array[$keys2] .= '<td class="font_gray bg_gray">－</td>';
            	}
                continue;
            }

            $room_list = array();
            for ($i = 1; $i <= $shop['counseling_rooms']; $i++)   $room_list[] = "1".$i;
            for ($i = 1; $i <= $shop['vip_rooms']; $i++)          $room_list[] = "2".$i;


            // 営業時間表ループ
            $count = 0;
            foreach($tmp_time_list as $keys2 => $value2){
				if( !in_array($value2, $time_list) ){
					// $time_listが予約可能な時間になるので、属さない時間は全て予約不可
					$tr_array[$keys2] .= '<td class="font_gray bg_gray">－</td>';
					continue;
				}
				
	            // 当時前は空き確認せず予約不可扱い
	            if ($hope_date == date("Y-m-d")) {
	            	// 希望日が今日
	            	if($value2 < date("H:i")){
	            		// 検索時間が今の時刻より前
	                    $tr_array[$keys2] .= '<td class="font_gray bg_gray">－</td>';
	                    continue;
	            	}
	            }
                // 希望店舗ルームループ
                $is_empty = false;
                
                // length=3(1時間半の場合)の場合の カンセリング1 19:30、 カンセリング1以外　21:00を超えない対処 
                if (!(in_array($value2, array("18:30", "20:00")) && ($length > 2))) {
                    $sql = " WHERE  type<>3 and type<>14 and del_flg=0 and hope_date='".addslashes($hope_date)."' AND shop_id=".$value." AND room_id IN (".implode(',', $room_list).")";
                    $sql.= " AND ( (hope_time<".$keys2." AND hope_time+length>".$keys2.")"; // 予約開始時間と比較(重なりあり)
                    $sql.= " OR (hope_time<".($keys2 + $length)." AND hope_time+length>".($keys2 + $length).")"; // 予約終了時間と比較(重なりあり)
                    $sql.= " OR (hope_time>=".$keys2." AND hope_time+length<=".($keys2 + $length). ") )"; // 中にあり
                    $full_room_list = Get_Table_Array($table, "room_id", $sql); // $full_room_listは「予約不可の部屋」
                    foreach($room_list as $tmp_room){
                    	if( !in_array($tmp_room, $full_room_list) ){
                    		// 空きroomあり
                    		$is_empty = true;
                    		break;
                    	}
                    }
                }
                
                // 終了時間＞２１時
                if (($keys2 + $length) > 21) $is_empty = false;

                if ($is_empty) {
                    $tr_array[$keys2] .= '<td class="double_circle"><a onClick="dateTimeSubmit(\''.$shop['id'].'.'.$shop['name'].'.'.$value1.'.'.$value2.'\');"><u>◎</u></a></td>';
                    // 別の日程に指定
                    $is_nittei = true;
                } else {
                	// 空きがないとき
                    $tr_array[$keys2] .= '<td class="font_gray bg_gray">－</td>';
                }
            }
            
        }
        $rsv_list[] = array('html' => '<table class="calender box_margin2"><tbody>');
        foreach($tmp_time_list as $keys2 => $value2){
        	if( !isset($tr_array[$key2]) ){
        		// </tr>を付与
        		$rsv_list[] = array('html' => $tr_array[$keys2].'</tr>');
        	}
        }
        $rsv_list[] = array('html' => '</tbody></table>');
        
		// カレンダーの下部品
	    $rsv_list[] = array('html' => '
				</div>
				</div>
				<div class="pager_area">
					<ul>' . 
						(
							($search_count <= 1) ? 
								('<li class="inline_block gray_box font_gray prev">&#12296; 前週</li>') :
								('<li class="inline_block blue_box paginate prev" onclick="ChangeWeek(\'' . $prev_index . '\')"><a>&#12296; 前週</a></li>')
						)
						. '<li class="year"></li>' . 
						(
							($search_count < $search_enable_week) ? 
								('<li class="inline_block blue_box paginate next" onclick="ChangeWeek(\'' . $next_index . '\')"><a>翌週  &#12297;</a></li>') :
								('<li class="inline_block gray_box font_gray next">翌週  &#12297;</li>')
						) .
					'</ul>
				</div>
			</div>
	    ');
	    // 別の日程に指定
	    if (!$is_nittei && (
            $_GET['tenpo1'] || $_GET['tenpo2'] || $_GET['tenpo3'] || $_GET['tenpo4'] || $_GET['tenpo5'] ||
	        $_GET['tenpo6'] || $_GET['tenpo7'] || $_GET['tenpo8'] || $_GET['tenpo9'] || $_GET['tenpo10'] ||
	        $_GET['tenpo11'] || $_GET['tenpo12'] || $_GET['tenpo13'] || $_GET['tenpo14'] || $_GET['tenpo15'] ||
	        $_GET['tenpo16'] || $_GET['tenpo17'] || $_GET['tenpo18'] || $_GET['tenpo19'] || $_GET['tenpo20'] ||
	        $_GET['tenpo21'] || $_GET['tenpo22'] || $_GET['tenpo23'] || $_GET['tenpo24'] || $_GET['tenpo25'] ||
	        $_GET['tenpo26'] || $_GET['tenpo27'] || $_GET['tenpo28'] || $_GET['tenpo29'] || $_GET['tenpo30'] ||
	        $_GET['tenpo31'] || $_GET['tenpo32'] || $_GET['tenpo33'] || $_GET['tenpo34'] || $_GET['tenpo35'] ||
	        $_GET['tenpo36'] || $_GET['tenpo37'] || $_GET['tenpo38'] || $_GET['tenpo39'] || $_GET['tenpo40'] ||
            $_GET['tenpo41'] || $_GET['tenpo42'] || $_GET['tenpo44'] || $_GET['tenpo44'] || $_GET['tenpo45'] ||
            $_GET['tenpo46'] || $_GET['tenpo47'] || $_GET['tenpo48'] || $_GET['tenpo49'] || $_GET['tenpo50'] ||
            $_GET['tenpo51'] || $_GET['tenpo52'] || $_GET['tenpo53'] || $_GET['tenpo54'] || $_GET['tenpo55'] ||
            $_GET['tenpo56'] || $_GET['tenpo57'] || $_GET['tenpo58'] || $_GET['tenpo59'] || $_GET['tenpo60'])
	        && ($_GET['nittei1'] || $_GET['nittei2'] || $_GET['nittei5'] || $_GET['nittei4'] || $_GET['nittei5'] || $_GET['nittei6'] || $_GET['nittei7'])) {
	        // 別日の検索を行うため、空きが無くても表示する
//			$rsv_list = array();
//			$rsv_list[] = array('html' => '<div class="contents_block shop_detail_area"><div class="tit"><span class="normal_font6">別の日程をご選択ください。</span></div></div>');
	    }
	}
    
    return $rsv_list;
}

function getTimeListByTime($table,$shop_list,$tennpo,$nittei,$gTime2,$gTime3,$length,$gParty,$gClosed,$gPartyTime,$gEndingShop,$gEndingDate,$gEndingTime){
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
        // 終了時間＞２１時
        if(($keys2+$length)>21)$is_empty = false;
        if($is_empty) {
            $rsv_list[] = array(
              'html' => '<table id="tbl02" border="0" cellspacing="0.5" cellpadding="0"><tr>
                 <td class="date"><p class="'.getDateClass($value1).'">'.getDateYobi2($value1).'</p></td>
                     <td class="time"><p>'.$value2.'</p></td>
                     <td class="salon"><p>'.$shop_list[$value].'</p></td>
                     <td><p><a href="#step3" onclick="return result(\''.$shop_list[$value].'\',\''.$value1.'\',\''.$value2.'\',\''.$value.'\');" class="yoyakubi"><img src="../img/counseling/btn_select_on.gif" width="100" height="30" alt="選択"></a></p></td>
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
function getDateYobi3($date){
  $week = array("日", "月", "火", "水", "木", "金", "土");
  $time = strtotime($date);
  $w = date("w", $time);
 
  return date("m/d", $time)."<br>(".$week[$w].")";
}

function getDateClass($date){
  $w = date("w",  strtotime($date));
  if($w==0) return "font_holiday_red"; 
  if($w==6) return "font_holiday_blue"; 
  else return false;
}

header('Content-type: application/json; charset=UTF-8');
echo json_encode($rsv_list);
?>
