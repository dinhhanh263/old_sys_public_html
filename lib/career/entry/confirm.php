<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


if(!isset($_SESSION)){
	session_start();
}
session_cache_limiter('none');
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
include_once( "../../lib/tag_job_sp.php" );
// 新卒・中途 採用ページの出しわけ
// if($_POST['page'] <> 'new'){
if($_POST['page'] == 'chuto'){
    // $page_title ="中途";
    // $page_class ="experienced";
    // $page_radio_button_title ="脱毛サロン勤務経験";
    // $types = $gSkill; // radioボタン
// }else if($_POST['page'] == 'new'){
//     $page_title ="新卒";
//     $page_class ="new";
//     $page_radio_button_title ="卒業予定";
//     $types = $gGraduation; // radioボタン
//     $title = 1; // 登録通知メール
}else if($_POST['page'] == 'cc'){
    // $page_title ="コールセンター";
    // $page_class ="";
    // $title = 2; // 登録通知メール
    $job_media_flg = 1;
};

//求人媒体リスト
$job_media_sql  = $GLOBALS['mysqldb']->query( "select * from job_media WHERE del_flg = 0 AND status=0 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $job_media_sql->fetch_assoc() ) {
    $job_media_list[$result['id']] = $result['name'];
};
// 店舗リスト
$shop_list = getDatalist2("shop");
// メール表示用にtypesの配列を結合
// $gtypes = $gGraduation + $gSkill + array(8=>"コールセンタースタッフ採用");

$table = "job";

