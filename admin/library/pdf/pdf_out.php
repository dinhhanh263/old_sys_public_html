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
require_once LIB_DIR . 'auth.php';

$table = "contract";
$shop_tel = "0120-444-680";

// 契約書類出力用
if ($_GET['contract_id'] != "") {
    $contract = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . h($_GET['contract_id']) . "'");
    if ($contract['id'] != "") {
        $shop = Get_Table_Row("shop", " WHERE del_flg=0 AND id = '" . h($contract['shop_id']) . "'");
        $customer = Get_Table_Row("customer", " WHERE del_flg=0 AND id = '" . h($contract['customer_id']) . "'");
        $course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . h($contract['course_id']) . "'");
        $reservation = Get_Table_Row("reservation", " WHERE del_flg=0 AND id = '" . h($contract['reservation_id']) . "'");
        $staff = Get_Table_Row("staff", " WHERE del_flg=0 AND id = '" . h($contract['staff_id']) . "'");

        $customer_name =  $customer['name'] ? $customer['name'] : $customer['name_kana']; // 顧客名
        $length = str_replace("分", "", $gLength[$course['length']]); // 1回のお手入れ時間(分)

        // 契約回数、単価
        $times = 0; // 契約回数
        if ($contract['times']) {
            $times = ($course['type'] == 1) ? 1 : $contract['times']; // 月額の場合、契約回数を1に置換
            $per_fixed_price = round($contract['fixed_price'] / $times); // 単価(定価)
            $per_price = round(($contract['fixed_price'] - $contract['discount']) / $times); // 単価(割引後金額)
            if ($contract['discount']) {
                $per_discount = round($contract['discount'] / $times); // 単価(割引金額)
            }
        }

        // 特典明細
        if ($contract['option_name'] == 12) $option_name = "バースデーキャンペーン全身脱毛1回無料プレゼント※契約回数消化後使用可能"; // 無料追加(全身1回)
        elseif ($contract['option_name'] == 13) $option_name = "全身脱毛1回無料チケットプレゼント"; // フリーチケット(全身1回)
        else $option_name = "";
        if ($option_name) {
            $option_times = 1;
            $option_price = 0;
        } else {
            $option_times = "";
            $option_price = "";
        }

        // 施術期間(契約期間)
        $treatment_start_date = ($course['type'] == 1 && $contract['start_ym']) ? date("Y-m-d", strtotime($contract['start_ym'] . "01")) : $contract['contract_date']; // 施術期間(契約期間)開始日
        $treatment_last_date = ($course['type'] == 1 && $contract['start_ym']) ? date("Y-m-d", strtotime($contract['start_ym'] . "01" . " last day of next month")) : $contract['end_date']; // 施術期間(契約期間)終了日
        $contract_period = ($treatment_last_date != "0000-00-00") ? str_replace("-", "/", $treatment_start_date) . "～" . str_replace("-", "/", $treatment_last_date) : ""; // 施術期間(契約期間)

        // SP、無制限プラン以外の場合、回数保証期間を設定
        $contract_period2 = ""; // 回数保証期間
        if (!($course['zero_flg'] == "1" && $course['sales_start_date'] >= "2019-11-06")) {
            $guarantee_start_date = date("Y-m-d", strtotime($contract['end_date'] . '+1 day')); // 回数保証期間開始日
            $guarantee_last_date = $contract['extension_end_date']; // 回数保証期間終了日
            $contract_period2 = ($guarantee_last_date != NULL) ? str_replace("-", "/", $guarantee_start_date) . "～" . str_replace("-", "/", $guarantee_last_date) : ""; // 回数保証期間
        }

        // 旧コースのデータを取得
        if ($contract['old_contract_id'] != 0) {
            $old_contract = Get_Table_Row("contract", " WHERE customer_id !=0 AND del_flg=0 AND id='" . h($contract['old_contract_id']) . "'");
            if (isset($old_contract)) {
                $old_course = Get_Table_Row("course", " WHERE del_flg=0 AND id='" . h($old_contract['course_id']) . "'");

                // 月額からパックにプラン変更した場合、旧コース残金明細を出力しない
                if ($old_course['type'] == 0) {
                    // $old_payed_price = $old_contract['price'] - $old_contract['balance']; // 支払済金額
                    $old_price = $old_contract['fixed_price'] - $old_contract['discount']; // コース金額
                    $old_payed_price = $old_price - $old_contract['balance']; // 支払済金額
                    $old_per_price = round($old_price / $old_contract['times']); // 消化単価
                    if (isset($old_contract['r_times'])) {
                        $old_contract_r_times = $old_contract['r_times'];
                        $old_contract_r_price = $old_contract_r_times * $old_per_price;
                        $old_contract_balance = $old_payed_price - $old_contract_r_price;
                    }
                }

                // 契約回数、未消化回数
                $old_times = $old_remain_times = 0;
                if ($old_contract['times']) {
                    $old_times = ($old_course['type'] == 1) ? 1 : $old_contract['times']; // 月額の場合、契約回数を1に置換
                    $old_remain_times = ($old_times < $old_contract['r_times']) ? 0 : $old_times - $old_contract['r_times']; // 未消化回数
                }

                // 施術期間(契約期間)
                $old_treatment_start_date = ($old_course['type'] == 1 && $old_contract['start_ym']) ? date("Y-m-d", strtotime($old_contract['start_ym']."01")) : $old_contract['contract_date']; // 施術期間(契約期間)開始日
                $old_treatment_last_date = ($old_course['type'] == 1 && $old_contract['start_ym']) ? date("Y-m-d", strtotime($old_contract['start_ym']."01"." last day of next month")) : $old_contract['end_date']; // 施術期間(契約期間)終了日
                $old_contract_period = ($old_treatment_last_date != "0000-00-00") ? str_replace("-", "/", $old_treatment_start_date)."～".str_replace("-", "/", $old_treatment_last_date) : ""; // 施術期間(契約期間)
            }
        }

        // プラン組替通知書用
        $remain_times = ($times < $old_contract['r_times']) ? 0 : $times - $old_contract['r_times']; // 未消化回数
        $old_cancel_date = ($old_contract['cancel_date'] && $old_contract['cancel_date'] != '0000-00-00') ? $old_contract['cancel_date'] : date('Y-m-d'); // 組替日
    }

