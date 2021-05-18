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

// 本人確認
// $is_rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] );
$pdo->prepare('SELECT * FROM reservation WHERE del_flg = 0 AND id = ? AND customer_id = ?');
$pdo->bindParam([$_REQUEST['rid'], $_REQUEST['id']]);
$pdo->execute();
$is_rsv = $pdo->fetch();

$ctm = [];
$rsv = [];
$shop = [];
if (empty($gMsg)) {
    global $gMsg;
    
    $gMsg = '';
}

if($is_rsv['id']){
    // $ctm = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
    $pdo->prepare('SELECT * FROM customer WHERE del_flg = 0 AND id = ?');
    $pdo->bindParam($_REQUEST['id']);
    $pdo->execute();
    $ctm = $pdo->fetch();

    // $rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and type=1 and status<2 and customer_id=".$_REQUEST['id'] ." and hope_date>='".date('Y-m-d')."' order by id desc limit 1");
    $pdo->prepare('SELECT * FROM reservation WHERE del_flg = 0 and type = 1 and status < 2 and customer_id= ? and hope_date >= ? order by id desc limit 1');
    $pdo->bindParam([$_REQUEST['id'], date('Y-m-d')]);
    $pdo->execute();
    $rsv = $pdo->fetch();
    
    // $shop = Get_Table_Row("shop"," WHERE id=" .$rsv['shop_id'] );
    $pdo->prepare('SELECT * FROM shop WHERE id = ?');
    $pdo->bindParam($rsv['shop_id']);
    $pdo->execute();
    $shop = $pdo->fetch();

    // 当日時刻過ぎたらリセット
    if($rsv['id'] && $rsv['hope_date']==date("Y-m-d") && $gTime2[$rsv['hope_time']]<=date("H:i")) $rsv = array();
}

// 未来日にカウンセリング予約なし、また、当日時刻過ぎたら
if(empty($rsv['id'])) {
    $gMsg = '<h2><img src="/img/counseling/tit_4.png" width="820" height="60" alt="ご予約内容の確認"></h2>
    <div id="thanks"><h3 class="pink" style="padding:20px 0;color:#ffa0ac;text-align:center;"><b>キャンセルできる予約データがありません。</h3></div>';

// キャンセル処理
}else{
    if($_POST['mode']=="cancel"){

    $pdo->prepare('update reservation set type=3, edit_date= ? where id= ?');
    $pdo->bindParam([date('Y-m-d H:i:s'), $rsv['id']]);
    $pdo->execute();
    $ctm = $pdo->fetch();

    $name =  $ctm['name_kana'] ? $ctm['name_kana']."様" : "";
    $content = $name.'

全身脱毛サロン キレイモです！

この度は、ご連絡ありがとうございました。
カウンセリングご予約のキャンセルを受け付けました。

▼▼全身脱毛に関するご相談やプランなど、まずは無料カウンセリングを！▼▼
▼▼▼カウンセリングの再予約はこちらから▼▼▼
'.$home_url.'counseling/?id='.$ctm['id'].'&rid='.$rsv['id'].'&act=change

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

【ご予約内容】

カウンセリング予約

【日時】
'.str_replace("-","/",$rsv['hope_date']).'
'.$gTime2[$rsv['hope_time']].'～（所要時間約60～90分）

【店舗名】
'.$shop['name'].'

'.$gPref[$shop['pref']].$shop['address'].'
'.$home_url.'saloninfo/'.$shop['url'].'.html#shop_flow

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

'.$mail_campaign.'

▼▼▼カウンセリングの再予約は、こちらより受け付けています。▼▼▼
'.$home_url.'counseling/?id='.$ctm['id'].'&rid='.$rsv['id'].'&act=change

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

'.$name.'のご予約をスタッフ一同心よりお待ちしております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

'.str_replace("店舗名",$shop['name'],$mail_footer)
;

    //自動送信*****************************************************************
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $returnpath = "-f ".SEND_MAIL_ADDRESS;
    $from = "From:".SEND_MAIL_ADDRESS."\n";
    $from.= "cc: ".MAIL_YOYAKU."\n";
    //$from.= "cc: ".MAIL_YOYAKU.",".$shop['mail']."\n";

    $subject = "【KIREIMO】カウンセリング予約キャンセル完了メール";
    // if($ctm['mail']) mb_send_mail($ctm['mail'], $subject, $content, $from, $returnpath);
    if($ctm['mail']) {
        $kagoya = new KagoyaSendMail();
        $kagoya->send($ctm['mail'], $subject, $content, $from, $returnpath);
    }

    $gMsg = '
        <h2><img src="/img/counseling/complete_cancle.png" alt="ご予約完了" class="wall"></h2>
        <div id="thanks">
            <h3 class="pink" style="font-size:180%;padding:20px 0;color:#ffa0ac;text-align:center;"><b>予約キャンセルを承りました。</h3>
            <div class="inner" style="padding-bottom:250px;">
                <p align="center">ご不明点はご遠慮無くコールセンター（0120-444-680）へご連絡下さい。</p>
                <p align="center">またのご来店をスタッフ一同心よりお待ちしております。</p>
            </div>
        </div>
    ';

  }
}
?>