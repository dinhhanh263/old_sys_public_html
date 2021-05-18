<?php

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
require_once LIB_DIR . 'KagoyaSendMail.php';


//NGlリスト。type:1.名前2.名前かな3.メール4.電話
$ng_sql = $pdo->query( "select * from ngword WHERE status=0 order by id" ) or kireimo_die('query error'.mysql_error());
if ($ng_sql) {
    $i=1;
    while ( $result = $pdo->fetch() ) {
        $ng_list[$result['type']][$i] = $result['name'];
        $i++;
    }
}

$gItems  = array(
    'hope_date'     => array( "name" =>'ご希望日時',             "exist_check" => true,  "item_name" => true,    "confirm_item" => true,     "enter_check" => true,     "additional" => ""),
    'hope_time'     => array( "name" =>'ご希望時間',             "exist_check" => true,  "item_name" => false,   "confirm_item" => true,     "enter_check" => true,      "additional" => "～(所要時間約60～90分)"),
    'shop'          => array( "name" =>'ご予約の店舗',            "exist_check" => true,  "item_name" => true,    "confirm_item" => true,     "enter_check" => true,      "before" => ""),
    );
$gActionFlag = array(
    'change'    => 1,
    '11'        => 11
);
//var_dump($gActionFlag[$_SESSION['ACT']]);exit;
#----------------------------------------------------------------------------------------------------------------------#
# メール送信                                                                                                             #
#----------------------------------------------------------------------------------------------------------------------#
$_POST['shop_id'] = !empty($_POST['shop_id']) ? $_POST['shop_id'] : 1;
$_POST['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
$_POST['mode'] = !empty($_POST['mode']) ? $_POST['mode'] : '';

// 不正文字の除外
$_POST['name'] = str_replace("", "", $_POST['name'] );
$_POST['name'] = str_replace("", "", $_POST['name'] );
//E72C
$_POST['name'] = str_replace("", "", $_POST['name'] );
//E68D
$_POST['name'] = str_replace("", "", $_POST['name'] );


if( $_POST['shop_id'] ) {
    $pdo->prepare('SELECT * FROM shop WHERE del_flg = 0 AND status = 2 and id = ?');
    $pdo->bindParam($_POST['shop_id']);
    $pdo->execute();
    $shop = $pdo->fetch();
}

// 店舗別注意文言追加　2017/08/02 add by shimada
$attention_message ="";
// 川崎店
if($shop['id']==31){
    $attention_message =
"・川崎店は日曜・祝日に限り、正面入り口が19時で閉鎖されます。
19時以降のご来店はスタッフがお迎えに参りますので、正面入り口の左側へ回っていただき、裏口に到着いたしましたら、一度コールセンターにお電話をお願いいたします。";

// 錦糸町店
} else if($shop['id']==32){
    $attention_message =
"・錦糸町店は正面入り口が20時で閉鎖されます。
20時以降のご来店はホームページで裏口への行き方をご確認いただき、裏口に到着いたしましたら、一度コールセンターへお電話をお願いいたします。";

// それ以外(改行だけ入れる)
} else {
    $attention_message ="";
}

if($_POST['mode']=="send"){
    foreach( $gItems as $key => $value ){
        $_POST[$key] = preg_replace('/＼/','ー',$_POST[$key]);
        if($value['type']!="checkbox") $_POST[$key] = htmlspecialchars($_POST[$key]);
        if (get_magic_quotes_gpc())  $_POST[$key] = stripslashes($_POST[$key]);
        $body = "";
        //お申込み内容
        if($value['item_name'])     $body .= "【".$value['name']."】\r\n"; // 項目名表示
        if($value['param'])         $body .= $value['param'][$_POST[$key]];
        elseif($value['type']=="checkbox" && is_array($_POST[$key]))
                                    $body .= implode(",",$_POST[$key]); // 複数選択結合
        else                        $body .= $value['before'].$_POST[$key];
                                    $body .= $value['additional']; // "～"、"-"、"歳"等を付加

        if($value['enter_check'])   $body .= "\r\n\r\n";

        //簡易文、社内確認用
        if($value['confirm_item']) $mail_content1 .= $body;

        if($key=="shop"){
            $for_mail_shop_name_list = array("新宿","池袋","渋谷","横浜","町田","名古屋","梅田");
            str_replace($for_mail_shop_name_list, $for_mail_shop_name_list, $_POST[$key],$count);
            if($count !== 0){
                $body .=  "※近隣に別店舗がございます。ご来店の際はお間違いの無いようご注意ください。"."\r\n\r\n";
            }
            if($messange) $body .=  "※近隣に別店舗がございます。ご来店の際はお間違いのないようご注意ください。"."\r\n\r\n";
            $body .= $home_url."img/map_lp/".$shop_map[$shop['id']]."\r\n\r\n";
            $body .= "店舗までの詳しいご案内はこちら↓"."\r\n";
            $body .= $home_url."img/map_lp/".$shop_map2[$shop['id']]."\r\n";
        }

            $body .= $address = $gPref[$shop['pref']].$shop['address']."\r\n";
            // $body .= $home_url."img/map_lp/".$shop_map[$shop['id']]."\r\n\r\n";
            // $body .= "店舗までの詳しいご案内はこちら↓"."\r\n";
            $body .= $home_url."saloninfo/".$shop['url'].".html#shop_flow\r\n";
        }

        $mail_content .= $body;

    }
    // add name_ka,tel by ka 2017/12/04
    if( $_SESSION['ACT'] || !( array_search($_POST['name'], $ng_list[1]) || array_search($_POST['name_kana'], $ng_list[2]) || array_search($_POST['mail'], $ng_list[3]) || array_search($_POST['tel'], $ng_list[4]) ) ){

        //空き確認
        $table = "reservation";
        $hope_time_hhmm = $_POST['hope_time'];
        $_POST['hope_time'] = array_search($_POST['hope_time'], $gTime2);
        $_POST['length'] = ($_POST['persons']>1) ? 3 : 2;

        $room_list = array();
        for ($i = 1; $i <= $shop['counseling_rooms'] ; $i++) $room_list[] = "1".$i;
        for ($i = 1; $i <= $shop['vip_rooms']; $i++)    $room_list[] = "2".$i;

        $is_empty = false;
        $is_timeover =false;
        $errmsg['hope_date_time'] = array();

        // 予約時に、予約時間を超えてしまったかを確認
        if($_POST['hope_date'] && $_POST['hope_time'] ){
            $hope_date_time = str_replace("/","-",$_POST['hope_date'])." ".$gTime2[$_POST['hope_time']];
            if($hope_date_time < date('Y-m-d H:i')){
                $errmsg['hope_date_time'] = "<span class='error'>申し訳ございません。ご予約時間の時間を過ぎているため、ご予約は行えません。</span><br>";
            }
        }

        // 店舗の希望日の予約データをすべて取得
        $hope_date = new DateTime($_POST['hope_date']);
        $sql = 'SELECT id, room_id, hope_time, length FROM reservation WHERE type <> 3 and type <> 14 and del_flg = 0 ';
        $sql .= ' AND hope_date = ? AND shop_id = ?';
        $pdo->prepare($sql);
        $pdo->bindParam([
            $hope_date->format('Y-m-d'),
            $_POST['shop_id'],
        ]);
        $pdo->execute();
        $results = $pdo->fetchAll();
        
        if (empty($results)) {
            // 店舗の希望時間に予約が一つもない→空きあり
            $is_empty = true;
            $_POST['room_id'] = $room_list[0];
        } else {
            // 店舗の希望時間に予約がいくつかある→空いている部屋を検索
            
            // 部屋リストをすべて空きありにセット
            $vacancies = [];
            foreach ($room_list as $room) {
                $vacancies[$room] = 1;

            $gMsg .= '<input class="back" type="submit" value="選びなおす" /> ';
            $gMsg .= '</form><br />';
        }else{

            //顧客新規--------------------------------------------------------------------------------------------------------------------
            $_POST['password'] = generateID(6,'small');
            $_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
            $_POST['mo_agent'] = $mo_agent;

            // 同セッションあったら、adcodeを登録しない　edit by ka 20170602
            if(!Get_Table_Col("customer","id"," WHERE del_flg=0 AND session_id ='".session_id()."' " )){
                $_POST['adcode'] = $_SESSION['AD_CODE'];

            }

            // 希望開始時間、希望終了時間
            $hopeTimeStart = new DateTime($hope_date->format('Y-m-d') . ' ' . $hope_time_hhmm);
            $hopeTimeEnd = new DateTime($hope_date->format('Y-m-d') . ' ' . $hope_time_hhmm);
            
            $length = empty($_POST['length']) ? 2 : $_POST['length'];
            
            $hopeTimeEnd->modify('+' . $length . ' hours');
            $hope_time_start = $hopeTimeStart->format('Y-m-d H:i');
            $hope_time_end = $hopeTimeStart->format('Y-m-d H:i');
            
            // 予約データで空室を除外
            foreach ($results as $item) {
                $reserveTimeStart = new DateTime($hope_date->format('Y-m-d') . $gTime2[$item['hope_time']]);
                $reserveTimeEnd = new DateTime($hope_date->format('Y-m-d') . $gTime2[$item['hope_time']]);
                $reserveTimeEnd->modify('+' . $item['length'] . ' hours');
                
                $reserve_time_start = $reserveTimeStart->format('Y-m-d H:i');
                $reserve_time_end = $reserveTimeEnd->format('Y-m-d H:i');
                
                if ($reserve_time_start < $hope_time_start && $hope_time_start < $reserve_time_end) {
                    $vacancies[$item['room_id']] = 0;
                } elseif ($reserve_time_start < $hope_time_end && $hope_time_end < $reserve_time_end) {
                    $vacancies[$item['room_id']] = 0;
                }
            }
            
            // 予約されていない部屋を取得 $vacancies[room_id] == 1
            $vacant_rooms = [];
            foreach ($vacancies as $room_id => $item) {
                if ($item == 1) {
                    $vacant_rooms[] = $room_id;
                }
            }
            
            if (count($vacant_rooms) == 0) {
                $is_empty = false;
            } else {
                $is_empty = true;
                $_POST['room_id'] = $vacant_rooms[0];
            }
        }

        //空き確認
        if(!$is_empty || $errmsg['hope_date_time']){
            if($errmsg['hope_date_time']){
                $gMsg = $errmsg['hope_date_time'];
            }else{
                $gMsg = "<font color='red' size='-1'>※只今、他の予約との重なりがあります。予約できませんでした。</font><br>";
            }

            $gMsg .= '<form action="./" name="frm" id="frm" method="post">';
            $gMsg .= '<input type="hidden" name="mode" value="send">';
            $gMsg .= '<input type="hidden" name="id" value="'.$_REQUEST['id'].'"/>';
            $gMsg .= '<input type="hidden" name="shop" value="'.$_POST['shop'].'" />';
            $gMsg .= '<input type="hidden" name="hope_date" value="'.$_POST['hope_date'].'" />';
            $gMsg .= '<input type="hidden" name="hope_time" value="'.$_POST['hope_time'].'" />';
            $gMsg .= '<input type="hidden" name="shop_id" value="'.$_POST['shop_id'].'" />';
            $gMsg .= '<input type="hidden" name="name" value="'.$_POST['name'].'" />';
            $gMsg .= '<input type="hidden" name="name_kana" value="'.$_POST['name_kana'].'" />';
            $gMsg .= '<input type="hidden" name="birthday" value="'.$_POST['birthday'].'" />';
            $gMsg .= '<input type="hidden" name="tel" value="'.$_POST['tel'].'" />';
            $gMsg .= '<input type="hidden" name="mail" value="'.$_POST['mail'].'" />';
            $gMsg .= '<input type="hidden" name="pair_flg" value="'.$_POST['pair_flg'].'" />';
            $gMsg .= '<input type="hidden" name="hope_campaign" value="'.$_POST['hope_campaign'].'" /> ';
            $gMsg .= '<input type="hidden" name="hopes_discount" value="'.$_POST['hopes_discount'].'" /> ';
            $gMsg .= '<input type="hidden" name="persons" value="'.$_POST['nunzu'].'" /> ';
            $gMsg .= '<input type="hidden" name="echo" value="'.$_POST['echo'].'" /> ';
            $gMsg .= '<input type="hidden" name="mag" value="'.$_POST['mag'].'" /> ';
            $gMsg .= '<input type="hidden" name="hope_time_range" value="'.$_POST['hope_time_range'].'" /> ';
            $gMsg .= '<input type="hidden" name="memo" value="'.$_POST['memo'].'" /> ';

            if($_POST['persons']>1){
                $gMsg .= '<input type="hidden" name="pair_name_kana" value="'.$_POST['pair_name_kana'].'" />';
                // $gMsg .= '<input type="hidden" name="pair_tel" value="'.$_POST['pair_tel'].'" />';
            }
            $gMsg .= '<input type="submit" value="選びなおす" /> ';
            $gMsg .= '</form><br />';
        }else{

            //顧客新規--------------------------------------------------------------------------------------------------------------------
            $_POST['password'] = generateID(6,'small');
            $_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
            $_POST['mo_agent'] = $mo_agent;

            // 同セッションあったら、adcodeを登録しない　edit by ka 20170602
            //もしくは同じメールアドレス、もしくは「名前もしくは名前カナ」かつ「電話番号が同じ」で媒体を付与しない　add by 20171020
            // if(!Get_Table_Col("customer","id"," WHERE del_flg=0 AND session_id ='".session_id()."' " )){
            $pdo->prepare('SELECT id FROM customer WHERE del_flg = 0 AND (session_id = ? OR mail =? OR tel =? AND name =? OR tel =? AND name_kana =?)');
            $pdo->bindParam(
                session_id(),
                $_POST['mail'],
                $_POST['tel'],
                $_POST['name'],
                $_POST['tel'],
                $_POST['name_kana']
                );
            $pdo->execute();
            if ($pdo->fetch(PDO::FETCH_NUM)[0]) {
                $_POST['adcode'] = $_SESSION['AD_CODE'];
            }

            $_POST['mo_id'] = $mobile_id;
            $_POST['session_id'] = session_id();
            $_POST['url'] = $_SERVER['PHP_SELF'];
            $_POST['referer_url'] = $_SESSION['KIREIMO_REFERER'];
            $_POST['user_agent'] = $ua;

            //２：配信サーバより、ID有り。　１：ID無し、メールアドまた電話存在あり；　メール内変更リンクより、ACTあり、IDあり
            $_POST['rebook_flg'] = ($_POST['id']<>'' && !$_SESSION['ACT']) ? 2 : 1;//梅木より：２　一般再度申込:１
            if($_SESSION['ACT']) $_POST['act_flg'] = $gActionFlag[$_SESSION['ACT']]; //6/26 11:18より

            if (!$_POST['id']) {
                $pdo->prepare('SELECT id FROM customer WHERE del_flg = 0 AND ((mail = ? and tel = ?) or (mail = ? and birthday = ?) or (tel = ? and birthday = ?))');
                $pdo->bindParam([
                    $_POST['mail'],
                    $_POST['tel'],
                    $_POST['mail'],
                    $_POST['birthday'],
                    $_POST['tel'],
                    $_POST['birthday']
                ]);
                $pdo->execute();
                $_POST['id'] = $pdo->fetch(PDO::FETCH_NUM)[0];
            }

            // お友達紹介キャンペーン－広告ID
            $introducer = false;
            if(isset($_SESSION['invite_customerId']) & strlen($_SESSION['invite_customerId']) > 0){
                // 顧客データを取得
                // $introducer = Get_Table_Row("customer"," WHERE id=" . $_SESSION['invite_customerId'] . " AND del_flg=0 ");
                $pdo->query('SELECT * FROM customer WHERE id = ? AND del_flg = 0');
                $pdo->bindParam($_SESSION['invite_customerId']);
                $pdo->execute();
                $introducer = $pdo->fetch();

            }

            $customer_field  = array("no","sn_shop","password","shop_id","name","name_kana","pair_name_kana","birthday","tel","mail","pair_flg","hope_campaign","hopes_discount","echo","mag","hope_time_range","reg_date","edit_date","mo_agent","adcode","mo_id","session_id","url","referer_url","user_agent");
            $customer_field2 =                           array("shop_id","name","name_kana","pair_name_kana","birthday","tel","mail","pair_flg","hope_campaign","hopes_discount","session_id","rebook_flg");

            if($_POST['id']) {
                // お友達紹介キャンペーン－紹介者情報@Tue Sep. 13, 2016
                // 顧客（紹介元）情報があったら
                if($introducer !== false){
                    if(isset($introducer['id']) && strlen($introducer['id']) > 0){
                        // 更新前に、広告ID（`adcode`）の存在チェック
                        $tmp_adcode = Get_Table_Col("customer","adcode"," WHERE id = " . $_POST['id'] . " AND del_flg = 0 ");
                        // 存在しない場合のみ、お友達紹介キャンペーン専用広告ID（`adcode`）を入れる
                        if(strlen($tmp_adcode) < 1){
                            array_push($customer_field2, "adcode");
                            $_POST['adcode'] = INTRODUCTION_ADCODE;
                        }

                        // 変数を破棄
                        unset($tmp_adcode);

                        // 紹介者情報を挿入
                        // 既存データチェック
                        if(Get_Table_Row("introducer"," WHERE customer_id = " . $introducer['id'] . " AND introducer_customer_id = " . $_POST['id'] . " AND del_flg = 0") === false){
                            // 存在しなければ挿入
                            $pdo->prepare('insert introducer (customer_id, introducer_customer_id, reg_date, edit_date, del_flg) values (?, ?, ?, ?, 0) ');
                            $pdo->bindParam([
                                    $introducer['id'],
                                    $_POST['id'],
                                    date('Y-m-d H:i:s'),
                                    date('Y-m-d H:i:s')
                            ]);
                            $pdo->execute();
                        }
                    }
                }

                $_POST['customer_id'] = Update_Data("customer",$customer_field2,$_POST['id']);//再度申込
            } else {
                // お友達紹介キャンペーン－広告ID（`adcode`）@Tue Sep. 13, 2016
                // 顧客（紹介元）情報があったら
                if($introducer !== false){
                    if(isset($introducer['id']) && strlen($introducer['id']) > 0){
                        $_POST['adcode'] = INTRODUCTION_ADCODE;
                    }
                }

                $_POST['customer_id'] = Input_New_Data("customer",$customer_field);//新規
                // $result  = $GLOBALS['mysqldb']->query( "select * from customer ORDER BY id desc limit 1" );
                if(isset($_POST['customer_id']) && strlen($_POST['customer_id']) > 0){
                    // while ( $row = mysql_fetch_assoc($result)){
                        $pdo->prepare('update customer set no= ? where id = ?');
                        $pdo->bindParam([
                                    $shop['code'] . $_POST['customer_id'],
                                    $_POST['customer_id']
                        ]);
                        $pdo->execute();
                        //CVTag用
                        $_POST['no'] = $shop['code'] . $_POST['customer_id'];

                        // お友達紹介キャンペーン－紹介者情報@Tue Sep. 13, 2016
                        // 顧客（紹介元）情報があったら
                        if($introducer !== false){
                            if(isset($introducer['id']) && strlen($introducer['id']) > 0){
                                $pdo->prepare('insert introducer (customer_id, introducer_customer_id, reg_date, edit_date, del_flg) values (?, ?, ?, ?, 0) ');
                                $pdo->bindParam([
                                        $introducer['id'],
                                        $_POST['id'],
                                        date('Y-m-d H:i:s'),
                                        date('Y-m-d H:i:s')
                                ]);
                                $pdo->execute();
                            }
                        }
                    // }
                }
            }



            //クッキー情報格納,新規契約のみ---------------------------------------------------------------------------------------------------------------------
            if(isset($_COOKIE) && !$_POST['id']){
                foreach($_COOKIE as $key => $val){
                    //広告IDのみ、数字のみと判断
                    if(is_numeric($key) && $key<>88888){
                        if($val['KIREIMO_ADCODE_COOKIE']==99999) $val['KIREIMO_ADCODE_COOKIE']="";//媒体なし
                        $cv_flg = ($val['KIREIMO_ADCODE_COOKIE']==$_SESSION['AD_CODE']) ? 1 :0;
                        $pdo->prepare('insert k_cookie (customer_id,referer_url,adcode,first_date,edit_date,reg_date,cnt,cv_flg) ' .
                                        ' values (?, ?, ?, ?, ?, ?, ?, ?);');
                        $pdo->bindParam([
                            $_POST['customer_id'],
                            $val['KIREIMO_REFERER_COOKIE'],
                            $val['KIREIMO_ADCODE_COOKIE'],
                            $val['KIREIMO_COOKIE_DATE'],
                            $val['KIREIMO_COOKIE_LASTDATE'],
                            date("Y-m-d H:i:s"),
                            $val['KIREIMO_COOKIE_CNT'],
                            $cv_flg
                        ]);
                        $pdo->execute();
                    }
                    //オーガニック格納
                    if($key==88888){
                        $cv_flg =  1 ;
                        $val['KIREIMO_ORGANIC_ADCODE_COOKIE'] = $_SESSION['AD_CODE'] ? $_SESSION['AD_CODE'] : "";
                        $pdo->prepare('insert k_organic_cookie (customer_id,referer_url,entrance_url,adcode,first_date,edit_date,reg_date,cnt,lp_flg,cv_flg) ' . 
                                        ' values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
                        $pdo->bindParam([
                                $_POST['customer_id'],
                                $val['KIREIMO_ORGANIC_REFERER_COOKIE'],
                                $val['KIREIMO_ORGANIC_ENTRANCE_COOKIE'],
                                $val['KIREIMO_ORGANIC_ADCODE_COOKIE'],
                                $val['KIREIMO_ORGANIC_COOKIE_DATE'],
                                $val['KIREIMO_ORGANIC_COOKIE_LASTDATE'],
                                date("Y-m-d H:i:s"),
                                $val['KIREIMO_ORGANIC_COOKIE_CNT'],
                                $val['KIREIMO_ORGANIC_LPFLAG_COOKIE'],
                                $cv_flg
                        ]);
                        $pdo->execute();
                    }
                }
            }


            //将来の予約をキャンセル処理-------------------------------------------------------------------------------------------------------------
            if($_POST['id'] && $_POST['customer_id'] ) {
                $pdo->prepare('update reservation set type = 3, edit_date = ? ' .
                                'where type = 1 and del_flg = 0 and customer_id = ? and hope_date >= ?');
                $pdo->bindParam([
                        $_POST['edit_date'],
                        $_POST['customer_id'],
                        date('Y-m-d')
                ]);
                $pdo->execute();
            }

            //予約新規,担当者自動指定？room指定？
            $_POST['type'] = 1;//区分：カウンセリング
            $_POST['new_flg'] = 1;//新規契約フラッグ
            $reservation_field = array("customer_id","shop_id","room_id","type","hope_date","hope_time","length","persons","hope_campaign","hopes_discount","echo","mag","hope_time_range","introducer","introducer_type","special","memo","reg_date","edit_date","adcode","new_flg");
            if($_POST['id']) array_push($reservation_field,  "rebook_flg");//再度申込
            if($_SESSION['ACT']) array_push($reservation_field,  "act_flg");//予約変更
            $data_ID = Input_New_Data($table ,$reservation_field);

            //再度申込が計上なし
            if(!$_POST['id']){
                //申込件数集計
                IncrementAccessLog(date('Y-m-d'), 3, $mo_agent, $_POST['adcode']);
                //解析用
                if($result_id)IncrementAccessLog(date('Y-m-d'), $result_id, $mo_agent, $_POST['adcode']);
            }

            //自動送信*****************************************************************
            mb_language("ja");
            mb_internal_encoding("UTF-8");
            $_POST['name'] = str_replace("﨑", "崎", $_POST['name'] );
            $_POST['name'] = str_replace("髙", "高", $_POST['name'] );
            $returnpath = "-f ".SEND_MAIL_ADDRESS;

// 予約変更
if($_SESSION['ACT']){
    $content = $_POST['name'].'様

全身脱毛サロン キレイモです！

この度は、ご連絡ありがとうございます。
カウンセリングご予約日時の変更を承りましたので、
下記ご予約情報をご確認ください。

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

【ご予約内容】
カウンセリング予約

'.$mail_content.'
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
'.$mail_attention.'
'.$mail_campaign.'
※ご予約日時の変更、キャンセルは下記URLより受付しております。

▼ご予約の変更の場合はこちら▼
'.$home_url.'counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=change

▼ご予約のキャンセルの場合はこちら▼
'.$home_url.'counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=cancel

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

'.$_POST['name'].'様のご来店、スタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

'.str_replace("店舗名",$shop['name'],$mail_footer)
;

    $from = "From:".SEND_MAIL_ADDRESS."\n";
    $from.= "bcc: ".MAIL_YOYAKU."\n";
    $subject = "【KIREIMO】カウンセリングご予約日時変更完了メール";

    if ($_POST['mail']) {
        $kagoya = new KagoyaSendMail();
        $kagoya->send($_POST['mail'], $subject, $content, $from, $returnpath);
    }
}else{
            if($_POST['memo']) $subject .="【備考アリ!】";
            $subject .= "申込報告メール";
            if($_POST['id']) $subject .="(再申込)";

            $content = '
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
【ご予約内容】
カウンセリング予約

'.$mail_content.'
'.$attention_message.'
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
'.$mail_attention.'
'.$mail_campaign.'

※ご予約日時の変更、キャンセルは下記URLより受付しております。

▼ご予約の変更の場合はこちら▼
'.$home_url.'counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=change

▼ご予約のキャンセルの場合はこちら▼
'.$home_url.'counseling/?id='.$_POST['customer_id'].'&rid='.$data_ID.'&act=cancel

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

'.($_POST['name'] ? $_POST['name']."様の" : "").'ご来店、スタッフ一同心よりお待ち申し上げております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

'.str_replace("店舗名",$shop['name'],$mail_footer)
;

            //管理者へ送信
            $content1  = $mail_content1;
            $content1 .= "【お名前】\r\n".$_POST['name']."\r\n";
            $content1 .= "【お名前(カナ)】\r\n".$_POST['name_kana']."\r\n";
            $content1 .= "【生年月日】\r\n".$_POST['birthday']."\r\n";
            $content1 .= "【電話番号】\r\n".$_POST['tel']."\r\n";
            $content1 .= "【メールアドレス】\r\n".$_POST['mail']."\r\n";

            $content1 .= "【参照元】\r\n".$_SESSION['KIREIMO_REFERER']."\r\n";
            $content1 .= "【ブラウザ】\r\n".$ua."\r\n";
            $content1 .= "【広告ID】\r\n".$_SESSION['AD_CODE']."\r\n";
            $content1 .= "【IP】\r\n".$_SERVER["REMOTE_ADDR"]."\r\n";
            $from1 = "From:".SEND_MAIL_ADDRESS."\n";

            //$from1.= "cc: ".$shop['mail']."\n";
            if($_POST['rebook_flg']==2) $from1.= "Bcc: support@kireimo.jp\n";

            // mb_send_mail(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);
            $kagoya = new KagoyaSendMail();
            $kagoya->send(MAIL_YOYAKU, $subject, $content1, $from1,$returnpath);

            $subject2 = "【KIREIMO】お申込受付完了メール（自動返信）";
            $content2 .= $_POST['name']."　様

全身脱毛サロン キレイモです！


この度は、無料カウンセリングにお申込いただき誠にありがとうございます。
カウンセリングのご予約を受け付けました。
";

            $content2 .= $content;

            //自動返信
            $from = "From:".SEND_MAIL_ADDRESS."\n";
            $from.= "cc: ".MAIL_KAKUNIN;//未使用

            //$from .= "Disposition-notification-to:".MAIL_YOYAKU; //開封通知
            // mb_send_mail($_POST['mail'],$subject2,$content2,$from,$returnpath);
            $kagoya->send($_POST['mail'],$subject2,$content2,$from,$returnpath);

}
            //重複申込の場合がCVに計上しない
            if(!$_POST['id']){
                //CVtag list
                $sql = "select * from tag WHERE del_flg = 0 AND status=1 AND coverage=1 AND location=2 ORDER BY id";
                foreach ($pdo->query($sql) as $row) {
                    $tag_head_cv .= View_Cook_Html($row['tag']) ."\n";
                }
            }
            $complete_flg= true;
        }
   }else $duplicated = true;
}
?>