//フォーム処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* 入力画面から遷移した際、必須項目を確認する */
    // if(isset($_POST["validate_count"])){
    //     // 必須項目nameとpostを比較する。
    //     if($_POST["validate_count"] == ""){// validate_countが無かった場合(javascriptが無効の場合)
    //         $error_msg = "javascriptを有効にしてください。または、03-6721-1299（採用専用ダイヤル）までご連絡ください。";
    //     }else{// validate_countがある
    //         $post_validate_array = explode(",",$_POST["validate_count"]);//validate_countをphp配列化
    //         $post_count = count($_POST);
    //         $i=0;
    //         while($i<$post_count){
    //             if(isset($post_validate_array[$i])){//validate_countの配列がある
    //                 if($_POST[$post_validate_array[$i]] == "" || $_POST[$post_validate_array[$i]] == null){//入力の値が無い場合
    //                     $error_msg = "必須項目の入力が足りません。";
    //                }
    //             }
    //             $i++;
    //         }
    //     }
    //     if($error_msg){ //エラーメッセージがある場合、ファイルの読み込みを中止する
    //         return $error_msg;
    //     }else{ //正常ならvalidate_countを削除
    //         unset($_POST["validate_count"]);
    //     }
    // }

    //値を代入
    $entry_name = htmlspecialchars($_POST["entry_name"], ENT_QUOTES);
    $entry_name_2 = htmlspecialchars($_POST["entry_name_2"], ENT_QUOTES);
    $entry_name_kana = htmlspecialchars($_POST["entry_name_kana"], ENT_QUOTES);
    $entry_name_kana_2 = htmlspecialchars($_POST["entry_name_kana_2"], ENT_QUOTES);
    $school_name = htmlspecialchars($_POST["school_name"], ENT_QUOTES);
    $sex = htmlspecialchars($_POST["sex"], ENT_QUOTES);

    // $birthday_year = htmlspecialchars($_POST["birthday_year"], ENT_QUOTES);
    // $birthday_month = htmlspecialchars($_POST["birthday_month"], ENT_QUOTES);
    // $birthday_day = htmlspecialchars($_POST["birthday_day"], ENT_QUOTES);
    // $birth = $birthday_year.$birthday_month.$birthday_day;

    // //新卒卒業予定年月
    // $graduation_year = $_POST["graduation_year"];
    // $graduation_month = sprintf('%02d', $_POST["graduation_month"]);
    // $graduation_ym = $graduation_year.$graduation_month;

    //誕生日整形
    $birthday_year = $_POST["birthday_year"];
    $birthday_month = sprintf('%02d', $_POST["birthday_month"]);
    $birthday_day = sprintf('%02d', $_POST["birthday_day"]);
    $birth = $birthday_year.$birthday_month.$birthday_day;
    $birthday = $birthday_year."-".$birthday_month."-".$birthday_day;
    //年齢計算
    $age = (int)((date('Ymd') - $birth)/10000);

    $zip1 = htmlspecialchars($_POST["zip1"], ENT_QUOTES);
    $zip2 = htmlspecialchars($_POST["zip2"], ENT_QUOTES);
    $pref = htmlspecialchars($_POST["pref"], ENT_QUOTES);
    $now_address_1 = htmlspecialchars($_POST["now_address_1"], ENT_QUOTES);
    $now_address_2 = htmlspecialchars($_POST["now_address_2"], ENT_QUOTES);
    $now_tel_1 = htmlspecialchars($_POST["now_tel_1"], ENT_QUOTES);
    $now_tel_2 = htmlspecialchars($_POST["now_tel_2"], ENT_QUOTES);
    $now_email = htmlspecialchars($_POST["now_email"], ENT_QUOTES);
    $now_email_2 = htmlspecialchars($_POST["now_email_2"], ENT_QUOTES);
    // $line = htmlspecialchars($_POST["line"], ENT_QUOTES);
    // $station = htmlspecialchars($_POST["station"], ENT_QUOTES);
    $shop_num = htmlspecialchars($_POST["shop_num"], ENT_QUOTES);
    $type = htmlspecialchars($_POST["type"], ENT_QUOTES);
    $exeperience_c = htmlspecialchars($_POST["exeperience_c"], ENT_QUOTES);
    $job_media_id = $_POST["job_media_id"];
    isset($_POST["job_media_id"]) ? ($job_media=htmlspecialchars($job_media_list[$_POST["job_media_id"]], ENT_QUOTES)." ") : $job_media="";
    $opportunity = htmlspecialchars($_POST["opportunity"], ENT_QUOTES);
    if($job_media_flg ==1){ // CC応募の場合
        $job_media = $opportunity;
    }else{
        $job_media = $job_media." ".$opportunity;
    }
    $input_form_title_tab_self_pr = htmlspecialchars($_POST["input_form_title_tab_self_pr"], ENT_QUOTES);
    $input_form_title_tab_self_pr_br = nl2br($input_form_title_tab_self_pr);
    $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES);
    $comment_br = nl2br($comment);

    //全角文字を半角に変換
    $zip1 = mb_convert_kana($zip1,"as");
    $zip2 = mb_convert_kana($zip2,"as");
    $now_tel_1 = mb_convert_kana($now_tel_1,"as");
    $now_tel_2 = mb_convert_kana($now_tel_2,"as");
    $now_email = mb_convert_kana($now_email,"as");

}

    //電話番号整形
    $_POST['now_tel_1'] = sepalate_tel($now_tel_1);
    //年齢計算
    //$age = (int)((date('Ymd') - $_POST["birthday"])/10000);
    // //誕生日整形　旧
    // $birthday_year = $_POST["birthday_year"];
    // $birthday_month = sprintf('%02d', $_POST["birthday_month"]);
    // $birthday_day = sprintf('%02d', $_POST["birthday_day"]);
    // $birth = $birthday_year.$birthday_month.$birthday_day;
    // $birthday = $birthday_year."-".$birthday_month."-".$birthday_day;
    //$_POST['now_tel_2'] = sepalate_tel($now_tel_2); //携帯電話
    // $_POST["age"] = $age;

    // セッションの制御をかけるなら下記
    // if(!strstr($_POST['comment'],"http") && !Get_Table_Row("job"," WHERE del_flg=0 AND session_id ='".session_id()."' " ) ){}
    // メールアドレスが登録されているデータと異なるか、重複してもセッションIDが異なるデータのみ登録フラグを立てる
    //$regist_flg = false;


    //申込件数集計--------------------------------------------------------------
    IncrementAccessLog2(date('Y-m-d'), 4, $mo_agent, $_SESSION['AD_CODE']);
    $mobile_id = get_mobile_id(); // モバイルデータ

    // 新規登録
    $_POST['birthday'] = $_POST['birthday_year'].'-'.$_POST['birthday_month'].'-'.$_POST['birthday_day'];
    unset($_POST['birthday_year']);
    unset($_POST['birthday_month']);
    unset($_POST['birthday_day']);

    $_POST['zip'] = $_POST['zip1'].'-'.$_POST['zip2'];
    unset($_POST['zip1']);
    unset($_POST['zip2']);

    unset($_POST['now_email_2']);

    // 登録データ
    $_POST['mo_agent']      = $mo_agent;
    $_POST['adcode']        = $_SESSION['AD_CODE'];
    $_POST['mo_id']         = $mobile_id;
    $_POST['session_id']    = session_id();
    $_POST['url']           = $_SERVER['PHP_SELF'];
    $_POST['referer_url']   = $_SESSION['KIREIMO_REFERER'];
    $_POST['user_agent']    = $_SERVER['HTTP_USER_AGENT']; //？
    $_POST['reg_flg']       = 2;
    $_POST['graduation_ym'] = $_POST['graduation_year'].$_POST['graduation_month']; // 卒業予定月
    unset($_POST['graduation_year']);
    unset($_POST['graduation_month']);

    // 編集日付
    $_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");


