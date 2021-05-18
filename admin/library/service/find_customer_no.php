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

// CSVインポート
if( $_POST['mode'] == "csv_import" ){

	if ( $_FILES["import_file"]["size"] === 0 )	$gMsg .=  "<span style='color:red;'>※　インポートするファイルを指定してください。</span><br>\n";
	// if ( !$_POST['option_month'] ) $gMsg .=  "<font color='red'>※　何月分支払代金を入力してください。</font><br>\n";
	$import_file = $_FILES['import_file']['tmp_name'];
	$import_date = date("Y-m-d H:i:s");

	if(file_exists($import_file) && !$gMsg){
		$contents = file_get_contents ($_FILES['import_file']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents);
		$contents = str_replace("\r", "\n", $contents);
		$lines = preg_split ('/[\n]+/', $contents);

		$total = 0 ;

		// 月額コースID取得
		$month_id = implodeArray("course","id"," WHERE del_flg=0 AND type=1");

		$search =  array("イト",	"エンド",	"カト",	"クド",	"コノ",	"クノ",	"ゴト",	"コンド",	"サト",	"サイト",	"オッ",	"オフ",	"ソマ",	"トヤマ",		"ズカ",	"m",	"t",	"ショ",	"イョ",	"イャ",		"イュ",	"ネッ");
		$replace = array("イトウ",	"エンドウ",	"カトウ",	"クドウ",	"コウノ",	"クノウ",	"ゴトウ",	"コンドウ",	"サトウ",	"サイトウ",	"オオ",	"オオウ",	"ソウマ",	"トウヤマ",	"ヅカ",	"ッ",	"ヨ",	"ショウ",	"ヨ",	"ヤ",		"ユ",	"ネス");

		foreach($lines as $val){
			//　初期化
			$auto_pay_no = "";
			$no = "";
			$card_name = "";
			$name = "";
			$card_no = "";
			$customer_no = "";
			$where = "";

			$val = trim($val);
			list($auto_pay_no,$no,$card_name,$name,$card_no) = explode(",",$val);

			if(mb_detect_encoding($name, "auto")=='SJIS')	$name = mb_convert_encoding($name, 'UTF-8','shift-jis'); // if import file code is shift-jis

			$name = mb_convert_kana($name,"SKV", "UTF-8");
			$name = str_replace($search,$replace,$name);

			$search2  = array("ジュウン",	"ウウ",	"サトウミ",	"ヨウンッ",	"Ｌイッ",	"オヒャマ",	"ミズホ　ハタ",	"ユコ",	"サトウコ",		"ヨコ",	"エＬＬイ",	"アズサ");
			$replace2 = array("ジュン",	"ウ",	"サトミ",	"ヨン",	"イム",	"オオヤマ",	"ミヅホ",		"ユウコ",	"サトコ",		"ヨウコ",	"エリ",	"アヅサ");
			$name = str_replace($search2,$replace2,$name);

			// 全角に変換後、スペースを削除
			$name1 = str_replace("　","",$name);
			list($name11,$name22)  = explode("　",$name);
			$name2 = $name22.$name11; // 姓名を逆に

			//　カード名義が半角に統一
			$card_name = mb_convert_kana($card_name, "skah", "UTF-8");
			list($card_name11,$card_name22)  = explode(" ",$card_name);
			$card_name2 = $card_name22.' '.$card_name11; // 姓名を逆に

			// カードNoが4桁整形
			$card_no = sprintf("%04d", $card_no);

			// 処理
			if( $auto_pay_no){
				$data .= '<tr class="alternate-row">';
				$data .= '<td>'.$auto_pay_no.'</td>';
				$data .= '<td>'.$no.'</td>';
				$data .= '<td>'.$card_name.'</td>';
				$data .= '<td>'.$name.'</td>';
				$data .= '<td>'.$card_no.'</td>';
				$data .= '<td>';

				// 条件：会員番号が部分一致、かつ、名前またカナが部分一致,カード名義があればカード名義から検索、かつ月額契約
				$where = " WHERE c.id=t.customer_id
							AND c.del_flg=0
							AND t.del_flg=0
							AND (replace(c.card_name,'　',' ')='".$card_name."' OR replace(c.card_name,'　',' ')='".$card_name2."')
							AND c.card_name<>''";
			  if(!empty($no)){
				$where .= "	AND ( c.card_no='".$card_no."' AND c.card_no<>'' OR c.no='".$no."' )";
			  }else{
			  	$where .= "	AND c.card_no='".$card_no."' AND c.card_no<>'' ";
			  }
				$where .= "	AND t.course_id in(".$month_id." )
							ORDER BY c.no
						  " ;

				if( $customer_no = Get_Table_Array_Multi("customer c,contract t","c.no", $where) ){
					$i = 0;
					$pre_no = "";
					foreach($customer_no as $key => $val){
						if ($i && $pre_no<>$val['no']) $data .= ", ";
						if ($pre_no=="" || $pre_no<>"" && $pre_no<>$val['no']) $data .= $val['no'];
						$pre_no = $val['no'];
						$i++;
					}
				}
				$data .= '</td></tr>';
				$total++;
			}
		}
		$gMsg = '<font color="red">※ '.$total.'件が処理しました。</font>';
	}
}
