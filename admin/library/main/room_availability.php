<?php
// 新宿本店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==1 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==1 && $_POST['hope_date']<="2014-09-24") ? 5 : $counseling_rooms;


// 池袋東口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==2 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==2 && $_POST['hope_date']<="2014-10-09") ? 5 : $counseling_rooms;

// 渋谷道玄坂店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==3 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==3 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==3 && $_POST['hope_date']<="2014-10-26") ? 5 : $counseling_rooms;

// 横浜西口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==4 && $_POST['hope_date']<="2016-01-05") ? 5 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==4 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 大宮東口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==5 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==5 && $_POST['hope_date']<="2015-05-25") ? 4 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==5 && $_POST['hope_date']<="2014-07-30") ? 5 : $counseling_rooms;

// 新宿南口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==6 && $_POST['hope_date']<="2014-10-26") ? 5 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==6 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 池袋サンシャイン通り口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==7 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==7 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 銀座店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==8 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 名古屋栄店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==9 && $_POST['hope_date']<="2016-08-04") ? 3 : $counseling_rooms;

// 新潟万代店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==10 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;

// 千葉店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==13 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 町田店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==14 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 渋谷宮益坂店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==15 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==15 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;

// 心斎橋駅前店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==16 && $_POST['hope_date']<="2016-08-31") ? 2 : $counseling_rooms;

// 神戸元町店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==17 && $_POST['hope_date']<="2016-08-04") ? 3 : $counseling_rooms;

// 宇都宮東武駅前店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==18 && $_POST['hope_date']<="2016-02-01") ? 2 : $counseling_rooms;

// 秋葉原店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==20 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;

// 横浜駅前店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==22 && $_POST['hope_date']<="2016-08-04") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==22 && $_POST['hope_date']<="2016-01-05") ? 4 : $counseling_rooms;

// 熊本下通店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==23 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;

// 仙台東映プラザ店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==26 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// なんば店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==27 && $_POST['hope_date']<="2016-08-27") ? 2 : $counseling_rooms;

// 吉祥寺店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==28 && $_POST['hope_date']<="2016-05-13") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==28 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 静岡Denbill店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==29 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;

// あべの店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==30 && $_POST['hope_date']<="2016-08-27") ? 2 : $counseling_rooms;

// 川崎店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==31 && $_POST['hope_date']<="2016-04-30") ? 3 : $counseling_rooms;
$counseling_rooms = ($_POST['shop_id']==31 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 錦糸町店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==32 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 津田沼北口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==35 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 柏店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==36 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 藤沢南口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==37 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 名古屋駅前店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==38 && $_POST['hope_date']<="2016-08-04") ? 3 : $counseling_rooms;

// 町田中央通店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==39 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 五反田店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==40 && $_POST['hope_date']<="2016-08-04") ? 2 : $counseling_rooms;

// 新宿西口店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==41 && $_POST['hope_date']<="2016-09-30") ? 2 : $counseling_rooms;

// 立川北口駅前店カウンセリングルーム
$counseling_rooms = ($_POST['shop_id']==42 && $_POST['hope_date']<="2016-07-31") ? 5 : $counseling_rooms;




// 横浜西口店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==4 && $_POST['hope_date']<="2016-04-30") ? 6 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==4 && $_POST['hope_date']<="2016-04-30") ? 3 : $shop['pack_rooms'];

// 新宿南口店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==6 && $_POST['hope_date']<="2016-08-04") ? 5 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==6 && $_POST['hope_date']<="2016-08-04") ? 4 : $shop['pack_rooms'];

// 銀座店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==8 && $_POST['hope_date']<="2016-04-30") ? 6 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==8 && $_POST['hope_date']<="2016-04-30") ? 3 : $shop['pack_rooms'];

// 名古屋栄店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==9 && $_POST['hope_date']<="2016-01-31") ? 7 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==9 && $_POST['hope_date']<="2016-01-31") ? 4 : $shop['pack_rooms'];

// 新潟万代店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==10 && $_POST['hope_date']<="2016-08-04") ? 5 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==10 && $_POST['hope_date']<="2016-08-04") ? 4 : $shop['pack_rooms'];

// 町田店トリートメントルーム
$ninety_time_rooms =    ($_POST['shop_id']==14 && $_POST['hope_date']<="2016-08-04") ? 5 : $ninety_time_rooms;

// 熊本下通店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==23 && $_POST['hope_date']<="2016-08-04") ? 3 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==23 && $_POST['hope_date']<="2016-08-04") ? 2 : $shop['pack_rooms'];

// 吉祥寺店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==28 && $_POST['hope_date']<="2016-08-04") ? 4 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==28 && $_POST['hope_date']<="2016-08-04") ? 3 : $shop['pack_rooms'];

// 北千住店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==33 && $_POST['hope_date']<="2016-08-04") ? 4 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==33 && $_POST['hope_date']<="2016-08-04") ? 3 : $shop['pack_rooms'];

// 名古屋駅前店トリートメントルーム
$ninety_time_rooms = ($_POST['shop_id']==38 && $_POST['hope_date']<="2016-04-30") ? 5 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==38 && $_POST['hope_date']<="2016-04-30") ? 3 : $shop['pack_rooms'];




// なんば店トリートメントルーム
$ninety_time_rooms 		= ($_POST['shop_id']==27 && $_POST['hope_date']<="2016-08-31") ? 3 : $ninety_time_rooms;

// あべの店トリートメントルーム
$ninety_time_rooms 		= ($_POST['shop_id']==30 && $_POST['hope_date']<="2016-08-31") ? 3 : $ninety_time_rooms;
$shop['pack_rooms'] = ($_POST['shop_id']==30 && $_POST['hope_date']<="2016-08-31") ? 2 : $shop['pack_rooms'];

?>