// 領収書出力用
} elseif ($_GET['sales_id'] != "") {
    $sales = Get_Table_Row("sales", " WHERE del_flg=0 AND id = '" . h($_GET['sales_id']) . "'");
    if ($sales['id'] != "") {
        $contract = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . h($sales['contract_id']) . "'");
        $shop = Get_Table_Row("shop", " WHERE del_flg=0 AND id = '" . h($sales['shop_id']) . "'");
        $customer = Get_Table_Row("customer", " WHERE del_flg=0 AND id = '" . h($sales['customer_id']) . "'");
        $course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . h($sales['course_id']) . "'");
        $reservation = Get_Table_Row("reservation", " WHERE del_flg=0 AND id = '" . h($sales['reservation_id']) . "'");

        $customer_name =  $customer['name'] ? $customer['name'] : $customer['name_kana']; // 顧客名
        $option_name = $sales['option_name'] ? $gOption[$sales['option_name']] : "オプション"; // オプション名
        $option_price = $sales['option_price'] + $sales['option_card'] + $sales['option_transfer']; // オプション金額

        $fixed_price = ($sales['type'] == 6) ? $sales['price'] + $sales['discount'] : $sales['fixed_price']; // 購入金額(割引前)

        // 店舗情報
        $shop_info = (
            "https://kireimo.jp" . "\n\n"
            . "株式会社ヴィエリス" . "\n"
            . "KIREIMO " . $shop['name'] . "\n"
            . "〒" . $shop['zip'] . "\n"
            . $shop['address'] . "\n"
            . "TEL:" . $shop_tel . "\n"
            . "Email:info@kireimo.jp" . "\n\n"
            . "発行日:" . date("Y/m/d")
        );

        // 消費税率
        if ($sales['pay_date'] < "2014-04-01") {
            $tax = 0.05;
            $tax2 = 1.05;
        } elseif ($sales['pay_date'] < "2019-10-01") {
            $tax = 0.08;
            $tax2 = 1.08;
        } else {
            $tax_data = Get_Table_Row("basic"," WHERE id = 1");
            $tax = $tax_data['value'];
            $tax2 = 1 + $tax_data['value'];
        }

        // 月額プランの場合、精算時の消費税率で金額を算出
        if ($course['type'] == 1) {
            $monthly_price = round($course['price'] * $tax2);
            // 月額で税込価格が奇数の場合、1円足す
            if ($monthly_price %2 != 0) $monthly_price = $monthly_price + 1;
        }

        // 消化回数、残回数
        if ($contract['id'] != "") {
            // $times = ($course['type'] == 1) ? 1 : $contract['times']; // 月額の場合、回数を1に置換
            $r_times = ($sales['r_times'] > 0) ? $sales['r_times'] : $contract['r_times']; // 消化回数
            $remain_times = ($contract['times'] > $r_times) ? ($contract['times'] - $r_times) : 0; // 残回数
        } else {
            $r_times = $remain_times = 0;
        }

        // 消化単価
        // 〜〜〜〜〜library/service/detail.phpからコピー〜〜〜〜〜
            // 割引率の計算 2016/12/16 add by shimada
            if($customer['introducer_type']==3){
                // スタッフ紹介
                $rate_intro = 0.2;
            } else if($customer['introducer_type']==5){
                // 企業紹介
                $rate_intro = 0.1;
            } else {
                // 紹介なし
                $rate_intro = 0;
            }

            // 単価の種類を定義
            $price_once    = 0;                                                 // 消化単価(初期化)
            if($sales['times']){
                $per_price_dis = ($course['type'] == 1 ? round(($monthly_price-$sales['discount'])/$sales['times']) : round(($sales['fixed_price']-$sales['discount'])/$sales['times']));                      // 割引単価
            } else {
                $per_price_dis = 0;                      // 割引単価
            }
            //$per_price_dis = round(($sales['fixed_price']-$sales['discount'])/$sales['times']);                      // 割引単価
            //$per_price     = round($sales['fixed_price']*(1-$rate_intro)/$sales['times']);// 通常単価
            $per_price_adj = ($course['type'] == 1 ? ($monthly_price-$sales['discount'])-($sales['times']-1)*$per_price_dis : ($sales['fixed_price']-$sales['discount'])-($sales['times']-1)*$per_price_dis);          // 調整単価
            if($sales['times']){
                $per_price     = ($course['type'] == 1 ? round($monthly_price*(1-$rate_intro)/$sales['times']) : round($sales['fixed_price']*(1-$rate_intro)/$sales['times']));// 通常単価
            } else {
                $per_price     = 0;// 通常単価
            }

            // コース別 加算値を設定 2016/12/16 add by shimada
            // 旧月額/パック ×1回毎、新月額 ×2回毎
            $course_plus = 1;
            if($course['new_flg']){
                // 加算値
                $course_plus = 2;
            }

            // 消化単価を計算する 2016/12/16 add by shimada
            if($course['type']){
            // 月額処理
                // 割引期間内(割引最終回を含む)
                if( ($reservation['r_times']-1)*$course_plus < $sales['times'] && $sales['r_times']*$course_plus >= $sales['times']){
                    // コース回数で偶数・奇数のときの計算
                    if($sales['times']%2==1){// 奇数
                        // 全身:2回分、半身:1回分の振り分け
                        if($reservation['part']==0){
                            // 調整単価+通常単価
                            $price_once = $per_price_adj+$per_price;
                        } else {
                            // 調整単価
                            $price_once = $per_price_adj;
                        }
                    } else { // 偶数
                        // 全身:2回分、半身:1回分の振り分け
                        if($reservation['part']==0){
                            // 調整単価+割引単価
                            $price_once = $per_price_adj+$per_price_dis;
                        } else {
                            // 割引単価(運用上想定なし)
                            $price_once = $per_price_dis;
                        }
                    }
                }
                // 割引期間内+割引期間外(割引最終回は含まない)
                elseif($sales['r_times']*$course_plus < $sales['times'] && $sales['r_times'] > 0){
                    // 全身:2回分、半身:1回分の振り分け
                    if($reservation['part']==0){
                        // 割引単価*$course_plus
                        $price_once = $per_price_dis *$course_plus;
                    } else {
                        // 割引単価
                        $price_once = $per_price_dis;
                    }
                }
                // 通常の消化(消化以外のレジ精算もここ)
                else {
                    // 全身:2回分、半身:1回分の振り分け
                    if($reservation['part']==0){
                        $price_once = $per_price*$course_plus;
                    } else {
                        $price_once = $per_price;
                        //ホットペッパー月額ケース(既存)
                        if($sales['course_id']==70){
                            $price_once = $course_price['45']*1.08/$course_times['45']; //消費税1.08に固定
                        }
                    }
                }
            } else {
                $price_remain = 0;
                // 割引単価
                $price_once = $per_price_dis;
                // 役務残(請求金額-消化済単価)
                $price_remain = $sales['price'] - $price_used ;
            }
        // 〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜ここまで〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜

        // プラン組替領収書用
        if ($contract['id'] != "") {
            $new_contract = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . h($contract['new_contract_id']) . "'");
            $new_course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . h($contract['new_course_id']) . "'");
            $old_course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . h($contract['course_id']) . "'");

            $new_per_price = $new_usered_price = $new_remained_price = 0;
            if ($new_contract['times']) {
                $new_per_price = round(($new_contract['fixed_price'] - $new_contract['discount']) / $new_contract['times']); // 消化単価(組替後プラン)
                $new_used_price = $new_per_price * $contract['r_times']; // 消化金額(組替後プラン)
                $new_remained_price = ($new_contract['fixed_price'] - $new_contract['discount']) - $new_used_price; // 未消化金額(組替後プラン)
            }
        }

        // 物販領収書用
        $product_stocks = Get_Table_Array("product_stock","*"," WHERE del_flg=0 and sales_id = '".addslashes($sales['id'])."'");
        if ($product_stocks != "") {
            $product_name = $product_fixed_price = $product_count = $product_price = [];
            $product_total_price = 0;
            for ($i = 0; $i < (count($product_stocks)); $i++) {
                $product_no = $product_stocks[$i]['product_no'];
                $product_name[$i] = Get_Table_Col("product", "name", " WHERE del_flg=0 and id = '".addslashes($product_no)."'"); // 商品名
                $product_fixed_price[$i] = $product_stocks[$i]['price']; // 商品定価
                $product_count[$i] = $product_stocks[$i]['product_count']; // 商品購入数
                $product_price[$i] = $product_fixed_price[$i] * $product_count[$i]; // 商品金額
                $product_total_price += $product_price[$i]; // 合計
            }
        }
    }
}