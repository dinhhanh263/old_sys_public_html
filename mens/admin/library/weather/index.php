<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

if($authority['id']=="5"){
    header("Location: ../adcode/");
    exit();
}

$_POST = $_REQUEST;

$table = "reservation";

$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($_POST['shop_id'])."'");

//店舗リスト
$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト

$course_list= getDatalistMens("course");


//var_dump($data);


$year = ($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
$month = ($_REQUEST['month']) ? $_REQUEST['month'] : date("n");
$day = ($_REQUEST['day']) ? $_REQUEST['day'] : date("j");

$flg= ($_REQUEST['flg']) ? $_REQUEST['flg'] : "";

$html = calendar($year, $month, $day,$flg);

function calendar($year, $month, $day,$flg="") {


  //月末
  $l_day = date("j", mktime(0, 0, 0, $month + 1, 0, $year));
  
  //初期出力
  
  $premonth = ($month==1) ? 12 : $month-1;
  $afmonth = ($month==12) ? 1 : $month+1;
  
  $preyear = ($month==1 && $flg="pre") ?  $year-1 : $year;
  $afyear = ($month==12 && $flg="af") ? $year+1 : $year;
  
  $tmp = <<<EOM
		<div align="center">
		<table width="100%" cellspacing="1" cellpadding="5" border="1" class="product-table">
			<tr id="room" height="30">
				<th colspan="7" class="table-header-repeat line-center">
					<a href="./?year=$preyear&month=$premonth&day=$day&flg=pre">&lt;&lt;&nbsp;{$premonth}</a>
					<a href="">{$year}/{$month}</a>
					<a href="./?year=$afyear&month=$afmonth&day=$day&flg=af">{$afmonth}&nbsp;&gt;&gt;</a>
				</th>
			</tr>
			<tr bgcolor="#e9eefd" height="30">
					<td align="center" width="14%" bgcolor="#fde9f2" >日</th>
					<td align="center" width="14%">月</th>
					<td align="center" width="14%">火</th>
					<td align="center" width="14%">水</th>
					<td align="center" width="14%">木</th>
					<td align="center" width="14%">金</th>
					<td align="center" width="14%" bgcolor="#fde9f2" >土</th>
				</tr>
EOM;
  //月末分繰り返す
  for ($i = 1; $i < $l_day + 1;$i++) {
    //曜日の取得
    $week = date("w", mktime(0, 0, 0, $month, $i, $year));
    //曜日が日曜日の場合
    if ($week == 0) {
      $tmp .= "  <tr>\n";
    }
    //1日の場合
    if ($i == 1) {
      $tmp .= str_repeat("    <td>&nbsp;</td>\n", $week);
    }

	if ($i == $day) {
	  //指定日付の場合
      $tmp .= "    <td align='center' height='100'><a href='./?year=$year&month=$month&day=$i'>{$i}</a><br><img src='http://i.yimg.jp/images/weather/general/forecast/pinpoint/size40/prain_light.gif' boder='0' alt='晴れ'><br>21/15</td>\n";
    } else {
      //現在の日付ではない場合
      $tmp .= "    <td align='center' height='100'><a href='./?year=$year&month=$month&day=$i'>{$i}</a><br><img src='http://i.yimg.jp/images/weather/general/forecast/pinpoint/size40/psun.gif' boder='0' alt='晴れ'><br>21/15</td>\n";
    }
    //月末の場合
    if ($i == $l_day) {
      $tmp .= str_repeat("    <td>&nbsp;</td>\n", 6 - $week);
    }
    //土曜日の場合
    if($week == 6) {
      $tmp .= "  </tr>\n";
    }
  }
  $tmp .= "</table>\n";

  $selected = ($hit) ? "selected" : "";

  return $tmp;
  
}
?>