//フォーム処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['mode'] == 'send') {
    if($_POST["type"] == 8){ //CC応募の場合
        $type = htmlspecialchars($_POST["type"], ENT_QUOTES);
        $gtypes = 'コールセンター採用';
    }else{
        unset($_POST['type']);
        $gtypes = '中途採用';
    }
    //データ登録
    $data_ID =  Input_Data($table);

    //応募者への送信処理-------------------------------------------------------------------
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $to = $now_email;
    $from = MAIL_FROM;
    $sbj="【KIREIMO】採用のご応募ありがとうございます";

    /*if($sex == 1){// 男性からの申し込み

        $body = "
        {$entry_name} {$entry_name_2}　様

        KIREIMO（キレイモ）を運営しております
        株式会社ヴィエリスでございます。
        この度はご応募いただきありがとうございます。

        恐れ入りますが、現在男性所属可能な部署のスタッフ募集は行っておりません。
        今後機会がございましたら、ご応募お待ちしております。

        ご応募内容

        ------------------------------------------------------

        ■お名前 : {$entry_name} {$entry_name_2}

        ■お名前(カナ) : {$entry_name_kana} {$entry_name_kana_2}

        ■学校名 : {$school_name}

        ■性別 : {$gSex3[$sex]}

        ■年齢 : {$age} 歳

        ■生年月日 : {$birthday_year}-{$birthday_month}-{$birthday_day}

        ■郵便番号 : {$zip1}-{$zip2}

        ■ご住所 : {$gPref[$pref]}{$now_address_1}{$now_address_2}

        ■電話番号 : {$now_tel_1}

        ■メールアドレス : {$now_email}

        ■最寄り駅 : {$line}線{$station}駅

        ■採用対象 : {$gtypes[$_POST['type']]}

        ■ご応募のきっかけ : {$job_media}

        ■自己PR : {$input_form_title_tab_self_pr}

        ■ご質問等 : {$comment}

        ------------------------------------------------------

        ※ このメールはサーバーより自動返信しております。

        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●
        株式会社ヴィエリス　全身脱毛専門サロンKIREIMO
        人材開発部　採用担当
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金10:00-19:00（日・祝日除く））
        メールアドレスinfo@vielis.co.jp
        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●";

    } else if($_POST['type'] == 1){ // 「卒業予定」が「高校」*/
        /* if($_POST['type'] == 1){// 「卒業予定」が「高校」

        $body = "
        {$entry_name} {$entry_name_2}　様

        KIREIMO（キレイモ）を運営しております
        株式会社ヴィエリスでございます。
        この度は、ご応募いただきありがとうございます。

        恐れ入りますが、高校在学中の方は学校のご担当者様を通してご応募ください。
        お問い合わせ先は以下の通りです。

        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金9:00-20:00（日・祝日除く））
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        メールアドレスinfo@vielis.co.jp

        ご連絡お待ちしております。

        ご応募内容

        ------------------------------------------------------

        ■お名前 : {$entry_name} {$entry_name_2}

        ■お名前(カナ) : {$entry_name_kana} {$entry_name_kana_2}

        ■学校名 : {$school_name}

        ■卒業年月 : {$graduation_year}年{$graduation_month}月

        ■性別 : {$gSex3[$sex]}

        ■年齢 : {$age} 歳

        ■生年月日 : {$birthday_year}-{$birthday_month}-{$birthday_day}

        ■郵便番号 : {$zip1}-{$zip2}

        ■ご住所 : {$gPref[$pref]}{$now_address_1}{$now_address_2}

        ■電話番号 : {$now_tel_1}

        ■メールアドレス : {$now_email}

        ■卒業予定 : {$gtypes[$_POST['type']]}

        ■ご応募のきっかけ : {$job_media}

        ■自己PR : {$input_form_title_tab_self_pr}

        ■ご質問等 : {$comment}

        ------------------------------------------------------

        ※ このメールはサーバーより自動返信しております。

        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●
        株式会社ヴィエリス　全身脱毛専門サロンKIREIMO
        人材開発部　採用担当
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金10:00-19:00（日・祝日除く））
        メールアドレスinfo@vielis.co.jp
        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●";

    } else */ if($_POST['type'] == 8){ // CC応募

        $body = "
        {$entry_name} {$entry_name_2}　様

        KIREIMO（キレイモ）を運営しております
        株式会社ヴィエリスでございます。
        この度はご応募いただきありがとうございます。

        本日より2～3日営業日以内にご返答しますので、
        今しばらくお待ちいただきますようお願いいたします。

        ご応募内容

        ------------------------------------------------------

        ■採用対象 : {$gtypes}

        ■お名前 : {$entry_name} {$entry_name_2}

        ■お名前(カナ) : {$entry_name_kana} {$entry_name_kana_2}

        ■性別 : {$gSex3[$sex]}

        ■年齢 : {$age} 歳

        ■生年月日 : {$birthday_year}-{$birthday_month}-{$birthday_day}

        ■郵便番号 : {$zip1}-{$zip2}

        ■ご住所 : {$gPref[$pref]}{$now_address_1}{$now_address_2}

        ■電話番号 : {$now_tel_1}

        ■メールアドレス : {$now_email}

        ■ご応募のきっかけ : {$opportunity}

        ------------------------------------------------------

        ※ このメールはサーバーより自動返信しております。

        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●
        株式会社ヴィエリス　全身脱毛専門サロンKIREIMO
        人材開発部　採用担当
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金10:00-19:00（日・祝日除く））
        メールアドレスinfo@vielis.co.jp
        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●";

    /* } else if ($_POST['type'] < 6){ // 新卒

        $body = "
        {$entry_name} {$entry_name_2}　様

        KIREIMO（キレイモ）を運営しております
        株式会社ヴィエリスでございます。
        この度はご応募いただきありがとうございます。

        本日より2～3日営業日以内にご返答しますので、
        今しばらくお待ちいただきますようお願いいたします。

        ご応募内容

        ------------------------------------------------------

        ■お名前 : {$entry_name} {$entry_name_2}

        ■お名前(カナ) : {$entry_name_kana} {$entry_name_kana_2}

        ■学校名: {$school_name}

        ■卒業年月 : {$graduation_year}年{$graduation_month}月

        ■性別 : {$gSex3[$sex]}

        ■年齢 : {$age} 歳

        ■生年月日 : {$birthday_year}-{$birthday_month}-{$birthday_day}

        ■郵便番号 : {$zip1}-{$zip2}

        ■ご住所 : {$gPref[$pref]}{$now_address_1}{$now_address_2}

        ■電話番号 : {$now_tel_1}

        ■メールアドレス : {$now_email}

        ■卒業予定 : {$gtypes[$_POST['type']]}

        ■ご応募のきっかけ : {$job_media}

        ■自己PR : {$input_form_title_tab_self_pr}

        ■ご質問等 : {$comment}

        ------------------------------------------------------

        ※ このメールはサーバーより自動返信しております。

        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●
        株式会社ヴィエリス　全身脱毛専門サロンKIREIMO
        人材開発部　採用担当
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金10:00-19:00（日・祝日除く））
        メールアドレスinfo@vielis.co.jp
        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●";
    */
    } else { // 中途

        $body = "
        {$entry_name} {$entry_name_2}　様

        KIREIMO（キレイモ）を運営しております
        株式会社ヴィエリスでございます。
        この度はご応募いただきありがとうございます。

        本日より2～3日営業日以内にご返答しますので、
        今しばらくお待ちいただきますようお願いいたします。

        ご応募内容

        ------------------------------------------------------

        ■採用対象 : 中途採用

        ■お名前 : {$entry_name} {$entry_name_2}

        ■お名前(カナ) : {$entry_name_kana} {$entry_name_kana_2}

        ■性別 : {$gSex3[$sex]}

        ■年齢 : {$age} 歳

        ■生年月日 : {$birthday_year}-{$birthday_month}-{$birthday_day}

        ■郵便番号 : {$zip1}-{$zip2}

        ■ご住所 : {$gPref[$pref]}{$now_address_1}{$now_address_2}

        ■電話番号 : {$now_tel_1}

        ■メールアドレス : {$now_email}

        ■希望店舗 : {$shop_list[$_POST['shop_num']]}

        ■ご応募のきっかけ : {$job_media}

        ------------------------------------------------------

        ※ このメールはサーバーより自動返信しております。

        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●
        株式会社ヴィエリス　全身脱毛専門サロンKIREIMO
        人材開発部　採用担当
        〒106-0032
        東京都港区六本木4-8-6パシフィックキャピタルプラザ6F
        電話番号：03-6721-1299(人材開発部直通)
        （受付時間：月-金10:00-19:00（日・祝日除く））
        メールアドレスinfo@vielis.co.jp
        ●∞∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞○∞∞∞∞●";

    }
    //$body=mb_convert_encoding($body,"JIS","utf-8");
    $header="From: {$from}\nReply-To: {$from}\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
    // 応募者への送信
    // mb_send_mail($to, $sbj, $body, $header);
    $kagoya = new KagoyaSendMail();
    $kagoya->send($to, $sbj, $body, $header);

    //管理者への送信処理------------------------------------------------------------------
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    if($sex == "1"){// 男性からの応募
        $title = '[男性から]';
        // $content .= "※男性からご応募がありました。\nお断りの自動返信メールを送信しました。\n";
        $content .= "※男性からご応募があります。";
    }else if($_POST['type'] == 8){ //cc
        $title = '[CC採用]';
    // } else if($_POST['type'] < 6){ // 新卒（経験/未経験以外の方は新卒）
    //     $title = '[新卒採用]';
    } else{ //中途
        $title = '[中途採用]';
    }

    $subject = $title."【KIREIMO】採用のご応募がありました";
    $content .= "ご応募内容\r\n\r\n";
    $content .= "------------------------------------------------------\r\n\r\n";
    $content .= "■お名前 : ".$entry_name.$entry_name_2."\r\n\r\n";
    $content .= "■お名前(カナ) : ".$entry_name_kana.$entry_name_kana_2."\r\n\r\n";
    // CCか中途の場合は卒業年月は入れない
    if($_POST['type'] != 7 && $_POST['type'] != 8 ){
		// $content .= "■学校名 : ".$school_name."\r\n\r\n";
    	// $content .= "■卒業年月 : ".$graduation_year."年".$graduation_month."月"."\r\n\r\n";
    }
    $content .= "■性別 : " . $gSex3[$sex] . "\r\n\r\n";
    $content .= "■年齢 : ".$age."歳\r\n\r\n";
    $content .= "■生年月日 : ".$birthday."\r\n\r\n";
    $content .= "■郵便番号 : ".$zip1.'-'.$zip2."\r\n\r\n";
    $content .= "■ご住所 : ".$gPref[$pref].$now_address_1.$now_address_2."\r\n\r\n";
    $content .= "■電話番号 : ".$now_tel_1."\r\n\r\n";
    $content .= "■メールアドレス : ".$now_email."\r\n\r\n";
    // 中途の場合は希望店舗表示
    if(!isset($_POST['type'])){
    	$content .= "■希望店舗 : ".$shop_list[$_POST['shop_num']]."\r\n\r\n";
    }
    $content .= "■採用対象 : ".$gtypes."\r\n\r\n";
    $content .= "■ご応募のきっかけ : ".$job_media."\r\n\r\n";
    $content .= "------------------------------------------------------\r\n\r\n";

    //管理者へ送信
    $content1  = $content;
    $content1 .= "\r\n【IP】\r\n".$_SERVER['REMOTE_ADDR']."\r\n";
    $content1 .= "【ブラウザ】\r\n".$_SERVER['HTTP_USER_AGENT'];
    $admin_to = MAIL_ADMIN_TO;
    $send_mail_address = MAIL_FROM;
    $send_mail_bcc =MAIL_BCC;

    // $admin_to = "system@kireimo.jp";
    // $send_mail_address = "system@kireimo.jp";
    // $send_mail_bcc ="system@kireimo.jp";

    $bcc      .="\r\n"."bcc: ".$send_mail_bcc;
    $from = "From:".$send_mail_address."\n";
    $from.="Bcc: ".$send_mail_bcc;
    // 管理者への送信
    // mb_send_mail($admin_to,$subject,$content1,$from);
    $kagoya->send($admin_to,$subject,$content1,$from);


    //サンクスページへ遷移
    if($_POST['type'] == 8){
    	header("Location: ../../career/entry/thanks_cc.php");
    }else{
    	header("Location: ../../career/entry/thanks.php");
    }

}

?>