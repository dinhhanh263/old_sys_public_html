<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and type=1 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] ." and hope_date>=".date('Y-m-d'));
if($rsv['id']){
    $ctm = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
    $shop = Get_Table_Row("shop"," WHERE id=" .$rsv['shop_id'] );
}

// キャンセル処理
if($rsv['id'] && $_POST['mode']=="cancel"){
    //candel_route?
    //$GLOBALS['mysqldb']->query("update reservation set type=3,act_flg=2,edit_date='".date('Y-m-d H:i:s')."' where id=".$rsv['id']);
     $GLOBALS['mysqldb']->query("update reservation set type=3,edit_date='".date('Y-m-d H:i:s')."' where id=".$rsv['id']);

    $content = $ctm['name'].'様

ご予約のキャンセルを受け付けました。

またのご予約をお待ちしております。

--

'.$ctm['name'].'様のキャンセル情報

【日時】

'.$rsv['hope_date'].'

'.$gTime2[$rsv['hope_time']].'～

【店舗名】

'.$shop['name'].'

【ご予約内容】

カウンセリング予約

次回のご予約は下記URLよりお願い致します。

http://kireimo.jp/mens/counseling/?id='.$ctm['id'].'&rid='.$rsv['id'].'&act=change

'.$ctm['name'].'様のご予約をスタッフ一同心よりお待ちしております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンKIREIMO／キレイモ　'.$shop['name'].'

http://kireimo.jp/mens/

お電話でのお問合せ（11時～20時）

フリーダイヤル：0120-444-276

メールでのお問合せ（24時間OK）

mens.info@kireimo.jp

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

※本メールは自動配信メールです。このメッセージに返信しないようお願いいたします。
';


    //自動送信*****************************************************************
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $returnpath = "-f ".SEND_MAIL_ADDRESS;
    $from = "From:".SEND_MAIL_ADDRESS."\n";
    $from.= "cc: ".MAIL_YOYAKU."\n";
    //$from.= "cc: ".MAIL_YOYAKU.",".$shop['mail']."\n";

    $subject = "【MEN'S KIREIMO】カウンセリング予約キャンセル完了メール";
    // if($ctm['mail']) mb_send_mail($ctm['mail'], $subject, $content, $from, $returnpath);
    if ($ctm['mail']) {
        $kagoya = new KagoyaSendMail();
        $kagoya->send($ctm['mail'], $subject, $content, $from, $returnpath);
    }

    $gMsg = '
        <h1>
            <span class="jp">予約キャンセルを承りました。</span>
            <p>ご不明点はご遠慮無く'.$shop['name'].'('.$shop['tel'].')へご連絡下さい。</p>
            <p>またのご来店をスタッフ一同心よりお待ちしております。</p>
        </h1>
    ';

}
?>