<?php 
// 本人確認
$is_rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] );

if($is_rsv['id']){
    $ctm = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
    $rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and type=1 and status<2 and customer_id=".$_REQUEST['id'] ." and hope_date>='".date('Y-m-d')."' order by id desc limit 1");
    $shop = Get_Table_Row("shop"," WHERE id=" .$rsv['shop_id'] );
    // 当日時刻過ぎたらリセット
    if($rsv['id'] && $rsv['hope_date']==date("Y-m-d") && $gTime2[$rsv['hope_time']]<=date("H:i")) $rsv = array();
}

// 未来日にカウンセリング予約なし、また、当日時刻過ぎたら
if(!$rsv['id']){
    $gMsg = '
		<div class="select_title">ご予約内容の確認</div>
		<div class="confirm_area"><b class="font_kireimo_pink">キャンセルできる予約データがありません。</b></div>
    ';
// キャンセル処理
}else{
    if($_POST['mode']=="cancel"){

    //candel_route?
    //mysql_query("update reservation set type=3,act_flg=2,edit_date='".date('Y-m-d H:i:s')."' where id=".$rsv['id']);
     mysql_query("update reservation set type=3,edit_date='".date('Y-m-d H:i:s')."' where id=".$rsv['id']);

    $content = $ctm['name'].'様

ご予約のキャンセルを受け付けました。

またのご予約をお待ちしております。

--

'.$ctm['name'].'様のキャンセル情報

【日時】

'.$rsv['hope_date'].'

'.$gTime2[$rsv['hope_time']].'～ （所要時間約60分）

【店舗名】

'.$shop['name'].'

【ご予約内容】

カウンセリング予約

次回のご予約は下記URLよりご予約をお願い致します。

'.$home_url.'counseling/?id='.$ctm['id'].'&rid='.$rsv['id'].'&act=change

'.$ctm['name'].'様のご予約をスタッフ一同心よりお待ちしております。

その他、ご不明な点、ご質問等ございましたらお気軽に下記までご連絡ください。

◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇

脱毛サロンKIREIMO／キレイモ　'.$shop['name'].'

'.$home_url.'

お電話でのお問合せ（11時～20時）

フリーダイヤル：0120-444-680

メールでのお問合せ（24時間OK）

info@kireimo.jp

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

    $subject = "【KIREIMO】カウンセリング予約キャンセル完了メール";
    if($ctm['mail']) mb_send_mail($ctm['mail'], $subject, $content, $from, $returnpath);

    $gMsg = '
		<div class="select_title">ご予約キャンセル完了</div>
		<div class="confirm_area">
			<b class="font_kireimo_pink">予約キャンセルを承りました。</b>
			<p align="center">ご不明点はご遠慮無くコールセンター（0120-444-680）へご連絡下さい。</p>
			<p align="center">またのご来店をスタッフ一同心よりお待ちしております。</p>
		</div>
    ';

  } 
}
?>