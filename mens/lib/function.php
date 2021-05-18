<?php 

/**************************************************
* 表示用加工
**************************************************/

// POSTデータの浄化関数
function Clean_Up_Post($dec = 0){
	foreach($_POST as $key => $val){
		if(is_array($_POST[$key])){
			foreach($_POST[$key] as $key2 => $val2){
				if($dec == 1){
					$val2 = rawurldecode($val2);
				}
				$val2 = mb_convert_kana($val2,"K");
				$_POST[$key][$key2] = stripslashes($val2);
			}
		}else{
			if($dec == 1){
				$val = rawurldecode($val);
			}
			$val = mb_convert_kana($val,"K");
			$_POST[$key] = stripslashes($val);
		}
	}
}


// 未入力チェック
function Check_Text($val) {
	$err_msg = '<br><font clor="#FF0000">入力されていません。</font>';
	if ($val == "") {
		return $err_msg;
	} else {
		return "";
	}
}


// 表示用加工関数（クロススクリプティング対策？タグの置換え）
function View_Cook($val){
	$val = stripslashes($val);
	$val = htmlspecialchars($val);
	$val = nl2br($val);
	//$val = mb_convert_kana($val,"K");
	return $val;
}


// 表示用加工関数（HTML許可）.JSタグで使用中
function View_Cook_Html($val){
	$val = stripslashes($val);
	//$val = nl2br($val);
	//$val = mb_convert_kana($val,"K");
	//置換
	if(strstr($val, '%%"%%')) $val = str_replace('%%"%%',"'",$val);
	if(strstr($val, '##"##')) $val = str_replace('##"##',"'",$val);
	return $val;
}


// 表示用加工関数（一部のタグを許可 ）
function Tag_Cook($val){
	$val = stripslashes($val);
	$val = mb_convert_kana($val,"K");
	$val = strip_tags( $val , '<a><b><font><strong><u><div><span>' );
	$val = nl2br($val);
	return $val;
}


// ファイル表示用関数
function View_File($file){
	$ext = strtolower(substr($file,-3,3));
	if($ext == "jpg" || $ext == "gif"){
		echo('<img src="'.$file.'" border="0">');
	}elseif($ext == "swf"){
		echo('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100" height="100">');
		echo('<param name="movie" value="'.$file.'">');
		echo('<param name="quality" value="high">');
		echo('<embed src="'.$file.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="180" height="180"></embed>');
		echo('</object>');
	}
}


// 画像表示用関数
function View_Image($file,$alt,$limit){
	if($limit == ""){ $limit = 2000; }
	if(is_file($file)){
		list($width,$height,$type,$attr) = getimagesize($file);
		if($width > $limit){
			$height = floor(($limit * $height) / $width);
			$width = $limit;
		}
		echo('<img src="'.$file.'" width="'.$width.'" height="'.$height.'" alt="'.htmlspecialchars($alt).'"/>');

	}
}


// 画像表示用関数（スタイル指定）
function View_Image_CSS($file,$alt,$limit,$style){
	if($limit == ""){ $limit = 2000; }
	if(is_file($file)){
		list($width,$height,$type,$attr) = getimagesize($file);
		if($width > $limit){
			$height = floor(($limit * $height) / $width);
			$width = $limit;
		}
		echo('<img src="'.$file.'" width="'.$width.'" height="'.$height.'" alt="'.htmlspecialchars($alt).'" class="'.$style.'" />');

	}
}


// テキストエリア内表示用関数（クロススクリプティング対策？タグの置換え）
function TA_Cook($val){
	$val = stripslashes($val);
	$val = htmlspecialchars($val);
	//$val = mb_convert_kana($val,"K");
	return $val;
}


// HIDDEN用文字加工関数
function Hide_Cook($val){
	$val = stripslashes($val);
	$val = mb_convert_kana($val,"K");
	$val = str_replace("\r","",$val);
	$val = str_replace("\t","",$val);
	$val = rawurlencode($val);
	return $val;
}


// DBへのSAVE用文字加工関数(''付き)
function DB_Cook($val,$type){
	if( $type == 1 ){
		if($val != "")$val = rawurldecode($val);
	}elseif( $type == "S" ){
		$val = mb_convert_encoding( $val , 'EUC-JP' , 'SJIS' );
	}elseif( $type == "ES" ){
		$val = mb_convert_encoding( $val , 'SJIS' , 'EUC-JP' );
	}elseif( $type == "IN"){
        !empty($val)?rawurldecode($val): $val= "0"; 
        return $val;
    }
	//$val = "'".addslashes($val)."'";//tag save時￥が付けられ
	$val = "'".($val)."'";
	
	return $val;
}


// POSTデータを一括でDBへのSAVE用文字加工関数(''付き)
function Post_To_DB_Cook($names){
	if(is_array($names)){
		foreach($names as $name){
			$vals[] = $name." = ".DB_Cook($_POST[$name],"1");
		}
	}
	$val = implode(",",$vals);
	return $val;
}


//生年月日から年齢を自動計算
function Birth_To_Age($input_date,$input_age){
 	//現在の日付
 	$now = date('Ymd');
 	//生年月日あり
	if($input_date && $input_date<>"0000-00-00"){
	     if(strstr($input_date, "/")){
	          list($year,$month,$day) = explode("/", $input_date);
	          if($month<10) $month = "0".$month;
	          if($day<10) $day = "0".$day;
	     }else{
	          list($year,$month,$day) = explode("-", $input_date);
	     }
	     $birthday = $year.$month.$day;
	        $age = floor(($now-$birthday)/10000);
	 }elseif($input_age){
	     $age = $input_age;
	 }
	 return $age;
}

//住所に「#」が含まれていた場合、半角スペースに置き換える
function Address_Check($input_address){
 	//「#」を検索する
 	if (strstr($input_address, '#')) {
 		//「#」を半角スペースに置き換え
 		$input_address = strtr($input_address,'#',' ');
 	}
 	return $input_address;
}

/**
 * 特殊文字の判別 2017/06/16 add by shimada
 * @param string $string
 * @return カンマ区切りの特殊文字(特殊文字がなければ""を返す)
 */
function Invalid_Characters_Check($text) {
	// UTF-8に変換する
	mb_regex_encoding('UTF-8');
 
	// 特殊文字
	$pdc = '≒≡∫√⊥∠∵∩∪ⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹ￢￤＇＂ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ￢㈱№℡∵①';
	$pdc.= '②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑯⑰⑱⑲⑳⑴⑵⑶⑷⑸⑹⑺⑻⑼⑽⑾⑿⒀⒁⒂⒃⒄⒅⒆⒇♠ ♡ ♢ ♣ ♤ ♥ ♦ ♧ ♨ ♩';
	$pdc.= ' ♪ ♫ ♬ ♭ ♮ ♯!"#$%&\'()=~|`{+*}<>?_！”＃＄％＆’（）＝～｜‘｛＋＊｝＜＞？＿';
	$pdc_array = Array();
	$pdc_text = str_replace(array("\r\n","\n","\r"), '', $text); //改行を除外する
 
	// 特殊文字判定用に文字の配列を作る
	while($iLen = mb_strlen($pdc, 'UTF-8')) {
		array_push($pdc_array, mb_substr($pdc, 0, 1, 'UTF-8'));
		$pdc = mb_substr($pdc, 1, $iLen, 'UTF-8');
	}
 	
 	//　特殊文字とされる文字(初期化)
	$target_character_array= array();
	// 特殊文字があるか1文字ずつチェックする
	foreach($pdc_array as $value) {
		// 特殊文字判定
		if(preg_match("/(" . preg_quote($value,"/u") . ")/", $pdc_text)) {
			// 特殊文字あり
			$target_character_array[] = $value;
		}
	}

	// 特殊文字があった場合、特殊文字の配列を半角空白区切りの文字列にする
	if($target_character_array){
		// 特殊文字(表示用)
	    $target_character = implode(' ',$target_character_array);
	} else {
		// 特殊文字なし
	    $target_character ="";
	}
	return $target_character;
}


/**
 * カナの判別 2017/06/16 add by shimada
 * @param string $string
 * @return $check_flg true:エラーあり、false：エラーなし
 * ※admin/library/sheet.phpにあった処理を共通処理に移動しました。
 */
function Invalid_Characters_Kana_Check($text) {
	// カナのエラーなし
	$check_flg=false;// 初期化
	// カナのエラー判定
	if(!preg_match("/^([　 \t\r\n]|[ァ-ヶー])+$/u", $text)){
		// カナのエラーあり
		$check_flg=true;
	}
	return $check_flg;
}

// 前後の半角・全角スペースを削除する 2017/07/12 add by shimada
function Space_Delete($str){
	// 行頭の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/^[ 　]+/u', '', $str);
    // 末尾の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/[ 　]+$/u', '', $str);
    return $str;
}


/**************************************************
* フォーム入力確認
**************************************************/

// フォーム入力確認関数（テキスト）
function Conf_Post_Text($name){
	$name = explode(":",$name);
	if( $name[0] != "" && $name[1] != "" ){
		$val = $_POST[$name[0]][$name[1]];
		$val = stripslashes($val);
		$val = str_replace("\\","￥",$val);
		$val = mb_convert_kana($val,"K");
		$val = str_replace("\r","",$val);
		$val = str_replace("\t","",$val);
		echo('<input name="'.$name[0].'['.$name[1].']" type="hidden" value="'.rawurlencode($val).'">');
		$val = htmlspecialchars($val);
		echo('<span class="conf">'.nl2br($val).'</span>');
	} else {
		$val = $_POST[$name[0]];
		if ($val == "") {
			echo('<input name="'.$name[0].'" type="hidden" value="'. $val .'">');
			echo('<span class="conf"> </span>');
		} else {
			$val = stripslashes($val);
			$val = str_replace("\\","￥",$val);
			$val = mb_convert_kana($val,"K");
			$val = str_replace("\r","",$val);
			$val = str_replace("\t","",$val);
			echo('<input name="'.$name[0].'" type="hidden" value="'.rawurlencode($val).'">');
			$val = htmlspecialchars($val);
			echo('<span class="conf">'.nl2br($val).'</span>');
		}
	}
}


// フォーム入力確認関数（チェックボックス）
function Conf_Post_CBox($name){
	$name = explode(":",$name);
	if( $name[0] != "" && $name[1] != ""){
		$val = $_POST[$name[0]][$name[1]];
		if( $val != "" ){
			echo('<span class="conf">■</span>');
			echo('<input name="'.$name[0].'['.$name[1].']" type="hidden" value="'.$val.'">');
		}else{
			echo('<span class="conf">□</span>');
			echo('<input name="'.$name[0].'['.$name[1].']" type="hidden" value="'.$val.'">');
		}
	}else{
		$val = $_POST[$name[0]];
		if( $val != "" ){
			echo('<span class="conf">■</span>');
			echo('<input name="'.$name[0].'" type="hidden" value="'.$val.'">');
		}else{
			echo('<span class="conf">□</span>');
			echo('<input name="'.$name[0].'" type="hidden" value="'.$val.'">');
		}
	}
}


// フォーム入力確認関数（イメージ）
function Conf_Post_Img($name,$num){
	$tmp_name = $_FILES[$name]['tmp_name'];
	$up_name = $_FILES[$name]['name'];
	$pos = strrpos($up_name,".");//拡張子取得
	$ext = substr($up_name,$pos+1,strlen($up_name)-$pos);
	$ext = strtolower($ext);//小文字化
	$new_name = date("YmdHis").$num.".".$ext;
	if( $ext == "jpg" || $ext == "gif" ){
		$res=move_uploaded_file($tmp_name,"./tmp/".$new_name);
		echo('<input name="'.$name.'" type="hidden" value="'.$new_name.'">');
		if(is_file("./tmp/".$new_name)){
			list($width, $height, $type, $attr) = getimagesize("./tmp/".$new_name);
		}
		if($width > 600){
			$width = 600;
		}
		echo('<img src="./tmp/'.$new_name.'" width="'.$width.'" height="'.$height.'">');
	}else{
		echo('<input name="'.$name.'" type="hidden" value="">');
		echo('画像無し');
	}
}


// フォーム入力確認関数（ファイル）
function Conf_Post_File($name,$num){
	$tmp_name = $_FILES[$name]['tmp_name'];
	$up_name = $_FILES[$name]['name'];
	$pos = strrpos($up_name,".");//拡張子取得
	$ext = substr($up_name,$pos+1,strlen($up_name)-$pos);
	$ext = strtolower($ext);//小文字化
	$new_name = date("YmdHis").$num.".".$ext;
	if($ext == "swf"){
		move_uploaded_file($tmp_name,"./tmp/".$new_name);
		echo('<input name="'.$name.'" type="hidden" value="'.$new_name.'">');
		echo('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="180" height="180">');
		echo('<param name="movie" value="./tmp/'.$new_name.'">');
		echo('<param name="quality" value="high">');
		echo('<embed src="./tmp/'.$new_name.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="180" height="180"></embed>');
		echo('</object>');
	}else{
		echo('<input name="'.$name.'" type="hidden" value="">');
		echo('ファイル無し');
	}
}

// フォーム入力確認関数（リスト）
function Conf_Post_List($name,$array){
	$name = explode(":",$name);
	if($name[0] != "" && $name[1] != ""){
		$f_name = $name[0];
		$val = $_POST[$name[0]][$name[1]];
	}else{
		$val = $_POST[$name[0]];
		$f_name = $name[0];
	}
	if(is_array($array)){
		if($name[0] != "" && $name[1] != ""){
			echo('<input name="'.$f_name.'['.$name[1].']" type="hidden" value="'.rawurlencode($val).'">');
			echo('<span class="conf">'.View_Cook($array[$val]).'&nbsp;</span>');
		}else{
			echo('<input name="'.$f_name.'" type="hidden" value="'.rawurlencode($val).'">');
			echo('<span class="conf">'.View_Cook($array[$val]).'&nbsp;</span>');
		}
	}else{
		echo('<input name="'.$f_name.'" type="hidden" value="">');
		echo('&nbsp;');
	}
}

// フォーム入力確認関数（イメージ）
function Edit_Conf_Post_Img($name,$num,$dir){
	// 削除フラグチェック
	$name_del = $name."_delete";
	$name_now = $name."_now";
	if( $_POST[$name_del] == "DELETE" ){
		echo('<input name="'.$name_del.'" type="hidden" value="DELETE">');
		echo('<input name="'.$name.'" type="hidden" value="">');
		echo('画像無し');
	}elseif($_FILES[$name]['tmp_name'] != ""){
		Conf_Post_Img($name,$num);
		echo('<input name="'.$name_del.'" type="hidden" value="">');
	}elseif($_POST[$name_now] != ""){
		if(is_file($dir.$_POST[$name_now])){
			list($width, $height, $type, $attr) = getimagesize($dir.$_POST[$name_now]);
		}
		if($width > 600){
			$width = 600;
		}
		echo('<img src="'.$dir.$_POST[$name_now].'" width="'.$width.'">');
		echo('<input name="'.$name_del.'" type="hidden" value="">');
		echo('<input name="'.$name.'" type="hidden" value="">');
	}else{
		echo('画像無し');
		echo('<input name="'.$name_del.'" type="hidden" value="">');
		echo('<input name="'.$name.'" type="hidden" value="">');
	}
	echo('<input name="'.$name_now.'" type="hidden" value="'.$_POST[$name_now].'">');
}


// フォーム入力確認関数（イメージ or FLASH）
function Edit_Conf_Post_File($name,$num,$dir){
	global $gRoot_Url;
	// ファイルの種類判別
	if($_FILES[$name]['name'] != ""){
		$up_name = $_FILES[$name]['name'];
		$pos = strrpos($up_name,".");//拡張子取得
		$ext = substr($up_name,$pos+1,strlen($up_name)-$pos);
		$ext = strtolower($ext);//小文字化
	}else{
		$name_now = $name."_now";
		$pos = strrpos($_POST[$name_now],".");//拡張子取得
		$ext = substr($_POST[$name_now],$pos+1,strlen($_POST[$name_now])-$pos);
		$ext = strtolower($ext);//小文字化
	}
	
	if($ext == "jpg" || $ext == "gif"){
		Edit_Conf_Post_Img($name,$num,$dir);
	}else{
		// 削除フラグチェック
		$name_del = $name."_delete";
		$name_now = $name."_now";
		if( $_POST[$name_del] == "DELETE" ){
			echo('<input name="'.$name_del.'" type="hidden" value="DELETE">');
			echo('<input name="'.$name.'" type="hidden" value="">');
			echo('ファイル無し');
		}elseif($_FILES[$name]['tmp_name'] != ""){
			Conf_Post_File($name,$num);
			echo('<input name="'.$name_del.'" type="hidden" value="">');
		}elseif($_POST[$name_now] != ""){
			if($ext == "swf"){
				echo('<input name="'.$name.'" type="hidden" value="'.$_POST[$name_now].'">');
				echo('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="180" height="180">');
				echo('<param name="movie" value="'.$gRoot_Url.'img/upload/banner/'.$_POST[$name_now].'">');
				echo('<param name="quality" value="high">');
				echo('<embed src="'.$gRoot_Url.'img/upload/banner/'.$_POST[$name_now].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="180" height="180"></embed>');
				echo('</object>');
			}
			echo('<input name="'.$name_del.'" type="hidden" value="">');
			echo('<input name="'.$name.'" type="hidden" value="">');
		}else{
			echo('ファイル無し');
			echo('<input name="'.$name_del.'" type="hidden" value="">');
			echo('<input name="'.$name.'" type="hidden" value="">');
		}
		echo('<input name="'.$name_now.'" type="hidden" value="'.$_POST[$name_now].'">');
	}
}


// エラー表示関数
function Error_End($error_msg){
	echo "<TABLE BORDER=\"0\" ALIGN=\"CENTER\" CELLPADDING=\"3\">\n";
	echo "<TR><TD ALIGN=\"CENTER\" BGCOLOR=\"#CC3300\"> \n";
	echo "<B><FONT COLOR=\"#FFFFFF\">エラー発生！！</FONT></B>\n";
	echo "</TD></TR><TR><TD>\n";
	echo $error_msg;
	echo "</TD></TR>\n";
	echo "<TR><TD><BR>";
	echo "<A href='javascript:history.back();'>＜＜戻る</A></TD></TR>\n";
	echo "</TD></TR></TABLE>\n";
	exit;
}


// 入力データの数字チェック
function Che_Num( $val ){
	$val = mb_convert_kana($val, "a", "UTF-8");
	if( is_numeric( $val ) ){
		if( $val < 0 ){
			$val = 0;
		}
	}else{
		$val = 0;
	}
	return $val;
}

// 0～9の連続した文字のみtrueを返す
function Che_Num2( $val ){
	$val = mb_convert_kana($val, "a", "UTF-8");
	if( is_numeric( $val ) ){
		if(preg_match("/^[0-9]+$/", $val)){
			return true;
		}
	}else{
		return false;
	}
}

// 0～9の連続した文字のみ値を返す(先頭に0があったら取り除く)
function Che_Num3( $val ){
	$val = mb_convert_kana($val, "a", "UTF-8");	
	// 0より小さかったらそのまま返す
	if ($val <= 0){
		return $val;
	}
	if( is_numeric( $val ) ){
		if(preg_match("/^[0-9]+$/", $val)){
			// 先頭の0を削除する
			$val = $val+0;
			return $val;
		}
	}else{
		return false;
	}
}

// 大文字のカンマを置き換える（対象文字「、」「，」「。」「.」「．」）
function Comma( $str ){
	$str = mb_convert_kana($str, "a", "UTF-8");
	if( $str<>'' ){
		$str = str_replace('，',',',$str);
		$str = str_replace('、',',',$str);
		$str = str_replace('。',',',$str);
		$str = str_replace('.',',',$str);
		$str = str_replace('．',',',$str);
	}
	return $str;
}

// セレクトリストの再セット関数(VAL)
function Reset_Select_Val( $ar , $target ){
	foreach( $ar as $val ){
		if( $val == $target ) echo( "\n".'<option value="'.$val.'" selected>'.$val.'</option>' );
		else echo( "\n".'<option value="'.$val.'">'.$val.'</option>' );
	}
}

// セレクトリストの再セット関数(KEY)
function Reset_Select_Key( $ar , $target ){
	foreach( $ar as $key => $val ){
		if( $key == $target ){
			echo( "\n".'<option value="'.$key.'" selected>'.$val.'</option>' );
		}else{
			echo( "\n".'<option value="'.$key.'">'.$val.'</option>' );
		}
	}
}

// セレクトリストの再セット関数(KEY)
function Reset_Select_Key2( $ar , $target ){
	foreach( $ar as $key => $val ){
		if( $key == $target ){
			echo( '<option value="'.$key.'" selected>'.$val.'</option>' );
		}else{
			echo( '<option value="'.$key.'">'.$val.'</option>' );
		}
	}
}

// セレクトリストの再セット関数(KEY)
function Reset_Select_Name( $ar , $target ){
	foreach( $ar as $key => $val ){
		if( $val == $target ){
			echo( "\n".'<option value="'.$val.'" selected>'.$val.'</option>' );
		}else{
			echo( "\n".'<option value="'.$val.'">'.$val.'</option>' );
		}
	}
}

// セレクトリストの再セット関数(KEY)
function Reset_Select_Key_Group( $ar , $target ,$group){
	foreach($group as $keyword => $group_name){
	  echo '<optgroup label="'.$group_name.'">';	
	  foreach( $ar as $key => $val ){
		if(strstr($val, $keyword)){
			if( $key == $target ){
				echo( "\n".'<option value="'.$key.'" selected>'.$val.'</option>' );
			}else{
				echo( "\n".'<option value="'.$key.'">'.$val.'</option>' );
			}
		}
	  }
	  echo '</optgroup>';
	}
}

// セレクトリストの再セット関数(KEY)
function Reset_Select_Array_Group( $ar , $target ,$group){
	echo '<option></option>';
	foreach($group as $id => $group_name){
	  echo '<optgroup label="'.$group_name.'">';	

	  foreach( $ar[$id] as $key => $val ){
		if( $key == $target ){
			echo( "\n".'<option value="'.$key.'" selected>'.$val.'</option>' );
		}else{
			echo( "\n".'<option value="'.$key.'">'.$val.'</option>' );
		}
	  }
	  echo '</optgroup>';
	}
}


// フォーム（POST）データの入力チェック（select用）
function Form_Check_Select( $name , $not_chara , $error_msg ){
	$rtn = array();
	if( $_POST[$name] != "" && $_POST[$name] != $not_chara ){
		$rtn['input'] .= View_Cook( $_POST[$name] );
		$rtn['hidden'] .= '<input name="'.$name.'" type="hidden" value="'.Hide_Cook( $_POST[$name] ).'">';
		$rtn['flg'] = 0;
	}else{
		$rtn['error'] .= View_Cook( $error_msg );
		$rtn['flg'] = 1;
	}
	return $rtn;
}


// フォーム（POST）データの入力チェック（半角英数字用）
function Form_Check_Hankaku( $name , $indispen , $error_msg ){
	$rtn = array();
	$_POST[$name] = mb_convert_kana( $_POST[$name] , "a" );
	if( $_POST[$name] == "" && $indispen == 1 ){
		$rtn['error'] .= View_Cook( $error_msg );
		$rtn['flg'] = 1;
	}else{
		$rtn['input'] .= View_Cook( $_POST[$name] );
		$rtn['hidden'] .= '<input name="'.$name.'" type="hidden" value="'.Hide_Cook( $_POST[$name] ).'">';
		$rtn['flg'] = 0;
	}
	return $rtn;
}


// フォーム（POST）データの入力チェック（全角文字用）
function Form_Check_Text( $name , $indispen , $error_msg ){
	$rtn = array();
	$_POST[$name] = mb_convert_kana( $_POST[$name] , "K" );
	if( $_POST[$name] == "" && $indispen == 1 ){
		$rtn['error'] .= View_Cook( $error_msg );
		$rtn['flg'] = 1;
	}else{
		$rtn['input'] .= View_Cook( $_POST[$name] );
		$rtn['hidden'] .= '<input name="'.$name.'" type="hidden" value="'.Hide_Cook( $_POST[$name] ).'">';
		$rtn['flg'] = 0;
	}
	return $rtn;
}


// フォーム（POST）データの入力チェック（半角数字用）
function Form_Check_Num( $name , $indispen , $error_msg ){
	$rtn = array();
	$_POST[$name] = mb_convert_kana( $_POST[$name] , "a" );
	if( $indispen == 1 ){
		if( $_POST[$name] != "" && is_numeric( $_POST[$name] ) ){
			$rtn['input'] .= View_Cook( $_POST[$name] );
			$rtn['hidden'] .= '<input name="'.$name.'" type="hidden" value="'.Hide_Cook( $_POST[$name] ).'">';
			$rtn['flg'] = 0;
		}else{
			$rtn['error'] .= View_Cook( $error_msg );
			$rtn['flg'] = 1;
		}
	}else{
		$rtn['flg'] = 0;
	}
	return $rtn;
}


// フォーム（POST）データの入力チェック（メール用）
function Form_Check_Email( $name , $indispen , $error_msg ){
	$rtn = array();
	$_POST[$name] = mb_convert_kana( $_POST[$name] , "a" );
	if( $indispen == 1 ){
		if( $_POST[$name] != "" && preg_match( "/[\w\d\-\.]+\@[\w\d\-\.]+/" , $_POST[$name] ) ){
			$rtn['input'] .= View_Cook( $_POST[$name] );
			$rtn['hidden'] .= '<input name="'.$name.'" type="hidden" value="'.Hide_Cook( $_POST[$name] ).'">';
			$rtn['flg'] = 0;
		}else{
			$rtn['error'] .= View_Cook( $error_msg );
			$rtn['flg'] = 1;
		}
	}else{
		$rtn['flg'] = 0;
	}
	return $rtn;
}


function Check_Email($email) {
    $email = trim($email);
    if (!empty($email)){
        if (!preg_match('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
        '@'.
        '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
        '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) 
        {
            $error_msg = '不正なメールアドレスです。';
        } 
    } else {
        $error_msg = "宛先が未入力です。";
    }
    return $error_msg ;
}

function Check_Email2($email) {

	// 入力可能(～.拡張子)リスト
	$preg_str = array(
		"ac", "ac.uk", "ac.za", "ad", "ae", "aero", "af", 
		"ag", "ai", "al", "am", "an", "ao", "aq",	"ar", 
		"arpa", "as", "asia", "asn.au", "at", "au", "aw", 
		"ax", "az", "ba", "bb", "bd", "be", "bf", "bg", 
		"bh", "bi", "biz", "bj", "bl", "bm", "bn", "bo", 
		"br", "br.com", "bs", "bt", "bv", "bw", "by", "bz", 
		"ca", "cat", "cc", "cd", "cf", "cg", "ch", "ci", 
		"ck", "cl", "cm", "cn", "cn.com", "co", "co.za", 
		"com", "com.au", "com.uy", "coop", "cr", "cs", 
		"cu", "cv", "cx", "cy", "cz", "de", "de.com", "dj", 
		"dk", "dm", "do", "dz", "ec", "edu", "edu.cn", 
		"ee", "eg", "eh", "er", "es", "et", "eu", "eu.com", 
		"eu.org", "fed.us", "fi", "fj", "fk", "fm", "fo", 
		"fr", "fx", "ga", "gb", "gb.com", "gb.net", "gd", 
		"ge", "gf", "gg", "gh", "gi", "gl", "gm", "gn", 
		"gov", "gov.uk", "gp", "gq", "gr", "gs", "gt", "gu", 
		"gw", "gy", "hk", "hm", "hn", "hr", "ht", "hu", 
		"hu.com", "id", "id.au", "ie", "il", "im", "in", 
		"info", "int", "io", "iq", "ir", "is", "it", "je", 
		"jm", "jo", "jobs", "jp", "jpn.com", "ke", "kg", 
		"kh", "ki", "km", "kn", "kp", "kr", "kw", "ky", 
		"kz", "la", "lb", "lc", "li", "lk", "lr", "ls", "lt", 
		"lu", "lv", "ly", "ma", "mc", "md", "me", "mf", 
		"mg", "mh", "mil", "mk", "ml", "mm", "mn", "mo", 
		"mobi", "mp", "mq", "mr", "ms", "mt", "mu", "museum", 
		"mv", "mw", "mx", "my", "mz", "na", "name", 
		"nc", "ne", "net", "net.au", "nf", "ng", "ni", 
		"nl", "no", "no.com", "np", "nr", "nu", "nz", "om", 
		"org", "org.au", "org.za", "pa", "pe", "pf", "pg", 
		"ph", "pk", "pl", "pm", "pn", "pr", "pro", "ps", 
		"pt", "pw", "py", "qa", "qc.com", "re", "ro", 
		"ru", "ru.com", "rw", "sa", "sa.com", "sb", "sc", 
		"sd", "se", "se.com", "se.net", "sg", "sh", "si", 
		"sj", "sk", "sl", "sm", "sn", "so", "sr", "st", 
		"su", "sv", "sy", "sz", "tc", "td", "tel", "tf", 
		"tg", "th", "tj", "tk", "tl", "tm", "tn", "to", 
		"tp", "tr", "travel", "tt", "tv", "tw", "tz", 
		"ua", "ug", "uk", "uk.com", "uk.net", "um", "us", 
		"us.com", "uy", "uy.com", "uz", "va", "vc", "ve", 
		"vg", "vi", "vn", "vu", "web.com", "wf", "ws", 
		"ye", "yt", "yu", "za", "za.com", "zm", "zr", "zw"
	);

    $miss_str = array("@i.softbank.jp" 	=> array("@softbank.jp",		"@Softbank.jp",		"@i.soetbank.jp",	"@i.softbank.ne.jp","@i.sftbank.jp",	"@isoftbank.jp",	"@i.softbant.jp",	"@i.softbank.ac.jp","@i.softbnk.jp",	"@i.softobanku.jp",	"@I.softbauk.jp",	"@i.sootbank.jp",	"@i.sofabank.jp",	"@i.softaank.jp",	"@i-softbank.jp","@i.softbakn.jp",	"@i.softbank.jo",	"@i.softbankjp",	"@i.softbakn.jp",	"@i.softbank.co.jp","@i.sodntbank.jp","@i.softbaknk.jp","@i.softbank.jp","@isoftbank.jp","@softbank.com","@softbank.i.jp","@8.softbank.jp","@i.siftbank.jp","@softbsnk.jp","@i.softbamk.jp","@i.softbank.com","@i.softbbnk.jp","@i.softank.jp","@is.oftbank.jp","@g.softbank.jp","@i.softbaok.jp","@i.softbabk.jp","@i.softbank..jp","@i.softbanl.jp","@i.sofbank.jp","@i.softbanku.jp","@i.sotbank.jp","@i.softba.k.jo","@i.softbank.kp","@i.smftbank.jp","@i.softbakn.jp","@i.softdank.jp","@i.softbsnk.jp","@i.softbannk.jp","@i.softdank.jp","@i.softban.jp","@i.sohutobanku.jp","@i.sofibank.jp","@i.sofetbank.jp","@i.softbbank.jp","@is.softbank.jp","@i.softbanj.jp","@i.softbank.ne","@i.sofutbank.jp","@i.softabank.jp","@i.sortbank.jp","@isoftbank.ne.jp","@i.softobank.jp","@l.softbank.jp","@i.softbankne.jp","@i.sodtbank.jp","@i.softjank.jp","@i.snftbank.jp","@i.sohtbank.jp","@i.sdftbank.jp","@i.sofrbank.jp","@l.Softbank.jp","@i.softbak.jp","@i.softbankk.jp"),
					  "@softbank.ne.jp" => array("@softbank.ne.jo",		"@sofbank.ne.jp",	"@softbank.co.jp",	"@softbank.ne.kp",	"@siftbank.ne.jp",	"@softbank.nd.jp",	"@sftbank.ne.jp",	"@sotbank.ne.jp",	"@softdank.ne.jp",	"@softbnk.ne.jp",	"@softbank.ne.tp",	"@sodtbank.ne.jp","@sotfbank.ne.jp","@softbank.me.jp","@softbank.nej.jp","@softaank.ne.jp","@sotftbank.ne.jp","@sotftbank.ne.jp","@softobank.ne.jp","@softank.ne.jp","@softbbnk.ne.jp","@softbak.ne.jp","@softban.ne.jp","@softbamk.ne.jp","@softtbank.ne.jp","@softbankne.jp","@softbank.ne","@soft.bank.ne.jp","@softbanku.ne.jp","@softbank.e.jp","@softbannk.ne.jp","@sohutobank.ne.jp", "@soetbank.ne.jp","@softbqnk.ne.jp"),
					  "@ezweb.ne.jp" 	=> array("@qezueb.ne.jp",		"@qzweb.ne.jp",		"@ezmeb.ne.jp",		"@ez.ne.jp",		"@ezwb.ne.jo",		"@ezweb.jp",		"@ezwed.ne.jp",		"@ezwb.ne.jp",		"@ezweb.ne.ne.jp",	"@ezwebe.ne.jp",	"@esweb.ne.jp",		"@zeweb.ne.jp",		"@ezweb.nr.jp","@ezweb.co.jp","@ezeb.ne.jp","@ezweb.ne.kp","@ezweb.ke.jp","@ezwe.ne.jp","@ezwab.ne.jp","@ezwebw.ne.jp","@ezwebne.jp","@ezweb.ne","@ezweb._e.jp","@ezxeb.ne.jp","@wzweb.ne.jp","@eyweb.ne.jp","zweb.ne.jp","@ez_w_eb.ne_.jp","@ezweb-ne.jp","@ezweb.ne.ne.jp","@ez_w_eb.ne_.jp","@ezweb.no.jp","@ewweb.ne.jp","@eweb.ne.jp","@exweb.ne.jp","@ezeweb.ne.jp","@i.ezweb.jp","@ezweb.nb.jp","@ezweb.nx.jp","@ezerb.ne.jp","@ezwnb.ne.jp","@ezb.ne.jp","@edweb.ne.jp","@ezweb.nd.jp","@ezeeb.ne.jp","@ezweb.ne.p","@ezweb.me.jp","@ezwb.ne.jp","@ezwne.ne.jp","@ewzwb.ne.jp","@exweb.jp","@ezwev.ne.jp","@ezweb.nk.jp","@ezweb.na.jp","@ezwrb.ne.jp","@ezweb.od.jp","@ez.web.ne.jp","@egweb.ne.jp","@ez.web.jp","@ezwea.ne.jp","@ezwen.ne.jp","@ez.web.co.jp","@ezewb.ne.jp"),
					  "@docomo.ne.jp" 	=> array("@docmo.ne.jp",		"@docimo.ne.jp",	"@docomo.ne.jo",	"@docpmo.ne.jp",	"@docom.ne.jp",		"@dokomo.ne.jp",	"@docomo.ne.jg",	"@docmo.ne.jp",		"@docomo.me.jp",	"@docomo.na.jp",	"@docono.ne.jp",	"@docomp.ne.jp",	"@docoomo.ne.jp","@docomo.ne.jp.ne.jp","@docomone.jp","@dpcomo.ne.jp","@docomo.ne","@docomoo.ne.jp","@docono.co.jp","@dokomo.ne.jp","@docomo.np.jp","@bocomo.ne.jp","@docomi.ne.jp","@dogomo.ne.jp","@docomo.ne.jp.jp","@docomo.jp","@dmcomo.ne.jp","@do.co.mo.ne.jp","@docomo.be.jp","@do.co.mo.ne.jp","@docomo.e.jp","@dicomo.ne.jp","@docomo.no.jp","@dcomo.ne.jp","@i.docomo.ne.jp","@dovomo.ne.jp","@socmo.ne.jp","@dacomo.ne.jp","@docomo.ac.jp",		"@docomo.nr.jp","@socomo.ne.jp"),
					  "@gmail.com" 		=> array("@gmal.com",			"@g-mail.com",		"@gmail.com.jp",	"@gmal.com",		"@gmaill.com",		"@gmil.com", 		"@gmail.ne.jp",		"@gamil.com",		"@gmeil.com",		"@gmail.co",		"@gail.com",		"@gmaol.com",		"@gamail.com","@gnail.com","@gmaiil.com","@gmail.jp","@gmaul.com","@gmail.om","@gmail.co.jp","@gmaio.com","@gmdil.com","@gmaik.com","@gmaii.com","@gimail.com","@gmsil.com","@gmai.com","@g.mail.com","@g.gmail.com","@gmail.ne","@gmil.cm", "@gmial.com", "@gmeil.ne.jp", "@jmail.com", "@gmail.cm"),
					  "@icloud.com" 	=> array("@cloud.com",			"@i.cloud.com",		"@icloud.ne.jp",	"@icluud.com",		"@iclou.com",		"@icoud.co.jp",		"@icloud.co.jp",	"@icioud.com",		"@icloud.jp",		"@iclub.com",		"@iconud.com",		"@icoud.com",		"@icloud.co","@iclond.com","@iclud.com","@iclouf.com","@i.cloud.ne.jp","@icolud.com","@I.Cloud.com","@icould.com","@icouid.com", "@iclooud.com", "@icroud.com", "@iciud.com", "@cloud.com"),
					  "@yahoo.co.jp" 	=> array("@yahoo.co.jo",		"@yahoo.co.jg",		"@yahon.co.jp",		"@yaho.co.jp",		"@yhoo.co.jp" ,		"@yahoo.cp.jp",		"@yahooo.co.jp",	"@yahooco.jp",		"@yaoo.co.jp",		"@yahoi.co.jp",		"@yahof.co.jp",		"@ya.hoo.co.jp",	"@yohoo.co.jp","@yahpp.co.jp","@yhaoo.co.jp","@yah-oo.co.jp","@uahoo.co.jp","@yahon.ne.jp","@yshoo.co.jp","@yahohoo.co.jp","@yahoo.com.jp"),
					  "@hotmail.co.jp" 	=> array("@hotmaio.co.jp",		"@hotmail.co.jo",	"@hotmaio.co.jo",	"@hotmaill.co.jp",	"@hotamil.com",		"@hotmil.com",		"@hotmail.ne.jp")
				);

    $rtn['flg'] = 0;
    $email = trim($email);
    list($email1,$email2) = explode("@",$email);
    //スペルミス訂正
    foreach($miss_str as $key =>$val){
    	foreach($val as $sub_key =>$sub_val){
    		if(strstr($email,$sub_val)){
    			//$email = $_POST['mail'] = str_replace($sub_val,$key,$email);
    			$email = $_POST['mail'] = $email1.$key;
    			break;
    		}
    	}
    }

    if (empty($email)){
		$rtn['error'] .= "メールアドレスを入力してください";
		$rtn['flg'] = 1;

	// "/^([a-zA-Z0-9\])===>"/^([a-zA-Z0-9\._-]) に変更 2017/07/10 add by shimada
	} else if (!preg_match("/^([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
		$rtn['error'] .= "メールアドレスは正しい形式で入力してください";
		$rtn['flg'] = 1;		

	} else if ( strstr($email,"ne.jo") || strstr($email,"co.jo") ) {
		$rtn['error'] .= "メールアドレスのドメインが正しくありません";
		$rtn['flg'] = 1;
	} else {
		$preg_str = '\'/\.' . implode('$|\.', $preg_str) . '$/i\'';

		if (!preg_match($preg_str, $email)) {
			$rtn['error'] .= "メールアドレスの書式が正しくありません";
			$rtn['flg'] = 1;
		}
	}

    return $rtn;
}

// フォーム（POST）データの入力チェック（エラー用）
function Input_Check_Result( $res , $print_flg ){
	if( $res['flg'] == 0 ){
		if( $print_flg ){
			echo( $res['input'] );
			echo( $res['hidden'] );
		}
	}else{
		if( $print_flg ){
			echo( '<font color="'.FORM_EM_COLOR.'">'.$res['error']."</font>\n" );
		}elseif( $res['error'] != "" ){
			return( '<font color="'.FORM_EM_COLOR.'">●'.$res['error']."</font><br>\n" );
		}
	}
}


/**************************************************
* フォーム部品生成
**************************************************/

// ページ繰り　リストの表示
function Print_List_Menu1( $start , $line_max , $get_cnt ){
	echo( 'ヒット件数：'.$get_cnt.'件　' );
	$page_cnt = ceil( $get_cnt / $line_max );
	$now_page = $start / $line_max + 1;
	if( $now_page > 1 ){
		echo( '<a href="javascript:submit_search('.( $start - $line_max ).');">＜前</a>　' );
	}
	for( $cnt = 0; $cnt < $page_cnt; $cnt++ ){
		if( $cnt == ( $start / $line_max ) ){
			echo( '<font color="#999999"> '.( $cnt + 1 ).'</font>' );
		}else{
			echo( ' <b><a href="javascript:submit_search('.( $cnt * $line_max ).');">'.( $cnt + 1 ).'</a></b>' );
		}
	} 
	if( $now_page < $page_cnt ){
		echo( '　<a href="javascript:submit_search('.( $start + $line_max ).');">次＞</a>' );
	}
}
// ページリンクの生成
function Print_List_Menu( $sStart , $sMax , $DataCnt ) {
	
	$DataCnt < $sStart and $sStart = 0;
	$pMax = 4;
	$sLast = ( $sStart + $sMax > $DataCnt ? $DataCnt : $sStart + $sMax );
	echo 'ヒット件数：' . $DataCnt . '件　';
	if ( $DataCnt == 0 ) return $page;
	
	//$DataCnt > 0 and echo '( ' . ( $sStart + 1 ) . '～' . $sLast . '件目を表示 )　';
	$page_cnt = ceil( $DataCnt / $sMax );
	$now_page = $sStart / $sMax + 1;
	
	if ( $now_page > 1 ) {
		echo '<a href="javascript:submit_search(' . ( $sStart - $sMax ) . ');" style="color:#94b52c;">＜前</a>';
	} else {
		echo '<font color="#999999">＜前</font>';
	}
	
	$pStart = $now_page - 3;
	$pStart < 0 and $pStart = 0;
	
	$pLast = $now_page + $pMax - 2;
	$pLast - $pStart < 5 and $pLast = $pStart + 5;
	$pLast > $page_cnt and $pLast = $page_cnt;
	
	$pLast - $pStart < 5 and $pStart > 0 and $page_cnt > 5 and $pStart = $pLast - 5;
	
	for ( $i=$pStart; $i<$pLast; $i++ ) {
		if ( $i == ( $sStart / $sMax ) ) {
			echo '<font color="#999999"> ' . ( $i + 1 ) . '</font>';
		} else {
			echo ' <b><a href="javascript:submit_search(' . ( $i * $sMax ) . ');" style="color:#94b52c;">' . ( $i + 1 ) . '</a></b>';
		}
	}
	
	if ( $now_page < $page_cnt ) {
		echo '　<a href="javascript:submit_search(' . ( $sStart + $sMax ) . ');" style="color:#94b52c;">次＞</a>';
	} else {
		echo '　<font color="#999999">次＞</font>';
	}

}

/**
 * <input type="radio">タグ生成
 */
function InputRadioTag( $name, $values, $checked, $glue="" )
{
	if ( !is_array( $values ) ) return '<input type="radio" name="'. $name. '" checked>'. $label;
	foreach ( $values as $val => $label ) {
		$tag[] = ' <label><input type="radio" name="'. $name. '" value="'. $val. '"'
		. ( $val == $checked ? ' checked' : '' ). ' /> '. $label. '</label>';
	}
	return join( $glue, $tag );
}

/**
 * <input type="radio">タグ生成
 * class =任意指定する
 * id = $name+$val
 */
function InputRadioClassTag( $name, $values, $checked, $glue="",$class )
{
	if ( !is_array( $values ) ) return '<input type="radio" name="'. $name. '" checked>'. $label;
	foreach ( $values as $val => $label ) {
		$tag[] = ' <label><input type="radio" name="'. $name. '" value="'. $val. '" class="'. $class. '" id="'. $name.$val. '"'
		. ( $val == $checked ? ' checked' : '' ). ' /> '. $label . '</label>';
	}
	return join( $glue, $tag );
}

/**
 * <input type="radio">タグ生成
 */
function InputRadioLabelTag( $name, $values, $checked, $glue="" )
{
	if ( !is_array( $values ) ) return '<input type="radio" name="'. $name. '" checked>'. $label;
	foreach ( $values as $val => $label ) {
		$tag[] = ' <label><input type="radio" name="'. $name. '" value="'. $val. '"'
		. ( $val == $checked ? ' checked' : '' ). ' /> '. $label . '</label>';
	}
	return join( $glue, $tag );
}
	
/**
 * <input type="checkbox">タグ生成
 */
function InputCheckboxTag( $name, $values, $checked, $glue='', $wrap = false )
{
	$count = 0;
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
			.  ' <p><label><input type="checkbox" name="'. $name. '[]" value="'. $val. '"'
			. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ). ' /> '. $val."</label></p>";
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 */
function InputCheckboxTag2( $name, $values, $checked, $glue='', $wrap = false )
{
	$count = 0;
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
			.  ' <label><input type="checkbox" name="'. $name. '[]" value="'. $val. '"'
			. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ). ' /> '. $val. '</label>';
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成(配列から取得した値をvalueとしてキーにいれる)
 */
function InputCheckboxTagKey( $name, $values, $checked, $glue='', $wrap = false)
{
	// チェック済みの項目
	$c_green = "c_green";
	$checked_array = explode(',', $checked);
	
	$count = 0;
	foreach ( $values as $val ) {
		$checked_string = in_array(array_search($val, $values),$checked_array)==true  ? $c_green : "" ;
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
			.  ' <label class=' .$checked_string. '><input type="checkbox" name="'. $name. '[]" value="'. array_search($val, $values). '"'
			. ( in_array(array_search($val, $values),$checked_array)  ? ' checked="checked"' : '' ). ' /> '. $val . '</label>';
		$count++;

	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 */
function InputCheckboxTag3( $name, $values, $checked, $glue='', $wrap = false )
{
	$count = 0;
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
			.  ' <p class="checkbox"><label><input type="checkbox" name="'. $name. '[]" value="'. $val. '"'
			. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ). ' /> '. $val."</label></p>";
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 */
function InputCheckboxTag4( $name, $values, $checked, $glue='', $wrap = false ,$onchange='')
{
	$count = 0;
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
			.  ' <p><label><input type="checkbox" name="'. $name. '['.$count.']" value="'. $val. '"'
			. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ).$onchange. ' /> '. $val."</label></p>";
		$count++;
	}
	return join( $glue, $tag );
}


/**
 * <input type="checkbox">タグ生成
 * $swap 配列を指定の数で折り返したいとき設定
 * 例）3  表示） 配列1,配列2,配列3
 *            配列4,配列5
 */
function InputCheckboxTag5( $name, $values, $checked, $glue='', $wrap = false ,$onchange='')
{
	$count = 0;
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
		.  ' <label><input type="checkbox" name="'. $name. '['.$count.']" value="'. $val. '"'
		. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ).$onchange. ' /> '. $val. '</label>';
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 * $swap 配列を指定の数で折り返したいとき設定
 * $start $end  配列を$startから何個($num個) 指定して取り出す
 */
function InputCheckboxTag6( $name, $values, $checked, $glue='', $wrap = false ,$onchange='',$start,$num)
{
	$count = 0;
	$values = array_slice($values,$start,$num);
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
		.  ' <label><input type="checkbox" name="'. $name. '['.$count.']" value="'. $val. '"'
		. ( strstr( $checked, $val ) ? ' checked="checked"' : '' ).$onchange. ' /> '. $val. '</label>';
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 * $swap 配列を指定の数で折り返したいとき設定
 * $checked  文字列を分割して配列で返す
 */
function InputCheckboxTagKeyFold( $name, $values, $checked, $glue='', $wrap = false ,$onchange='',$start,$num)
{
	// チェック済みの項目
	$c_green = "c_green";
	$checked_array = explode(',', $checked);

	$count = 0;
	$values = array_slice($values,$start,$num,true);// キーは保持する
	foreach ( $values as $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
		.  ' <label><input type="checkbox" name="'. $name. '['.$count.']" value="'. array_search($val, $values). '"'
		. ( in_array(array_search($val, $values),$checked_array)  ? ' checked="checked"' : '' ). ' /> '. $val . '</label>';
		$count++;
	}
	return join( $glue, $tag );
}

/**
 * <input type="checkbox">タグ生成
 * $swap 配列を指定の数で折り返したいとき設定
 * $checked  文字列を分割して配列で返す
 * $start $end  配列を$startから何個($num個) 指定して取り出す
 */
function InputCheckboxTag7( $name, $values, $checked, $glue='', $wrap = false ,$onchange='')
{
	$count = 0;
	$checked_array = explode(",", $checked);
	foreach ( $values as $key => $val ) {
		$tag[] = ( $wrap && $count && ( $count % $wrap == 0 ) ? "<br>\n" : '' )
		.  ' <div style="width:180px;display: inline-block; _display: inline;"><label><input type="checkbox" name="'. $name. '['.$count.']" value="'. $key. '"'
		. ( in_array($key,  $checked_array ) ? ' checked="checked"' : '' ).$onchange. ' /> '. $val. '</label></div>';
		$count++;
	}
	return join( $glue, $tag );
}

// セレクトリスト表示（数値のみ）
function Print_Select_List( $start , $tail , $target ){
	if( $start > $tail ){
		$start = 0;
		$tail = 1;
	}
	for( $cnt = $start; $cnt <= $tail; $cnt++ ){
		if( $cnt == $target ){
			echo( '<option value="'.$cnt.'" selected>'.$cnt.'</option>'."\n" );
		}else{
			echo( '<option value="'.$cnt.'">'.$cnt.'</option>'."\n" );
		}
	}
}


// セレクトリスト表示（配列のキー）
function Print_Select_List3( $box, $target ){
	foreach( $box as $key => $val ){
		if( $key == $target ){
			echo( '<option value="'.$key.'" selected>'.$val.'</option>'."\n" );
		}else{
			echo( '<option value="'.$key.'">'.$val.'</option>'."\n" );
		}
	}
}


// 入力エラー表示
function Error_Msg( $val ){
	$val = '<font color="#FF9933">◆</font><font color="#990000">'.$val."</font><br>\n";
	return $val;
}


// ラジオボタンチェッカー
function Set_Radio_Def( $def,$now ){
	if($def == $now){
		echo(" checked");
	}
}


// 画像アップロード
function Upload_Img( $up_name,$tmp_name,$target_dir,$target_name ){
	$pos = strrpos($up_name,".");//拡張子取得
	$ext = substr($up_name,$pos+1,strlen($up_name)-$pos);
	$ext = strtolower($ext);//小文字化
	$new_name = $target_name.".".$ext;
	if($ext == "jpg" || $ext == "gif" || $ext == "swf"){
		if(move_uploaded_file($tmp_name,$target_dir.$new_name)){
			return $new_name;
		}
		
	}
}


// 現在の画像確認リンク表示
function Link_Now_Img($name,$img_dir){
	if( $name != "" ){
	//if( $name != "" && is_file($img_dir.$name) ){
		echo('　<a href="'.$img_dir.$name.'" target="_blank">現在の画像を確認する</a>');
	}else{
		echo('　現在画像は設定されておりません');
	}
}


// 現在の画像（FLASH含む）確認リンク表示
function Link_Now_File($name,$img_dir){
	if( $name != "" ){
		echo('　<a href="'.$img_dir.$name.'" target="_blank">現在のファイルを確認する</a>');
	}else{
		echo('　現在ファイルは設定されておりません');
	}
}


// データ編集時の画像調整(ファイルも含む)
function Reset_Img_File($name,$dir){
	$name_up = $_POST[$name];
	$name_now = $name."_now";
	$name_now = $_POST[$name_now];
	$name_del = $name."_delete";
	$name_del = $_POST[$name_del];
	if($name_del == "DELETE"){
		$new_name = "";
		Delete_Old_File($name_now,$dir);
		Delete_Old_File($name_up,"./tmp/");
	}elseif($name_up != "" && is_file("./tmp/".$name_up)){
		$new_name = $name_up;
		copy("./tmp/".$name_up,$dir.$name_up);
		Delete_Old_File($name_now,$dir);
		Delete_Old_File($name,"./tmp/");
	}else{
		$new_name = $name_now;
	}
	return $new_name;
}

// $file: 元のファイル名
// $file2: リサイズ後のファイル名
// $max_w: リサイズ後の幅
// $max_h: リサイズ後の高さ
function Resize_Image( $file, $file2, $max_w, $max_h ) {
	// 元の画像サイズ取得
	$size = getimagesize( $file );
	$width = $size[0];
	$height = $size[1];
	
	// 画像形式を判定
	if ( strstr( $size['mime'],'jpg'  ) ) {
		$type = 'jpg';
	} elseif ( strstr( $size['mime'],'jpeg' ) ) {
		$type = 'jpg';
	} elseif ( strstr( $size['mime'],'gif' ) ) {
		$type = 'gif';
	} elseif ( strstr( $size['mime'],'png' ) ) {
		$type = 'png';
	} else {
		return "サポートされていない形式のファイルです。";
	}

	// リサイズ後の大きさを計算
	if ( $width >= $max_w ) { //横長
		$new_height = ceil( ( $max_w/$width ) * $height );
		$new_width = $max_w;
	} elseif ( $height >= $max_h ) { //縦長
		$new_width = ceil( ( $max_h/$height ) * $width );
		$new_height = $max_h;
	} else {
		$new_width = $width;
		$new_height = $height;
	}
	
	// リサイズ後の座標を計算
	$new_x = ceil( ( $max_w-$new_width ) / 2 );
	$new_y = ceil( ( $max_h-$new_height ) / 2 );
	
	// イメージリソース生成
	$new_img = imagecreatetruecolor( $max_w, $max_h );
	switch ( $type ) {
		case 'jpg': $old_img = imagecreatefromjpeg( $file ); break;
		case 'gif': $old_img = imagecreatefromgif( $file ); break;
		case 'png': $old_img = imagecreatefrompng( $file ); break;
	}
	
	// 画像生成
	imagefill( $new_img, 0, 0, 0xFFFFFF );
	imagecopyresampled( $new_img, $old_img, $new_x, $new_y, 0, 0, $new_width, $new_height, $width, $height );
	switch ( $type ) {
		case 'jpg': imagejpeg( $new_img, $file2, 80 ); break;
		case 'gif': imagegif( $new_img, $file2 ); break;
		case 'png': imagepng( $new_img, $file2 ); break;
	}
	
	return true;
}

// ファイルを削除（主に画像削除に使用）
function Delete_Old_File($files,$dir){
	if(is_array($files)){
		foreach($files as $val){
			if(is_file($dir.$val)){
				unlink($dir.$val);
			}
		}
	}else{
		if(is_file($dir.$files)){
			unlink($dir.$files);
		}
	}
	
}


/**************************************************
* DB関連
**************************************************/

// テーブルデータの取得（特定カラム1個）
function Get_Table_Col($table,$col,$where){
	$sql = "SELECT ".$col." FROM ".$table.$where.";";
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn->num_rows >= 1){
		$result = $rtn->fetch_row()[0];
	}else{
		$result = "";
	}
	return $result;
}


// テーブルデータの取得（1行）
function Get_Table_Row($table,$where){

		$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
    	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	$sql = "SELECT * FROM ".$table.$where.";";
	//if($table == "goal") {
		//var_dump($sql);
	//}
	//$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	$rtn = $GLOBALS['mysqldb']->query($sql) ;

	if($rtn){
		$result = $rtn->fetch_assoc();
	}else{
		$result = "";
	}
	return $result;
}

// テーブルデータの取得（1行）KIREIMO用

// function Get_Kireimo_Table_Row($table,$where){
		
// 		$db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
// 		if (!$db_selected){
//     	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
// 		}

// 	$sql = "SELECT * FROM ".$table.$where.";";
// 	//if($table == "goal") {
// 		//var_dump($sql);
// 	//}
// 	//$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
// 	$rtn = $GLOBALS['mysqldb']->query($sql) ;

// 	if($rtn){
// 		$result = $rtn->fetch_assoc();
// 	}else{
// 		$result = "";
// 	}
// 	return $result;
// }

// テーブルデータの取得（配列）
function Get_Table_Array($table,$col,$where){
	$sql = "SELECT ".$col." FROM ".$table.$where.";";
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn != false && $rtn->num_rows >= 1){
		if($col != "*"){
			while($line = $rtn->fetch_assoc()){
				$box[] = $line[$col];
			}
		}else{
			while($line = $rtn->fetch_assoc()){
				$box[] = $line;
			}
		}
	}
	return $box;
}


// テーブルデータの取得（複数指定）
function Get_Table_Array_Multi($table,$col,$where){
	$sql = "SELECT ".$col." FROM ".$table.$where.";";
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn != false && $rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			$box[] = $line;
		}
	}
	return $box;
}


// テーブルデータの取得（SQL直打ち結果：1カラム）
function Get_Result_Sql_Col($sql){
	//var_dump($sql);
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn->num_rows >= 1){
		$result = $rtn->fetch_row();
	}else{
		$result = "";
	}
	return $result;
}


// テーブルデータの取得（SQL直打ち結果：1行）
function Get_Result_Sql_Row($sql){
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn->num_rows >= 1){
		$result = $rtn->fetch_assoc();
	}else{
		$result = "";
	}
	return $result;
}


// テーブルデータの取得（SQL直打ち結果：配列）
function Get_Result_Sql_Array($sql){
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			$box[] = $line;
		}
	}
	return $box;
}


// テーブルデータの取得（配列添え字指定）
function Get_Table_Array_Sub($table,$col,$sub,$where){
	$sql = "SELECT * FROM ".$table.$where.";";
	$rtn = $GLOBALS['mysqldb']->query($sql);
	if($rtn != false && $rtn->num_rows >= 1){
		if($col != "*"){
			while($line = $rtn->fetch_assoc()){
				$box[$line[$sub]] = $line[$col];
			}
		}else{
			while($line = $rtn->fetch_assoc()){
				$box[$line[$sub]] = $line;
			}
		}
		mysql_free_hit($rtn);
	}
	return $box;
}


// テーブルから行削除
function Delete_Table_Row( $table,$col,$key ){
	if( $table != "" && $col != "" && $key != "" ){
		$sql = "DELETE from ".$table." WHERE ".$col." = '".addslashes($key)."';";

		$GLOBALS['mysqldb']->query( $sql );
	}
}


/**************************************************
* コンテンツ関連
**************************************************/

// POSTデータとSESSIONとの相互入れ替え
function Post_To_Session($names,$type){
	foreach($names as $val){
		if($type == "P-S"){
			$_POST[$val] = $_SESSION[$val];
		}else{
			$_SESSION[$val] = $_POST[$val];
		}
	}
}

// チェックボックスのチェック判断＆SQL文の生成
function Make_Sql_Check_Box($names){
	$where = "";
	foreach($names as $val){
		if($_POST[$val] != ""){
			$where .= " AND ".$val." = '1'";
		}
	}
	return $where;
}

// SJISをEUCに
function Convert_SJIS_EUC($names){
	foreach($names as $val){
		$_POST[$val] = mb_convert_encoding($_POST[$val],'EUC-JP','SJIS');
	}
}

// 空白を全角スペースに変更
function null_to_space($data){
	if ($data=='') {
		$res = mb_ereg_replace('','　',$data);
	} else {
		$res = $data;
	}
	return $res;
}

// ページリンクの生成
function setPageLink() {
	global $DataCnt , $sStart , $sMax;
	
	$DataCnt < $sStart and $sStart = 0;
	$pMax = 4;
	$sLast = ( $sStart + $sMax > $DataCnt ? $DataCnt : $sStart + $sMax );
	//$page = 'ヒット件数：' . $DataCnt . '件';
	if ( $DataCnt == 0 ) return $page;
	
	//$DataCnt > 0 and $page .= '( ' . ( $sStart + 1 ) . '～' . $sLast . '件目を表示 )　';
	$page_cnt = ceil( $DataCnt / $sMax );
	$now_page = $sStart / $sMax + 1;
	
	if ( $now_page > 1 ) {
		$page .= '<a href="javascript:submit_search(' . ( $sStart - $sMax ) . ');">＜前へ</a>';
	} else {
		$page .= '<font color="#999999">＜前へ</font>';
	}
	
	$pStart = $now_page - 3;
	$pStart < 0 and $pStart = 0;
	
	$pLast = $now_page + $pMax - 2;
	$pLast - $pStart < 5 and $pLast = $pStart + 5;
	$pLast > $page_cnt and $pLast = $page_cnt;
	
	$pLast - $pStart < 5 and $pStart > 0 and $page_cnt > 5 and $pStart = $pLast - 5;
	
	for ( $i=$pStart; $i<$pLast; $i++ ) {
		if ( $i == ( $sStart / $sMax ) ) {
			$page .= '<font color="#999999"> ' . ( $i + 1 ) . '</font>';
		} else {
			$page .= ' <b><a href="javascript:submit_search(' . ( $i * $sMax ) . ');">' . ( $i + 1 ) . '</a></b>';
		}
	}
	
	if ( $now_page < $page_cnt ) {
		$page .= '　<a href="javascript:submit_search(' . ( $sStart + $sMax ) . ');">次へ＞</a>';
	} else {
		$page .= '　<font color="#999999">次へ＞</font>';
	}
	
	return $page;
}
// モバイルページリンクの生成
function setPageLinkMobile() {
	global $DataCnt , $sStart , $sMax ,$param ;
	
	if($param) {
		$add_param = str_replace("?","&",$param);
	}elseif(isset($_GET['adcode'])){
		$add_param ="&adcode=".$_GET['adcode'];
	}
	
	$DataCnt < $sStart and $sStart = 0;
	$pMax = 4;
	$sLast = ( $sStart + $sMax > $DataCnt ? $DataCnt : $sStart + $sMax );
	//$page = 'ヒット件数：' . $DataCnt . '件';
	if ( $DataCnt == 0 ) return $page;
	
	//$DataCnt > 0 and $page .= '( ' . ( $sStart + 1 ) . '～' . $sLast . '件目を表示 )　';
	$page_cnt = ceil( $DataCnt / $sMax );
	$now_page = $sStart / $sMax + 1;
	
	if ( $now_page > 1 ) {
		$page .= '<a href="'.$_SERVER['PHP_SELF'].'?start=' . ( $sStart - $sMax ) . $add_param . '">＜前へ</a>';
	} else {
		$page .= '<font color="#999999">＜前へ</font>';
	}
	
	$pStart = $now_page - 3;
	$pStart < 0 and $pStart = 0;
	
	$pLast = $now_page + $pMax - 2;
	$pLast - $pStart < 5 and $pLast = $pStart + 5;
	$pLast > $page_cnt and $pLast = $page_cnt;
	
	$pLast - $pStart < 5 and $pStart > 0 and $page_cnt > 5 and $pStart = $pLast - 5;
	
	for ( $i=$pStart; $i<$pLast; $i++ ) {
		if ( $i == ( $sStart / $sMax ) ) {
			$page .= '<font color="#999999"> ' . ( $i + 1 ) . '</font>';
		} else {
			$page .= ' <b><a href="'.$_SERVER['PHP_SELF'].'?start=' . ( $i * $sMax ) . $add_param .  '">' . ( $i + 1 ) . '</a></b>';
		}
	}
	
	if ( $now_page < $page_cnt ) {
		$page .= '　<a href="'.$_SERVER['PHP_SELF'].'?start=' . ( $sStart + $sMax ) . $add_param .  '">次へ＞</a>';
	} else {
		$page .= '　<font color="#999999">次へ＞</font>';
	}
	
	return $page;
}


// データの削除
function Delete_Data2($table){
	// DBから削除
	Delete_Table_Row($table,"id",$_REQUEST['id']);
	return true;
}

// accesslog accessor
function IncrementAccessLog($date, $page_id, $mo_agent, $adcode=''){
	$GLOBALS['mysqldb']->query("INSERT INTO accesslog(access_date,page_id,mo_agent,adcode,job_flg,count) VALUES ('".$date."',".$page_id.",".$mo_agent.",'".$adcode."',0,1) ON DUPLICATE KEY UPDATE count=count+1;");

}

// accesslog accessor JOB(CARRER)用
function IncrementAccessLog2($date, $page_id, $mo_agent, $adcode=''){
	$GLOBALS['mysqldb']->query("INSERT INTO accesslog(access_date,page_id,mo_agent,adcode,job_flg,count) VALUES ('".$date."',".$page_id.",".$mo_agent.",'".$adcode."',1,1) ON DUPLICATE KEY UPDATE count=count+1;");
}

// accesslog accessor MURYOU会員用
function IncrementAccessLog3($date, $page_id, $mo_agent, $adcode=''){
	$GLOBALS['mysqldb']->query("INSERT INTO accesslog(access_date,page_id,mo_agent,adcode,job_flg,count) VALUES ('".$date."',".$page_id.",".$mo_agent.",'".$adcode."',2,1) ON DUPLICATE KEY UPDATE count=count+1;");
}

// 代理店新規登録
function Input_New_Agent(){
	// DBに追加
	$sql = "INSERT INTO agent ( pid,name, mail, tantou, show_flg, id, password ) VALUES(";
	$sql .= DB_Cook( $_POST['pid'] , "1" ).",";
	$sql .= DB_Cook( $_POST['name'] , "1" ).",";
	$sql .= DB_Cook( $_POST['mail'] , "1" ).",";
	$sql .= DB_Cook( $_POST['tantou'] , "1" ).",";
	$sql .= DB_Cook( $_POST['show_flg'] , "1" ).",";
	$sql .= DB_Cook( $_POST['id'] , "1" ).",";
	$sql .= DB_Cook( $_POST['password'] , "1" );
	$sql .= ");";
	$rtn = $GLOBALS['mysqldb']->query( $sql );
	return $rtn==false ? false : true;
}

// 新規登録
function Input_New_Data($table,$names){
	foreach($names as $key=>$val){
		$vals1[] = $val;
		$vals2[] = DB_Cook($_POST[$val],"1");
	}
	$names1 .= implode(",",$vals1);
	$names2 .= implode(",",$vals2);
	$sql = "INSERT INTO ".$table." ( ".$names1." ) VALUES(".$names2.");";
	//if($table=="allowance"){
	//var_dump($sql);	exit;
	//}
	$rtn = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);

	//if($rtn) $data = Get_Table_Row($table," ORDER BY id DESC LIMIT 1");
	//return $rtn==false ? false : $data['id'];

	#IDを取得
	$res=$GLOBALS['mysqldb']->query("SELECT LAST_INSERT_ID()");
	$dat=$res->fetch_row();
	return $rtn==false ? false : $dat[0];
}

function Input_New_Data2($table,$names,$array_data){
	foreach($names as $key=>$val){
		$vals1[] = $val;
		$vals2[] = DB_Cook($array_data[$val],"1");
	}
	$names1 .= implode(",",$vals1);
	$names2 .= implode(",",$vals2);
	$sql = "INSERT INTO ".$table." ( ".$names1." ) VALUES(".$names2.");";
	
	//if($table=="contract"){
	//	var_dump($sql);exit;
	//}
	$rtn = $GLOBALS['mysqldb']->query( $sql ) or die('query error'.$GLOBALS['mysqldb']->error);

	//if($rtn) $data = Get_Table_Row($table," ORDER BY id DESC LIMIT 1");
	//return $rtn==false ? false : $data['id'];

	#IDを取得
	$res=$GLOBALS['mysqldb']->query("SELECT LAST_INSERT_ID()");
	$dat=$res->fetch_row();
	return $rtn==false ? false : $dat[0];
}

// データの更新
function Update_Data($table,$names,$id){
	$sql = "UPDATE ".$table." SET ";
	foreach($names as $key=>$val){
		$vals[] = $val." = ".DB_Cook($_POST[$val],"1");
	}
	$sql .= implode(",",$vals);
	$sql .= " WHERE id = '".addslashes($id)."'";
	//if($table=="goal"){
		//var_dump($sql);
		//exit;
	//}
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	return $rtn==false ? false : $id;
}

// データの更新 $where=条件
function Update_Data_Where($table,$names,$where){
	$sql = "UPDATE ".$table." SET ";
	foreach($names as $key=>$val){
		$vals[] = $val." = ".DB_Cook($_POST[$val],"1");
	}
	$sql .= implode(",",$vals);
	$sql .= " WHERE ".$where;
	//if($table=="goal"){
		//var_dump($sql);
		//exit;
	//}
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	return $rtn==false ? false : $id;
}

function Update_Data_IN($table,$names,$id){
	$sql = "UPDATE ".$table." SET ";
	foreach($names as $key=>$val){
		$vals[] = $val." = ".DB_Cook($_POST[$val],"1");
	}
	$sql .= implode(",",$vals);
	$sql .= " WHERE id IN (".addslashes($id).")";
	//if($table=="goal"){
		//var_dump($sql);exit;
	//}
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	return $rtn==false ? false : $id;
}

// データの新規登録
function Input_Data($table){
	foreach($_POST as $key=>$val){
		if($key!="id" && $key!="action" && $key!="mode" && $key!="image_type" && $key!="undefined" && $key!="first" && $key!="test" && $key!="criteo_acid" && $key!="rs_segs" && $key!="xcddsa" && $key!="ngi" && !is_numeric($key) && substr($key,0,1)<> "_" && !strstr($key, "CSUUID") && !strstr($key, "_kz") && !strstr($key, "_jzq") && !strstr($key, "_qz") && $key!="from_cc" && !strstr($key,"mixpanel") && $key!="cs" && !strstr($key, "rhpm") && !strstr($key, "FormAssist") && !strstr($key, "pt_") && $key!="_dc_gtm_UA-47320244-1" && $key!="__pbcd_id" && $key!="__pbcd_oid" && $key!="blnx" && $key!="__utma" && $key!="__utmb" && $key!="__utmc" && $key!="_ga" && $key!="__utmz" && $key!="__ulfpc" && $key!="__ywapbuk" && $key!="PHPSESSID"  && $key!="img_file" && $key!="img_pc_file" && $key!="img_mo_file" && $key!="gMsg"){
			$vals1[] = $key;
			$vals2[] = DB_Cook($val,"1");
		}
	}
	$names1 .= implode(",",$vals1);
	$names2 .= implode(",",$vals2);
	
	$sql = "INSERT INTO ".$table." ( ".$names1." ) VALUES(".$names2.");";

	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	if($rtn) $data = Get_Table_Row($table," ORDER BY id DESC LIMIT 1");
	return $rtn==false ? false : $data['id'];
}

// データの更新
function Input_Update_Data($table){
	$sql = "UPDATE ".$table." SET ";
	foreach($_POST as $key=>$val){
		if($key!="id" && $key!="action" && $key!="mode" && $key!="image_type" && $key!="undefined" && $key!="first" && $key!="test" && $key!="criteo_acid" && $key!="rs_segs" && $key!="xcddsa" && $key!="ngi" && !is_numeric($key) && substr($key,0,1)<> "_" && !strstr($key, "CSUUID") && !strstr($key, "_kz") && !strstr($key, "_jzq") && !strstr($key, "_qz") && $key!="from_cc" && !strstr($key,"mixpanel") && $key!="cs" && !strstr($key, "rhpm") && !strstr($key, "FormAssist") && !strstr($key, "pt_") && $key!="_dc_gtm_UA-47320244-1" && $key!="__pbcd_id" && $key!="__pbcd_oid" && $key!="blnx" && $key!="__utma" && $key!="__utmb" && $key!="__utmc" && $key!="_ga" && $key!="__utmz" && $key!="__ulfpc" && $key!="__ywapbuk" && $key!="PHPSESSID" && $key!="img_file" && $key!="img_pc_file" && $key!="img_mo_file" && $key!="gMsg")$vals[] = $key." = ".DB_Cook($val,"1");
	}
	$sql .= implode(",",$vals);
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
//var_dump($sql);exit;
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	return $rtn==false ? false : $_POST['id'];
}
// 代理店データの更新
function Input_Update_Agent(){
	// DB更新
	$names = array( "pid","name", "password", "mail", "tantou", "show_flg" );
	$sql = "UPDATE agent SET ".Post_To_DB_Cook($names);
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	return $rtn==false ? false : $_POST['id'];
}
// 広告コード新規登録
function Input_New_Adcode(){
	// DBに追加
	$sql = "INSERT INTO adcode ( form_type,memo,adcode, agent_id,name,site,page_id,page_name,type,ad_group,request_id,flyer_no,cost1,cost,maximum,release_date,job_flg ,hide_flg,reg_date,edit_date) VALUES(";
	$sql .= DB_Cook( $_POST['form_type'] , "1" ).",";	
	$sql .= DB_Cook( $_POST['memo'] , "1" ).",";
	$sql .= DB_Cook( $_POST['adcode'] , "1" ).",";
	$sql .= DB_Cook( $_POST['agent_id'] , "1" ).",";
	$sql .= DB_Cook( $_POST['name'] , "1" ).",";
	$sql .= DB_Cook( $_POST['site'] , "1" ).",";
	$sql .= DB_Cook( $_POST['page_id'] , "1" ).",";
	$sql .= DB_Cook( $_POST['page_name'] , "1" ).",";
	$sql .= DB_Cook( $_POST['type'] , "1" ).",";
	$sql .= DB_Cook( $_POST['ad_group'] , "1" ).",";
	$sql .= DB_Cook( $_POST['request_id'] , "1" ).",";
	$sql .= DB_Cook( $_POST['flyer_no'] , "1" ).",";
	$sql .= DB_Cook( $_POST['cost1'] , "1" ).",";
	$sql .= DB_Cook( $_POST['cost'] , "1" ).",";
	$sql .= DB_Cook( $_POST['maximum'] , "1" ).",";
	$sql .= DB_Cook( $_POST['release_date'] , "1" ).",";
	$sql .= DB_Cook( $_POST['job_flg'] , "1" ).",";
	$sql .= DB_Cook( $_POST['hide_flg'] , "1" ).",";
	$sql .= "'".date("Y-m-d H:i:s")."'".",";
	$sql .= "'".date("Y-m-d H:i:s")."'";
	$sql .= ");";
//var_dump($sql);
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	return $rtn==false ? false : true;
}
// 広告コードデータの更新
function Input_Update_Adcode(){
	// DB更新
	$names = array( "form_type","memo","agent_id","adcode","name","site","page_id","page_name","type","ad_group","request_id","flyer_no","cost1","cost","maximum","release_date","job_flg","hide_flg","edit_date" );
	$sql = "UPDATE adcode SET ".Post_To_DB_Cook($names);
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
	$rtn = $GLOBALS['mysqldb']->query($sql);
	return $rtn==false ? false : $_POST['id'];
}
// ユーザー新規登録
function Input_New_User($id){
	global $mobile_id;
	// DBに追加
	
	$sql = "INSERT INTO user ( id,password,status,name, name_kana, phone, mail, sex, age, prefecture, address, present, adcode, keiba_reki,target, kibourenraku, jyouhou, urajyouhou,moushikomi, reg_flg,  mo_agent, mo_id, reg_date  ) VALUES(";
	$sql .= DB_Cook( $id , "1" ).",";
	$sql .= DB_Cook( $_POST['password'] , "1" ).",";
	$sql .= DB_Cook( $_POST['status'] , "1" ).",";
	$sql .= DB_Cook( $_POST['name'] , "1" ).",";
	$sql .= DB_Cook( $_POST['name_kana'] , "1" ).",";
	$sql .= DB_Cook( $_POST['phone'] , "1" ).",";
	$sql .= DB_Cook( $_POST['mail'] , "1" ).",";
	$sql .= DB_Cook( $_POST['sex'] , "1" ).",";
	$sql .= DB_Cook( $_POST['age'] , "1" ).",";
	$sql .= DB_Cook( $_POST['prefecture'] , "1" ).",";
	$sql .= DB_Cook( $_POST['address'] , "1" ).",";
	$sql .= DB_Cook( $_POST['present'] , "1" ).",";
	$sql .= DB_Cook( $_POST['adcode'] , "1" ).",";
	$sql .= DB_Cook( $_POST['keiba_reki'] , "1" ).",";
	$sql .= DB_Cook( $_POST['target'] , "1" ).",";
	$sql .= DB_Cook( $_POST['kibourenraku'] , "1" ).",";
	$sql .= DB_Cook( $_POST['jyouhou'] , "1" ).",";
	$sql .= DB_Cook( $_POST['urajyouhou'] , "1" ).",";
	$sql .= DB_Cook( $_POST['moushikomi'] , "1" ).",";
	$sql .= DB_Cook( $_POST['reg_flg'] , "1" ).",";
	$sql .= DB_Cook( $_POST['mo_agent'] , "1" ).",";
	$sql .= "'". $mobile_id ."',";
	$sql .= "'".date("Y-m-d H:i:s")."'";
	$sql .= ");";
	
	$rtn = $GLOBALS['mysqldb']->query( $sql );
	return $rtn==false ? false : true;
}
// ユーザーの更新
function Input_Update_User(){
	// DB更新
	$names = array( "reg_flg","status","name","name_kana","phone","mail","sex","age","prefecture","address","present" ,"adcode","mo_agent","keiba_reki","target","kibourenraku","jyouhou","urajyouhou" ,"moushikomi","reg_date" );
	$sql = "UPDATE user SET ".Post_To_DB_Cook($names);
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
	$rtn = $GLOBALS['mysqldb']->query($sql);
	return $rtn==false ? false : $_POST['id'];
}
// メニューの変更
function Input_Update_Menu(){
	// DB更新
	$names = array( "name","status","page","authority","rank" );
	$sql = "UPDATE menu SET ".Post_To_DB_Cook($names);
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
	$rtn = $GLOBALS['mysqldb']->query($sql);
	return $rtn==false ? false : $_POST['id'];
}
// ユーザーの更新2
function Regist_User($id){
	// DB更新
	$names = array( "name","prefecture","sex","age","phone","mail","keiba_reki","target","kibourenraku","jyouhou","urajyouhou" ,"moushikomi","present","reg_flg","reg_date" );
	$sql = "UPDATE user SET ".Post_To_DB_Cook($names);
	$sql .= " WHERE id = '".addslashes($id)."'";

	$rtn = $GLOBALS['mysqldb']->query($sql);
	return $rtn==false ? false : $id;
}

// 5C問題対処
function Check5c($str) {
	// 全角に特定
	$last_word = mb_substr($str,-1, 1,"shift_jis");

	// 0x5cにかかるかを判別
	if($last_word =="\x5c"){
		$str = $str." ";
	}

	return $str;
}

/**
 * メール送信
 *
 * @param String $to           : Receiver mail address.
 * @param String $subject      : email subject.
 * @param int    $body         : mail body text.
 */
function Update_Contract_P_Status($contract_array,$customer_id=0){
	$status  =""; // 契約ステータス
	$flg0    =0;
	$flg1    =0;
	$flg2    =0;
	$flg3    =0;
	$flg4    =0;
	$flg5    =0;
	$flg6    =0;
	$flg7    =0;
	// 契約情報がある場合ステータス番号を取得する
	if($contract_array){
		foreach ($contract_array as $key => $value) {
			if($value['status']==0)$flg0=1; // 契約中
			if($value['status']==1)$flg1=1; // 契約終了
			if($value['status']==2)$flg2=1; // クーリング・オフ
			if($value['status']==3)$flg3=1; // 中途解約
			if($value['status']==4)$flg4=1; // プラン変更
			if($value['status']==5)$flg5=1; // ローン取消
			if($value['status']==6)$flg6=1; // 自動解約
			if($value['status']==7)$flg7=1; // 契約待ち
			if($value['status']==8)$flg8=1; // 返金保証回数終了
		}
		// ステータス優先順位(下から順)を決めてステータス番号を返す
		if($flg4==1)$status=4; // プラン変更
		if($flg6==1)$status=6; // 自動解約
		if($flg3==1)$status=3; // 中途解約
		if($flg2==1)$status=2; // クーリング・オフ
		if($flg8==1)$status=1; // 返金保証回数終了
		if($flg1==1)$status=1; // 契約終了
		if($flg7==1)$status=7; // 契約待ち
		if($flg5==1)$status=5; // ローン取消
		if($flg0==1)$status=0; // 契約中
	}
	return $status;
}

/**
 * 契約ステータス検索
 * 複数契約のステータスをすべて確認して、そのステータスがあるかどうかチェックする
 * @param array  $contract_array  : Get_Table_Arrayでcontractを複数取得したときの結果
 * @param int    $search_status   : 探したいステータス(任意)
 * @param int    $customer_id     : 顧客ID
 * return  ステータス番号
 */
function Search_Contract_Status($contract_array,$search_status=0,$customer_id=0){
	$status_flg =0; // 契約ステータスフラグ
	$loan_flg   =0; // ローン支払いフラグ
	$loan_cancel_flg=0; // ローン取消フラグ
	$loan_non_approval_flg = 0; // ローン非承認フラグ
	$balance_flg=0; // 売掛金支払いフラグ
	$loan_array = array();
	$balance_array = array();
	
	// 契約情報がある場合ステータス番号を取得する
	if($contract_array){
		foreach ($contract_array as $key => $value) {
			// 探したいステータスがあるかどうか
			if($value['status'] == $search_status){
				$status_flg=1;
			}
			// ローン支払いがあるか
			if(0 < $value['payment_loan']){
				$loan_flg=1;
				$loan_array[$customer_id] = array('customer_id'=>$customer_id,'pid'=>$value['pid'],'loan_status'=>$value['loan_status'],'payment_loan'=>$value['payment_loan']);
			}
			// 売掛があるか
			if($value['loan_status']==4){
				$loan_cancel_flg =1;// 4.ローン取消
			}
			// ローン非承認があるか
			if($value['loan_status']==2){
				$loan_non_approval_flg =1;// 2.ローン非承認
			}
			// 売掛があるか
			if(0 < $value['balance']){
				$balance_flg=1;
				$balance_array[$customer_id] = array('customer_id'=>$customer_id,'pid'=>$value['pid'],'balance'=>$value['balance']);
			}
		}
	}
	return array("status_flg"=>$status_flg,"loan_flg"=>$loan_flg,"loan_array"=>$loan_array,"loan_cancel_flg"=>$loan_cancel_flg,"loan_non_approval_flg"=>$loan_non_approval_flg,"balance_flg"=>$balance_flg,"balance_array"=>$balance_array);
}


/**
 * メール送信
 *
 * @param String $to           : Receiver mail address.
 * @param String $subject      : email subject.
 * @param int    $body         : mail body text.
 * @param array  $from_email   : Sender mail address.
 * @param array  $from_name    : Sender Name.
 *
 */
function sendMail($to, $subject, $body, $from_email,$cc,$bcc,$return){
	$headers  = "MIME-Version: 1.0 \n" ;
	$headers .= "From: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"ISO-2022-JP","AUTO")) ."" .
       "<".$from_email."> \n";
	
	$headers .= "Reply-To: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"ISO-2022-JP","AUTO")) ."" .
       "<".$from_email."> \n";
	
	if($cc){
		// 複数送信先の場合
		$cc_array = explode(";", $cc);
		foreach($cc_array as $key =>$val){
			$cc_data .= $comma1 . mb_encode_mimeheader (mb_convert_encoding($val,"ISO-2022-JP","AUTO")) . "<".$val.">";
			$comma1 = ",";
 		}
		$headers .= "Cc: " .$cc_data ."\n";
    }
	if($bcc){
		// 複数送信先の場合
		$bcc_array = explode(";", $bcc);
		foreach($bcc_array as $key =>$val){
			$bcc_data .= $comma2 . mb_encode_mimeheader (mb_convert_encoding($val,"ISO-2022-JP","AUTO")) . "<".$val.">";
			$comma2 = ",";
 		}
		$headers .= "Bcc: " .$bcc_data ."\n";
    }
    
	$headers .= "Content-Type: text/plain;charset=ISO-2022-JP \n";
    
	/* Mail, optional paramiters. */
	if($return) $sendmail_params  = "-f$return";
	else $sendmail_params  = "-f$from_email";
    
	mb_language("ja");
	
	//$subject =mb_convert_kana($subject ,"K","SJIS");
	$subject = mb_convert_encoding($subject, "ISO-2022-JP","AUTO");
	$subject = mb_encode_mimeheader($subject);
	
	//$body =mb_convert_kana($body ,"K","SJIS");
	$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");


	$result = mail($to, $subject, $body, $headers, $sendmail_params);
       
	return $result;
}
function sendMailHtml($to, $subject, $body, $from_email,$cc,$bcc,$return){
	$headers  = "MIME-Version: 1.0 \n" ;
	$headers .= "From: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"UTF-8","AUTO")) ."" .
       "<".$from_email."> \n";
	
	$headers .= "Reply-To: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"UTF-8","AUTO")) ."" .
       "<".$from_email."> \n";
	
	if($cc){
		// 複数送信先の場合
		$cc_array = explode(";", $cc); 
		foreach($cc_array as $key =>$val){
			$cc_data .= $comma1 . mb_encode_mimeheader (mb_convert_encoding($val,"UTF-8","AUTO")) . "<".$val.">";
			$comma1 = ",";
 		}
		$headers .= "Cc: " .$cc_data ."\n";
    }
	if($bcc){
		// 複数送信先の場合
		$bcc_array = explode(";", $bcc); 
		foreach($bcc_array as $key =>$val){
			$bcc_data .= $comma2 . mb_encode_mimeheader (mb_convert_encoding($val,"UTF-8","AUTO")) . "<".$val.">";
			$comma2 = ",";
 		}
		$headers .= "Bcc: " .$bcc_data ."\n";
    }
    

	// HTML メールを送信
    $headers .= "Content-type: text/html; charset=UTF-8 \n";
    
    
	/* Mail, optional paramiters. */
	if($return) $sendmail_params  = "-f$return";
	else $sendmail_params  = "-f$from_email";
    
	mb_language("ja");
	
	//$subject =mb_convert_kana($subject ,"K","SJIS");
	$subject = mb_convert_encoding($subject, "UTF-8","AUTO");
	$subject = mb_encode_mimeheader($subject);

	//mail関数より、改行コードCR+LF(\r\n)がLF(\n)に変更
	$body = str_replace("\r","",$body);
	$body = str_replace("\t","",$body);
	//$body = str_replace("\n","<br>",$body);
	
	//$body =mb_convert_kana($body ,"K","SJIS");
	$body = mb_convert_encoding($body, "UTF-8","AUTO");

	

	$result = mail($to, $subject, $body, $headers, $sendmail_params);
       
	return $result;
}

// ISO-2022-JP-MSのサポートはPHP5.2.1から
function sendMailHtmlISO($to, $subject, $body, $from_email,$cc,$bcc,$return){
	$headers  = "MIME-Version: 1.0 \n" ;
	$headers .= "From: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"ISO-2022-JP-MS","AUTO")) ."" .
       "<".$from_email."> \n";
	
	$headers .= "Reply-To: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_email,"ISO-2022-JP-MS","AUTO")) ."" .
       "<".$from_email."> \n";
	
	if($cc){
		$cc_array = explode(";", $cc);//複数送信先の場合
		foreach($cc_array as $key =>$val){
			$cc_data .= $comma1 . mb_encode_mimeheader (mb_convert_encoding($val,"ISO-2022-JP-MS","AUTO")) . "<".$val.">";
			$comma1 = ",";
 		}
		$headers .= "Cc: " .$cc_data ."\n";
    }
	if($bcc){
		$bcc_array = explode(";", $bcc);//複数送信先の場合
		foreach($bcc_array as $key =>$val){
			$bcc_data .= $comma2 . mb_encode_mimeheader (mb_convert_encoding($val,"ISO-2022-JP-MS","AUTO")) . "<".$val.">";
			$comma2 = ",";
 		}
		$headers .= "Bcc: " .$bcc_data ."\n";
    }
    

	// HTML メールを送信
    $headers .= "Content-type: text/html; charset=ISO-2022-JP \n";
    
    
	/* Mail, optional paramiters. */
	if($return) $sendmail_params  = "-f$return";
	else $sendmail_params  = "-f$from_email";
    
	mb_language("ja");
	
	//$subject =mb_convert_kana($subject ,"K","SJIS");
	$subject = mb_convert_encoding($subject, "ISO-2022-JP-MS","AUTO");
	$subject = mb_encode_mimeheader($subject);

	//mail関数より、改行コードCR+LF(\r\n)がLF(\n)に変更
	$body = str_replace("\r","",$body);
	$body = str_replace("\t","",$body);
	//$body = str_replace("\n","<br>",$body);
	
	//$body =mb_convert_kana($body ,"K","SJIS");
	$body = mb_convert_encoding($body, "ISO-2022-JP-MS","AUTO");

	

	$result = mail($to, $subject, $body, $headers, $sendmail_params);
       
	return $result;
}

// まだ使えない！！
function sendMailHtmlUTF8($mail_to, $subject, $body_html, $mail_from){
	$parameter = "-f ".$mail_from;
	$boundary = "--".uniqid(rand(),1);

	// ヘッダー情報
	$headers = '';
	$headers .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '"' . "\r\n";
	$headers .= 'Content-Transfer-Encoding: binary' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	 $headers .= "From: " . $mail_from . "\r\n";

	// メッセージ部分
	$message = '';
	$message .= '--' . $boundary . "\r\n";
	$message .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
	$message .= 'Content-Disposition: inline' . "\r\n";
	$message .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n";
	$message .= "\r\n";
	$message .= quoted_printable_decode ( $body_html ) . "\r\n";
	$message .= '--' . $boundary . "\r\n";

	// 送信する
	$result = mail($mail_to,$subject, $message, $headers, $parameter);
	return $result;
}

function bake_check($check)
{
	foreach ( $check as $key => $value )
	{
		if (get_magic_quotes_gpc()) $value = stripslashes($value);
		if (get_magic_quotes_gpc()) $key = stripslashes($key);
		$key = preg_replace('/＼/','ー',$key);
		$check[$key] = htmlspecialchars($value);
	}
	return $check;
}
function bake_check2($check)
{
	foreach ( $check as $key => $value )
	{
		if (get_magic_quotes_gpc()) $value = stripslashes($value);
		$check[$key] = htmlspecialchars($value);
	}
	return $check;
}
/**
 * 1.頭が“0”以外で始まるもの。
 * 2.9桁以下、12桁以上の番号。
 * 3.11桁で、頭が“050”“070”“080”“090”以外の番号。
 * 4.11桁で、頭から4桁目以降がゾロ目の番号。
 * 5.11桁で、頭から4桁目以降が“12345678”。
 * 6.10桁で、頭から3桁目以降がゾロ目の番号。
 * 7.10桁で、頭から3桁目以降が“12345678”。
 */
function is_telnum($str)
{
	$telephone = trim($str);
	
	/*if($ngtelnum_list = $GLOBALS['mysqldb']->query( "SELECT * FROM ngtelnum WHERE status<>1" )){
		while ( $list = $ngtelnum_list->fetch_assoc() ) {
			if($list['type']){
				if(stristr($telephone, $list['name']))	return false;//type=1,NGNUMが含まれる
			}
			elseif($list['name'] == $telephone) return false;//type=0,=NGNUM
		}
	}*/
	$telephone = str_replace('-','',$telephone);
	if(preg_match("/^\d{10,11}$/i", $telephone)){ // case "2"
		if(substr($telephone, 0, 1) == "0"){ // case "1"
			$tel1 = substr($telephone, 0, 3);
			if(preg_match("/^(050|070|080|090)/i", $tel1)){ // case "3"
				if(strlen($telephone) == 11){	
					$tel2 = substr($telephone, 3);
					$first_num = substr($tel2, 0, 1);
					if(!preg_match("/^[{$first_num}]+$/i", $tel2))// case "4"
						return (!preg_match("/(12345678)/i", $telephone));// case "5"
				}
			}
			else if(strlen($telephone) == 10){
				$tel2 = substr($telephone, 2);
				$first_num = substr($tel2, 0, 1);
				if(!preg_match("/^[{$first_num}]+$/i", $tel2)) // case "6"
					return (!preg_match("/(12345678)/i", $telephone)); // case "7"
			}
		}
	}
	return false;
}

// '08011111111' => '080-1111-1111'
// 対応範囲:携帯電話、IP電話、固定電話。フリーダイヤルは対応しません
function sepalate_tel($tel){
	// 電話番号でのハイフン系を統一する
	$tel = mb_convert_encoding( $tel, "UTF-8", "auto");
	$tel = str_replace('‐','-',mb_convert_kana( $tel, "rn", "UTF-8"));
	$tel = str_replace('－','-',$tel);
	$tel = str_replace('ー','-',$tel);
	$tel = str_replace('―','-',$tel);
	$tel = str_replace('－','-',$tel);
	$tel = str_replace('-','',$tel);

	// 此処から電話番号の頭での分岐
	if(mb_strlen($tel) == 11){
		$re = substr($tel,0,3) . '-';
		$re .= substr($tel,3,4) . '-';
		$re .= substr($tel,7,4);
		return $re;
	}elseif(mb_strlen($tel) == 10){
		if(
			substr($tel,0,5) == "01267" ||
			substr($tel,0,5) == "01372" ||
			substr($tel,0,5) == "01374" ||
			substr($tel,0,5) == "01377" ||
			substr($tel,0,5) == "01392" ||
			substr($tel,0,5) == "01397" ||
			substr($tel,0,5) == "01398" ||
			substr($tel,0,5) == "01456" ||
			substr($tel,0,5) == "01457" ||
			substr($tel,0,5) == "01466" ||
			substr($tel,0,5) == "01547" ||
			substr($tel,0,5) == "01558" ||
			substr($tel,0,5) == "01564" ||
			substr($tel,0,5) == "01586" ||
			substr($tel,0,5) == "01587" ||
			substr($tel,0,5) == "01632" ||
			substr($tel,0,5) == "01634" ||
			substr($tel,0,5) == "01635" ||
			substr($tel,0,5) == "01648" ||
			substr($tel,0,5) == "01654" ||
			substr($tel,0,5) == "01655" ||
			substr($tel,0,5) == "01656" ||
			substr($tel,0,5) == "01658" ||
			substr($tel,0,5) == "04992" ||
			substr($tel,0,5) == "04994" ||
			substr($tel,0,5) == "04996" ||
			substr($tel,0,5) == "04998" ||
			substr($tel,0,5) == "05769" ||
			substr($tel,0,5) == "05979" ||
			substr($tel,0,5) == "07468" ||
			substr($tel,0,5) == "08387" ||
			substr($tel,0,5) == "08388" ||
			substr($tel,0,5) == "08396" ||
			substr($tel,0,5) == "08477" ||
			substr($tel,0,5) == "08512" ||
			substr($tel,0,5) == "08514" ||
			substr($tel,0,5) == "08636" ||
			substr($tel,0,5) == "09496" ||
			substr($tel,0,5) == "09802" ||
			substr($tel,0,5) == "09912" ||
			substr($tel,0,5) == "09913" ||
			substr($tel,0,5) == "09969"
		){
			$re = substr($tel,0,5) . '-';
			$re .= substr($tel,5,1) . '-';
			$re .= substr($tel,6,4);
			return $re;
		}elseif(
			substr($tel,0,4) == "0123" ||
			substr($tel,0,4) == "0124" ||
			substr($tel,0,4) == "0125" ||
			substr($tel,0,4) == "0126" ||
			substr($tel,0,4) == "0133" ||
			substr($tel,0,4) == "0134" ||
			substr($tel,0,4) == "0135" ||
			substr($tel,0,4) == "0136" ||
			substr($tel,0,4) == "0137" ||
			substr($tel,0,4) == "0138" ||
			substr($tel,0,4) == "0139" ||
			substr($tel,0,4) == "0142" ||
			substr($tel,0,4) == "0143" ||
			substr($tel,0,4) == "0144" ||
			substr($tel,0,4) == "0145" ||
			substr($tel,0,4) == "0146" ||
			substr($tel,0,4) == "0152" ||
			substr($tel,0,4) == "0153" ||
			substr($tel,0,4) == "0154" ||
			substr($tel,0,4) == "0155" ||
			substr($tel,0,4) == "0156" ||
			substr($tel,0,4) == "0157" ||
			substr($tel,0,4) == "0158" ||
			substr($tel,0,4) == "0162" ||
			substr($tel,0,4) == "0163" ||
			substr($tel,0,4) == "0164" ||
			substr($tel,0,4) == "0165" ||
			substr($tel,0,4) == "0166" ||
			substr($tel,0,4) == "0167" ||
			substr($tel,0,4) == "0172" ||
			substr($tel,0,4) == "0173" ||
			substr($tel,0,4) == "0174" ||
			substr($tel,0,4) == "0175" ||
			substr($tel,0,4) == "0176" ||
			substr($tel,0,4) == "0178" ||
			substr($tel,0,4) == "0179" ||
			substr($tel,0,4) == "0182" ||
			substr($tel,0,4) == "0183" ||
			substr($tel,0,4) == "0184" ||
			substr($tel,0,4) == "0185" ||
			substr($tel,0,4) == "0186" ||
			substr($tel,0,4) == "0187" ||
			substr($tel,0,4) == "0191" ||
			substr($tel,0,4) == "0192" ||
			substr($tel,0,4) == "0193" ||
			substr($tel,0,4) == "0194" ||
			substr($tel,0,4) == "0195" ||
			substr($tel,0,4) == "0197" ||
			substr($tel,0,4) == "0198" ||
			substr($tel,0,4) == "0220" ||
			substr($tel,0,4) == "0223" ||
			substr($tel,0,4) == "0224" ||
			substr($tel,0,4) == "0225" ||
			substr($tel,0,4) == "0226" ||
			substr($tel,0,4) == "0228" ||
			substr($tel,0,4) == "0229" ||
			substr($tel,0,4) == "0233" ||
			substr($tel,0,4) == "0234" ||
			substr($tel,0,4) == "0235" ||
			substr($tel,0,4) == "0237" ||
			substr($tel,0,4) == "0238" ||
			substr($tel,0,4) == "0240" ||
			substr($tel,0,4) == "0241" ||
			substr($tel,0,4) == "0242" ||
			substr($tel,0,4) == "0243" ||
			substr($tel,0,4) == "0244" ||
			substr($tel,0,4) == "0246" ||
			substr($tel,0,4) == "0247" ||
			substr($tel,0,4) == "0248" ||
			substr($tel,0,4) == "0250" ||
			substr($tel,0,4) == "0254" ||
			substr($tel,0,4) == "0255" ||
			substr($tel,0,4) == "0256" ||
			substr($tel,0,4) == "0257" ||
			substr($tel,0,4) == "0258" ||
			substr($tel,0,4) == "0259" ||
			substr($tel,0,4) == "0260" ||
			substr($tel,0,4) == "0261" ||
			substr($tel,0,4) == "0263" ||
			substr($tel,0,4) == "0264" ||
			substr($tel,0,4) == "0265" ||
			substr($tel,0,4) == "0266" ||
			substr($tel,0,4) == "0267" ||
			substr($tel,0,4) == "0268" ||
			substr($tel,0,4) == "0269" ||
			substr($tel,0,4) == "0270" ||
			substr($tel,0,4) == "0274" ||
			substr($tel,0,4) == "0276" ||
			substr($tel,0,4) == "0277" ||
			substr($tel,0,4) == "0278" ||
			substr($tel,0,4) == "0279" ||
			substr($tel,0,4) == "0280" ||
			substr($tel,0,4) == "0282" ||
			substr($tel,0,4) == "0283" ||
			substr($tel,0,4) == "0284" ||
			substr($tel,0,4) == "0285" ||
			substr($tel,0,4) == "0287" ||
			substr($tel,0,4) == "0288" ||
			substr($tel,0,4) == "0289" ||
			substr($tel,0,4) == "0291" ||
			substr($tel,0,4) == "0293" ||
			substr($tel,0,4) == "0294" ||
			substr($tel,0,4) == "0295" ||
			substr($tel,0,4) == "0296" ||
			substr($tel,0,4) == "0297" ||
			substr($tel,0,4) == "0299" ||
			substr($tel,0,4) == "0422" ||
			substr($tel,0,4) == "0428" ||
			substr($tel,0,4) == "0436" ||
			substr($tel,0,4) == "0438" ||
			substr($tel,0,4) == "0439" ||
			substr($tel,0,4) == "0460" ||
			substr($tel,0,4) == "0463" ||
			substr($tel,0,4) == "0465" ||
			substr($tel,0,4) == "0466" ||
			substr($tel,0,4) == "0467" ||
			substr($tel,0,4) == "0470" ||
			substr($tel,0,4) == "0475" ||
			substr($tel,0,4) == "0476" ||
			substr($tel,0,4) == "0478" ||
			substr($tel,0,4) == "0479" ||
			substr($tel,0,4) == "0480" ||
			substr($tel,0,4) == "0493" ||
			substr($tel,0,4) == "0494" ||
			substr($tel,0,4) == "0495" ||
			substr($tel,0,4) == "0531" ||
			substr($tel,0,4) == "0532" ||
			substr($tel,0,4) == "0533" ||
			substr($tel,0,4) == "0536" ||
			substr($tel,0,4) == "0537" ||
			substr($tel,0,4) == "0538" ||
			substr($tel,0,4) == "0539" ||
			substr($tel,0,4) == "0544" ||
			substr($tel,0,4) == "0545" ||
			substr($tel,0,4) == "0547" ||
			substr($tel,0,4) == "0548" ||
			substr($tel,0,4) == "0550" ||
			substr($tel,0,4) == "0551" ||
			substr($tel,0,4) == "0553" ||
			substr($tel,0,4) == "0554" ||
			substr($tel,0,4) == "0555" ||
			substr($tel,0,4) == "0556" ||
			substr($tel,0,4) == "0557" ||
			substr($tel,0,4) == "0558" ||
			substr($tel,0,4) == "0561" ||
			substr($tel,0,4) == "0562" ||
			substr($tel,0,4) == "0563" ||
			substr($tel,0,4) == "0564" ||
			substr($tel,0,4) == "0565" ||
			substr($tel,0,4) == "0566" ||
			substr($tel,0,4) == "0567" ||
			substr($tel,0,4) == "0568" ||
			substr($tel,0,4) == "0569" ||
			substr($tel,0,4) == "0572" ||
			substr($tel,0,4) == "0573" ||
			substr($tel,0,4) == "0574" ||
			substr($tel,0,4) == "0575" ||
			substr($tel,0,4) == "0576" ||
			substr($tel,0,4) == "0577" ||
			substr($tel,0,4) == "0578" ||
			substr($tel,0,4) == "0581" ||
			substr($tel,0,4) == "0584" ||
			substr($tel,0,4) == "0585" ||
			substr($tel,0,4) == "0586" ||
			substr($tel,0,4) == "0587" ||
			substr($tel,0,4) == "0594" ||
			substr($tel,0,4) == "0595" ||
			substr($tel,0,4) == "0596" ||
			substr($tel,0,4) == "0597" ||
			substr($tel,0,4) == "0598" ||
			substr($tel,0,4) == "0599" ||
			substr($tel,0,4) == "0721" ||
			substr($tel,0,4) == "0725" ||
			substr($tel,0,4) == "0735" ||
			substr($tel,0,4) == "0736" ||
			substr($tel,0,4) == "0737" ||
			substr($tel,0,4) == "0738" ||
			substr($tel,0,4) == "0739" ||
			substr($tel,0,4) == "0740" ||
			substr($tel,0,4) == "0742" ||
			substr($tel,0,4) == "0743" ||
			substr($tel,0,4) == "0744" ||
			substr($tel,0,4) == "0745" ||
			substr($tel,0,4) == "0746" ||
			substr($tel,0,4) == "0747" ||
			substr($tel,0,4) == "0748" ||
			substr($tel,0,4) == "0749" ||
			substr($tel,0,4) == "0761" ||
			substr($tel,0,4) == "0763" ||
			substr($tel,0,4) == "0765" ||
			substr($tel,0,4) == "0766" ||
			substr($tel,0,4) == "0767" ||
			substr($tel,0,4) == "0768" ||
			substr($tel,0,4) == "0770" ||
			substr($tel,0,4) == "0771" ||
			substr($tel,0,4) == "0772" ||
			substr($tel,0,4) == "0773" ||
			substr($tel,0,4) == "0774" ||
			substr($tel,0,4) == "0776" ||
			substr($tel,0,4) == "0778" ||
			substr($tel,0,4) == "0779" ||
			substr($tel,0,4) == "0790" ||
			substr($tel,0,4) == "0791" ||
			substr($tel,0,4) == "0794" ||
			substr($tel,0,4) == "0795" ||
			substr($tel,0,4) == "0796" ||
			substr($tel,0,4) == "0797" ||
			substr($tel,0,4) == "0798" ||
			substr($tel,0,4) == "0799" ||
			substr($tel,0,4) == "0820" ||
			substr($tel,0,4) == "0823" ||
			substr($tel,0,4) == "0824" ||
			substr($tel,0,4) == "0826" ||
			substr($tel,0,4) == "0827" ||
			substr($tel,0,4) == "0829" ||
			substr($tel,0,4) == "0833" ||
			substr($tel,0,4) == "0834" ||
			substr($tel,0,4) == "0835" ||
			substr($tel,0,4) == "0836" ||
			substr($tel,0,4) == "0837" ||
			substr($tel,0,4) == "0838" ||
			substr($tel,0,4) == "0845" ||
			substr($tel,0,4) == "0846" ||
			substr($tel,0,4) == "0847" ||
			substr($tel,0,4) == "0848" ||
			substr($tel,0,4) == "0852" ||
			substr($tel,0,4) == "0853" ||
			substr($tel,0,4) == "0854" ||
			substr($tel,0,4) == "0855" ||
			substr($tel,0,4) == "0856" ||
			substr($tel,0,4) == "0857" ||
			substr($tel,0,4) == "0858" ||
			substr($tel,0,4) == "0859" ||
			substr($tel,0,4) == "0863" ||
			substr($tel,0,4) == "0865" ||
			substr($tel,0,4) == "0866" ||
			substr($tel,0,4) == "0867" ||
			substr($tel,0,4) == "0868" ||
			substr($tel,0,4) == "0869" ||
			substr($tel,0,4) == "0875" ||
			substr($tel,0,4) == "0877" ||
			substr($tel,0,4) == "0879" ||
			substr($tel,0,4) == "0880" ||
			substr($tel,0,4) == "0883" ||
			substr($tel,0,4) == "0884" ||
			substr($tel,0,4) == "0885" ||
			substr($tel,0,4) == "0887" ||
			substr($tel,0,4) == "0889" ||
			substr($tel,0,4) == "0892" ||
			substr($tel,0,4) == "0893" ||
			substr($tel,0,4) == "0894" ||
			substr($tel,0,4) == "0895" ||
			substr($tel,0,4) == "0896" ||
			substr($tel,0,4) == "0897" ||
			substr($tel,0,4) == "0898" ||
			substr($tel,0,4) == "0920" ||
			substr($tel,0,4) == "0930" ||
			substr($tel,0,4) == "0940" ||
			substr($tel,0,4) == "0942" ||
			substr($tel,0,4) == "0943" ||
			substr($tel,0,4) == "0944" ||
			substr($tel,0,4) == "0946" ||
			substr($tel,0,4) == "0947" ||
			substr($tel,0,4) == "0948" ||
			substr($tel,0,4) == "0949" ||
			substr($tel,0,4) == "0950" ||
			substr($tel,0,4) == "0952" ||
			substr($tel,0,4) == "0954" ||
			substr($tel,0,4) == "0955" ||
			substr($tel,0,4) == "0956" ||
			substr($tel,0,4) == "0957" ||
			substr($tel,0,4) == "0959" ||
			substr($tel,0,4) == "0964" ||
			substr($tel,0,4) == "0965" ||
			substr($tel,0,4) == "0966" ||
			substr($tel,0,4) == "0967" ||
			substr($tel,0,4) == "0968" ||
			substr($tel,0,4) == "0969" ||
			substr($tel,0,4) == "0972" ||
			substr($tel,0,4) == "0973" ||
			substr($tel,0,4) == "0974" ||
			substr($tel,0,4) == "0977" ||
			substr($tel,0,4) == "0978" ||
			substr($tel,0,4) == "0979" ||
			substr($tel,0,4) == "0980" ||
			substr($tel,0,4) == "0982" ||
			substr($tel,0,4) == "0983" ||
			substr($tel,0,4) == "0984" ||
			substr($tel,0,4) == "0985" ||
			substr($tel,0,4) == "0986" ||
			substr($tel,0,4) == "0987" ||
			substr($tel,0,4) == "0993" ||
			substr($tel,0,4) == "0994" ||
			substr($tel,0,4) == "0995" ||
			substr($tel,0,4) == "0996" ||
			substr($tel,0,4) == "0997"
		){
			$re = substr($tel,0,4) . '-';
			$re .= substr($tel,4,2) . '-';
			$re .= substr($tel,6,4);
			return $re;
		}elseif(
			substr($tel,0,3) == "011" ||
			substr($tel,0,3) == "015" ||
			substr($tel,0,3) == "017" ||
			substr($tel,0,3) == "018" ||
			substr($tel,0,3) == "019" ||
			substr($tel,0,3) == "022" ||
			substr($tel,0,3) == "023" ||
			substr($tel,0,3) == "024" ||
			substr($tel,0,3) == "025" ||
			substr($tel,0,3) == "026" ||
			substr($tel,0,3) == "027" ||
			substr($tel,0,3) == "028" ||
			substr($tel,0,3) == "029" ||
			substr($tel,0,3) == "042" ||
			substr($tel,0,3) == "043" ||
			substr($tel,0,3) == "044" ||
			substr($tel,0,3) == "045" ||
			substr($tel,0,3) == "046" ||
			substr($tel,0,3) == "047" ||
			substr($tel,0,3) == "048" ||
			substr($tel,0,3) == "049" ||
			substr($tel,0,3) == "052" ||
			substr($tel,0,3) == "053" ||
			substr($tel,0,3) == "054" ||
			substr($tel,0,3) == "055" ||
			substr($tel,0,3) == "058" ||
			substr($tel,0,3) == "059" ||
			substr($tel,0,3) == "072" ||
			substr($tel,0,3) == "073" ||
			substr($tel,0,3) == "075" ||
			substr($tel,0,3) == "076" ||
			substr($tel,0,3) == "077" ||
			substr($tel,0,3) == "078" ||
			substr($tel,0,3) == "079" ||
			substr($tel,0,3) == "082" ||
			substr($tel,0,3) == "083" ||
			substr($tel,0,3) == "084" ||
			substr($tel,0,3) == "086" ||
			substr($tel,0,3) == "087" ||
			substr($tel,0,3) == "088" ||
			substr($tel,0,3) == "089" ||
			substr($tel,0,3) == "092" ||
			substr($tel,0,3) == "093" ||
			substr($tel,0,3) == "095" ||
			substr($tel,0,3) == "096" ||
			substr($tel,0,3) == "097" ||
			substr($tel,0,3) == "098" ||
			substr($tel,0,3) == "099"
		){
			$re = substr($tel,0,3) . '-';
			$re .= substr($tel,3,3) . '-';
			$re .= substr($tel,6,4);
			return $re;
		}elseif(
			substr($tel,0,2) == "03" ||
			substr($tel,0,2) == "04" ||
			substr($tel,0,2) == "06"
		){
			$re = substr($tel,0,2) . '-';
			$re .= substr($tel,2,4) . '-';
			$re .= substr($tel,6,4);
			return $re;
		}
	}else{
		return 0;
	}
}

function is_ngword($str)
{
	$ngword_list = $GLOBALS['mysqldb']->query( "SELECT * FROM ngword WHERE status<>1" );
	while ( $list = $ngword_list->fetch_assoc() ) {
		if($list['type']){
			if(stristr($str, $list['name']))	return $str;//type=1,NGWORDが含まれる
		}
		elseif($list['name'] == $str) return $str;//type=0,=NGWORD
	}
	return false;
}
function is_age($value)
{
	$value = mb_convert_kana($value, 'n');
	if(is_numeric($value) && $value>19 && $value<120){
		return $value;
	}else{
		return false;
	}
}
function is_kana($kana){
	$kana = mb_convert_kana($kana, 'KVs');
	if(preg_match("/^(\xe3\x82[\xa1-\xbf]|\xe3\x83[\x80-\xbe]|".
                  "\xa5[\xa1-\xf6]|\xa1[\xb3\xb4\xbc]|".
                  "\x83[\x40-\x96]|\x81[\x52\x53\x5b]|\s)+$/",$kana)){
    	return $kana;
	} else {
	   return false;
	}
}
function user_post(){
	global $gColumnList,$gSex,$gPref,$gAge,$gKeiba_reki,$gKibourenraku,$gJyouhou,$gUrajyouhou,$gPresent,$gKibougesyu,$mo_agent;
	foreach($gColumnList as $key => $val){
		if( $key == "id" || $key == "status" || $key == "adcode" || $key == "mo_agent" || $key == "mo_id")continue;
		elseif($key == "reg_flg") $_POST[$key] =  2;
		elseif($key == "reg_date") $_POST[$key] = date("Y-m-d H:i:s");
		elseif( $val['type'] == "select" || $val['type'] == "radio"){
			foreach ( $val['param'] as $key1 => $val1 ){
				if($_POST[$val['name']] == $val1)$_POST[$key] = $key1;
			}
		}else{
			$_POST[$key] = $_POST[$val['name']];
		}
	}
}
/**
 * 端末IDを取得
 *
 * @package          Mobile
 * @return           string  端末ID(取得出来ない場合はfalseを返却)
 *
 */
function get_mobile_id()
{
    global $mo_agent;
	$strUserAgent = $_SERVER['HTTP_USER_AGENT'];
    //$strHostName = @gethostbyaddr($_SERVER['REMOTE_ADDR']);

    if ( $mo_agent == 1 ) {
        /* DoCoMo */
    	$strMobileId = $_SERVER['HTTP_X_DCMGUID'];
     } elseif ($mo_agent == 3){
        /* SoftBank */
        if ( preg_match("/^.+\/SN([0-9a-zA-Z]+).*$/", $strUserAgent, $match)) {
            $strMobileId = $match[1];
        } else {
            $strMobileId = false;
        }
    } elseif ( $mo_agent == 2 ) {
        /* EzWeb */
        $strMobileId = $_SERVER['HTTP_X_UP_SUBNO'];
    }
    return $strMobileId;

}
/**
  * ファイル名・ユニークID・パスワード生成
  *
  * @param  integer $length  文字列長 default:8 (1-256)
  * @param  string  $mode    モード   default:'alnum'
  * @return array
*/
    function generateID($length = 8, $mode = 'alnum')
    {
         if ($length < 1 || $length > 256) {
             return false;
         }
         $smallAlphabet = 'abcdefghijklmnopqrstuvwxyz';
         $largeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         $numeric       = '0123456789';

         switch ($mode) {

         // 小文字英字
         case 'small':
             $chars = $smallAlphabet;
             break;

         // 大文字英字
         case 'large':
             $chars = $largeAlphabet;
             break;

         // 小文字英数字
         case 'smallalnum':
             $chars = $smallAlphabet . $numeric;
             break;

         // 大文字英数字
         case 'largealnum':
             $chars = $largeAlphabet . $numeric;
             break;

         // 数字
         case 'num':
             $chars = $numeric;
             break;

         // 大小文字英字
         case 'alphabet':
             $chars = $smallAlphabet . $largeAlphabet;
             break;

         // 大小文字英数字
         case 'alnum':
         default:
             $chars = $smallAlphabet . $largeAlphabet . $numeric;
             break;
         }

         $charsLength = strlen($chars);

         $password = '';
         for ($i = 0; $i < $length; $i++) {
             $num = mt_rand(0, $charsLength - 1);
             $password .= $chars{$num};
         }

         return $password;
    }

function define_approot($script_filename, $parental_level = 0)
{
	$src = explode('/', $script_filename);
	array_pop($src);
	$dst = array();
	foreach ($src as $elem)
	{
		switch ($elem)
		{
		case '' :
		case '.' :		break;
		case '..' :	array_pop($dst);	break;
		default :		array_push($dst, $elem);
		}
	}
	$parental_level and $src = array_slice($src, 0, count($src) - $parental_level);
	define(APPROOT, implode('/', $src));
}
    
/**
 * setup session
 * @since 2013/02/07
 * @author ka
 */
function setup_session($name, $value = "")
{
	$sql = "update session set value='".$value."' where name='".$name."'";
	$rtn = $GLOBALS['mysqldb']->query($sql);
}

function get_session($name)
{
	$sql = "select value from session where name='".$name."'";
	$rtn = $GLOBALS['mysqldb']->query($sql);
	if($rtn->num_rows >= 1){
		  $result = $rtn->fetch_row();
	}else $result = "";

	return $result;
}

function get_menu($sales_employee_id){
	global $authority_level;
	// 親メニューの取得
	// 広告専用
	if($authority_level==50)  $pmenu_list = $GLOBALS['mysqldb']->query("select * from menu where pid=0 and status=1 and id in(9,49) order by pid,rank DESC");
	// 会計専用：レジと売上
	elseif($authority_level==51)  $pmenu_list = $GLOBALS['mysqldb']->query("select * from menu where pid=0 and status=1 and (id=3 or id=5) order by pid,rank DESC");
	elseif($authority_level)  $pmenu_list = $GLOBALS['mysqldb']->query("select * from menu where pid=0 and status=1 and authority>={$authority_level} order by pid,rank DESC");
    else $pmenu_list = $GLOBALS['mysqldb']->query("select * from menu where pid=0 and status=1 order by pid,rank DESC");

    while ( $menu_data = $pmenu_list->fetch_assoc() ) {
    	$gMenuPage[$menu_data['id']]['name'] 	= $menu_data['name'];
    	$gMenuPage[$menu_data['id']]['onclick'] = $menu_data['onclick'];
    }

	foreach($gMenuPage as $key=>$val){
      if($authority_level && $authority_level<>50 && $authority_level<>51)  $menu_list = $GLOBALS['mysqldb']->query("select * from menu where status=1 and pid={$key} and authority>={$authority_level} order by rank DESC");
        else $menu_list = $GLOBALS['mysqldb']->query("select * from menu where status=1 and pid={$key} order by rank DESC");
            
        if(!$menu_list->num_rows) continue;
   
        $selected = $val['onclick'] && strpos($_SERVER['SCRIPT_NAME'], $val['onclick']) ? "current" : "select";
        $selected2 = $val['onclick'] && strpos($_SERVER['SCRIPT_NAME'], $val['onclick']) ? " show" : "";
        $param = $val['onclick'] == "reservation" && $_POST['hope_date'] ? "?hope_date=".$_POST['hope_date'] : "";
        $html_menu .='<ul class="'.$selected.'"><li><a href="../'.$val['onclick']. $param.'"><b>'.$val['name'].'</b><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]-->';
        $html_menu .=  '<div class="select_sub'.$selected2.'"><ul class="sub">';

        while ( $sub_val = $menu_list->fetch_assoc() ) {
        	if ($sub_val['onclick'] == "reservation/index.php" && $_POST['hope_date']) {
        		$param2 = "?hope_date=".$_POST['hope_date'];
			//「社販」押下時のパラメータ
			} elseif ($sub_val['onclick'] == "sales/register.php") {
				$param2 = "?customer_id=".$sales_employee_id."&hope_date=".date('Y-m-d');
			} else {
				$param2 = "";
			}
          $sub_selected = $sub_val['onclick'] && strpos($_SERVER['SCRIPT_NAME'], $sub_val['onclick']) ? ' class="sub_show"' : '';
          $html_menu .=  '<li '.$sub_selected.'><a href="../'.$sub_val['onclick'].$param2.'">'.$sub_val['name'].'</a></li>';
        }
        $html_menu .=  '</ul></div><!--[if lte IE 6]></td></tr></table></a><![endif]-->';
        $html_menu .='</li></ul>';
        $html_menu .='<div class="nav-divider">&nbsp;</div>';
    }
    return $html_menu;
}
// Fileアップロード
function Upload_File( $up_name,$tmp_name,$target_dir,$target_name ){
	$pos = strrpos($up_name,".");//拡張子取得
	$ext = substr($up_name,$pos+1,strlen($up_name)-$pos);
	$ext = strtolower($ext);//小文字化
	$new_name = $target_name.".".$ext;
	move_uploaded_file($tmp_name,$target_dir.$new_name);
	return $new_name;
	
}

//DB切り替え用
// function kireimodb(){
// 	$db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
// 		if (!$db_selected){
//     	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
// 		}
// }

//mensDBへの戻り用
function changedb(){
	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
    	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}
}
//mens_kireimo DB用

function getDatalistMens($table,$array0="-",$where=""){

	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
    	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2".$where." order by id" );
	if($sql){
		if($array0) $data_list[0] = $array0;
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

//mens_kireimo DB用

function getDatalist($table,$array0="-",$where=""){

	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
    	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2".$where." order by id" );
	if($sql){
		if($array0) $data_list[0] = $array0;
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

//mens_kireimo shop用
function getDatalist_shop($array0="-",$where=""){

	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
    	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	$sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND assign=0 AND status=2".$where." order by id" );
	if($sql){
		if($array0) $data_list[0] = $array0;
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getDatalist2($table){

	// $db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
	// 	if (!$db_selected){
    // 	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
	// 	}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0 AND assign !=3 AND status=2 order by id" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getDatalist3($table,$first_id){
	if(!$first_id) $first_id=1010;

	// $db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
	// 	if (!$db_selected){
    // 	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
	// 	}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0 AND assign !=3 AND status=2 order by id=".$first_id." desc,name" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getDatalist4($table,$first_id){
	if(!$first_id) $first_id=1010;
	
	// $db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
	// 	if (!$db_selected){
    // 	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
	// 	}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0 order by id=".$first_id." desc,status=2 desc,id" );
	if($sql){
		$data_list[0] = "-";
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

// id=1001 本社スタッフ対応
function getDatalist5($table,$first_id){
	if(!$first_id) $first_id=1010;

	// $db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
	// 	if (!$db_selected){
    // 	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
	// 	}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0 AND (status=2 or id=1001 or id=1002 or id=1003 or id=1004 or id=1005 or id=999) order by id=".$first_id." desc,status=2 desc,id" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getDatalistArray($table,$pid){
	// 店舗リスト

	// $db_selected = $GLOBALS['mysqldb']->select_db('kireimo');
	// 	if (!$db_selected){
  	// die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
	// 	}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2 order by ".$pid.", id" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result[$pid]][$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

// table:m_ad 
function getDatalistArray2($table,$pid){
//adcodeの為mens_kireimoDB
	
	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." order by ".$pid.", name" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result[$pid]][$result['request_id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getDatalistArray3($table,$pid){
	// staffリスト
	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
  	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	$sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2 order by ".$pid.", id" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result[$pid]][$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}


function getStafflistArray($table,$pid,$end_date=""){
	//店舗リスト

	$db_selected = $GLOBALS['mysqldb']->select_db('mens_kireimo');
		if (!$db_selected){
  	die('データベースの読み込みに失敗しました。'.$GLOBALS['mysqldb']->error);
		}

	if($end_date && $end_date<>"0000-00-00") $sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$end_date."') order by ".$pid.", id" );
	else $sql = $GLOBALS['mysqldb']->query( "select * from ".$table." WHERE del_flg = 0  AND status=2 order by ".$pid.", id" );
	if($sql){
		while ( $result = $sql->fetch_assoc() ) {
			$data_list[$result[$pid]][$result['id']] = $result['name'];
		}
		return $data_list;
	}
	return false;
}

function getYobi($date,$format=1){
  $week = array("日", "月", "火", "水", "木", "金", "土");
  $time = strtotime($date);
  $w = date("w", $time);
  
  switch ($format) {
  	case 1:
  		return $date."（".$week[$w]."）";
  		break;
  	case 2:
  		return "（".$week[$w]."）";
  		break;
  	case 3:
  		return $week[$w];
  		break;
  }
}

/**
 * Cookieを取得する条件:
 * 媒体毎に取得、10分以内カウントしない
 * 媒体なし：99999
 * SESSIONがスマホで弱い？GETに変更?
 */
function setKireimoCookie(){

	$cookie_ad = $_SESSION['MENS_AD_CODE'] ? $_SESSION['MENS_AD_CODE'] : 99999;

	if(!array_key_exists($cookie_ad, $_COOKIE)){
		
		//　有効期限３ヶ月,参照元格納
		setcookie($cookie_ad."[MENS_KIREIMO_REFERER_COOKIE]",$_SERVER['HTTP_REFERER'],time()+3600*24*90,"/");
		//　有効期限３ヶ月、広告ID格納
		setcookie($cookie_ad."[MENS_KIREIMO_ADCODE_COOKIE]",$cookie_ad,time()+3600*24*90,"/");
		//　有効期限３ヶ月、アクセス日時格納
		setcookie($cookie_ad."[MENS_KIREIMO_COOKIE_DATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");
		//　有効期限３ヶ月、アクセス日時格納
		setcookie($cookie_ad."[MENS_KIREIMO_COOKIE_LASTDATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");
		//　有効期限３ヶ月、アクセス回数格納
		setcookie($cookie_ad."[MENS_KIREIMO_COOKIE_CNT]",1,time()+3600*24*90,"/");

	// 10分以内なら、カウントしない
	}elseif( ( strtotime("now") - strtotime($_COOKIE[$cookie_ad][MENS_KIREIMO_COOKIE_LASTDATE]) ) > 600 ){ 
		
		// 有効期限３ヶ月、アクセス日時格納
		setcookie($cookie_ad."[MENS_KIREIMO_COOKIE_LASTDATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");
		// 有効期限３ヶ月、アクセス回数+1格納
		setcookie($cookie_ad."[MENS_KIREIMO_COOKIE_CNT]",($_COOKIE[$cookie_ad][MENS_KIREIMO_COOKIE_CNT])+1,time()+3600*24*90,"/");
	}

}

function setOrganicCookie(){
	$cookie_ad = 88888;

	$gSearchDomain = array("yahoo.co.jp","yahoo.com","search.yahoo.co.jp","search.yahoo.com","google.co.jp","google.com","msn.com","bing.com","ask.com");
	$gExcludeKeyword = array("aclk?sa=","cse?q=","/afs/ads/","/url?q=");
	$gLPKeyword = array("lp","back","face","vio","waki");

	$organic_referer = $_SERVER["HTTP_REFERER"];
	$organic_url = parse_url($organic_referer);
	$organic_host = str_replace("www.", "", $organic_url['host']);

	$is_organic = true ;
	if( in_array($organic_host, $gSearchDomain,true)){
		foreach ($gExcludeKeyword as $key => $value) {
			if( strstr($organic_url, $value)){
				$is_organic = false ;
			}
		}
		if($is_organic == true ) {
			if(!array_key_exists($cookie_ad, $_COOKIE)){
		
				// 入口URL
				$lp_flg = 0 ;
				$entrance_url = (empty($_SERVER['HTTPS']) ? 'http://' : "https://").$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
				foreach ($gLPKeyword as $key => $val) {
					if( strstr($_SERVER["REQUEST_URI"], $val)){
						$lp_flg = 1 ;
						break;
					}
				}
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_ENTRANCE_COOKIE]",$entrance_url,time()+3600*24*90,"/");
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_LPFLAG_COOKIE]",$lp_flg,time()+3600*24*90,"/");

				//　有効期限３ヶ月,参照元格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_REFERER_COOKIE]",urldecode($_SERVER['HTTP_REFERER']),time()+3600*24*90,"/");
				//　有効期限３ヶ月、広告ID格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_ADCODE_COOKIE]",$cookie_ad,time()+3600*24*90,"/");
				//　有効期限３ヶ月、アクセス日時格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_COOKIE_DATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");	
				//　有効期限３ヶ月、アクセス日時格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_COOKIE_LASTDATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");
				//　有効期限３ヶ月、アクセス回数格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_COOKIE_CNT]",1,time()+3600*24*90,"/");

			// 10分以内なら、カウントしない
			}elseif( ( strtotime("now") - strtotime($_COOKIE[$cookie_ad][MENS_KIREIMO_ORGANIC_COOKIE_LASTDATE]) ) > 600 ){ 
		
				// 有効期限３ヶ月、アクセス日時格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_COOKIE_LASTDATE]",date("Y-m-d H:i:s"),time()+3600*24*90,"/");
				// 有効期限３ヶ月、アクセス回数+1格納
				setcookie($cookie_ad."[MENS_KIREIMO_ORGANIC_COOKIE_CNT]",($_COOKIE[$cookie_ad][MENS_KIREIMO_ORGANIC_COOKIE_CNT])+1,time()+3600*24*90,"/");
			}
		}
	}
}

function setCSVExportLog($csv_pw,$file_name){
	$ua = $_SERVER['HTTP_USER_AGENT'];

	if (strstr($ua, "iPhone")) {
		$mo_agent = 1;
	}elseif (strstr($ua, "iPad")) {
		$mo_agent = 2; // au
	}elseif (strstr($ua, "Android")) {
		$mo_agent = 3;
	}else{
		$mo_agent = 0; // pc .etc
	}


	$sql = "insert into log_export set ";
	$sql.= "login_id='".$_SESSION['user_id']."',";
	$sql.= "login_pw='".$_SESSION['pw']."',";
	$sql.= "csv_pw='".$csv_pw."',";
	$sql.= "file_name='".$file_name."',";
	$sql.= "ip_addr='".$_SERVER['REMOTE_ADDR']."',";
	$sql.= "mo_agent='".$mo_agent."',";
	$sql.= "mo_id='".$mobile_id."',";
	$sql.= "user_agent='".$ua."',";
	$sql.= "access_date='".date('Y-m-d H:i:s')."'";
	$GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
}

function calMonths($val1,$val2){
	$date1=strtotime($str1);
	$date2=strtotime($str2);
	
	$month1=date("Y",$date1)*12+date("m",$date1);
	$month2=date("Y",$date2)*12+date("m",$date2);

	$diff = $month1 - $month2;
	return $diff;
}

// 未来日と過去日のチェック
// $year,$month,$day 任意の日付（月計算するとき1か月後にするとずれるため''=1をセット）
// $past_num   任意の日付から過去 ～ヶ月［～年］
function monthFormat($month,$type) {
	// 半角数字にそろえる
	$month = mb_convert_kana($month,"a","UTF-8");

	// 1～9月の月は先頭に0をつける
	if($type ==0){
		$month = Che_Num3($month); //いったん先頭の0を削除
		if( $month <>10 && $month <>11 && $month<>12){
			$month = "0".$month;
		}
	} 
	// 1～9月の月は先頭に0をつけない
	elseif($type ==1){
		if(preg_match("/^[0-9]+$/", $month)){
			// 先頭の0を削除する
			$month = $month+0;
		}
	}
	return $month;	
}

// 年号の整形とチェック ※チェックは4桁のみ
// $type=0 4桁⇒2桁の年号 に変換する
// $type=1 2桁⇒4桁の年号に変換する
function yearFormat($year,$type){
	// 半角数字にそろえる
	$year = Che_Num3($year);

	if($type ==1){
	    // 2桁⇒4桁へ変換
		$year = '20'.$year;
		// 年号チェック
		if(3000 < $year || $year < 2001){
		    $year = false;
	    }
	} elseif($type ==0) {
	    // 4桁⇒2桁へ変換
	    $year = substr($year, -2);
	} 
	return $year;
}

// 期日チェック
// $target_ymd 指定した日にち 例)2015/09/20
// $end_ymd 期日           例)2015/10/10
function checkLimitDate($target_ymd,$end_ymd){
    $over_flg= false;
	$target_ymd   = mb_convert_kana($target_ymd,"a","UTF-8");
	$end_ymd     = mb_convert_kana($end_ymd,"a","UTF-8");

	// 期日をセットする
	$target = date("Y/m/d",strtotime($target_ymd));
	$end = date("Y/m/d",strtotime($end_ymd));

	// 期日を過ぎていたらtrue
	if (strtotime($target) < strtotime($end)) {
	  	$over_flg = true;
	} else {
		$over_flg = false;
	}
	return $over_flg;
}

// 未来日と過去日のチェック
// $year,$month,$day 任意の日付（月計算するとき1か月後にするとずれるため''=1をセット）
// $past_num   任意の日付から過去 ～ヶ月［～年］
// $future_num 任意の日付から未来 ～ヶ月［～年］
// $term       月チェック='m'/年チェック='y' 指定する
function checkTerm($year,$month,$day,$past_num,$future_num,$term){
	// dayの指定がなかったら1日をセット
	if($day === ''){
		$day = '01';
	}
	// 過去未来エラーチェック用
	$date_flg   = '';
	// 年月どちらの期間を設定するか
	if($term ==='y' ){
		$term = 'year';
	} elseif($term ==='m'){
		$term = 'month';
	}

	// 数値の場合処理を実施する
	if(is_numeric($year) && is_numeric($month)){
		$month = Che_Num3($month); //先頭の複数の0削除
		
		// 10月以前の月の先頭に0をつける
		if($month < 10){
			$month ='0'.$month;
		} else {
			$month = $month;
		}
		// 月の形式チェック（1～12月以外は'm'の値をセット)
		if($month <=0 || 12 < $month){
			$date_flg = 'm';
			return $date_flg;
		}

		$now_ymd  = date("Y-m").'-'.$day;                                      	   // 今のdate
		$now_ymd_before = date("Y-m-d",strtotime($now_ymd . "-".$past_num. $term)); // 今から前のdate
		$now_ymd_after = date("Y-m-d",strtotime($now_ymd . "+".$future_num.$term)); // 今より後のdate
		$select_ymd = $year.'-'.$month.'-'.$day;                                   // 入力したdate

		// 過去日か未来日が指定されたら 過去'p' 未来'f' の値をセット
		if($select_ymd < $now_ymd_before) {
			$date_flg   = 'p';
		} elseif ($now_ymd_after < $select_ymd){
			$date_flg = 'f';
		}

	} else {
		return false;
	}
	return $date_flg;
}

// 指定した期間 年月 の配列を作る
// $start_ym 例'2014/10'
// $end_ym   例'2015/05'
// $plus_month    例'2' 指定月より2ヶ月先から配列を作る
// $minus_month   例'1' 指定月より1ヶ月前まで配列を作る
// ※上記例だと 2014/12～2015/4月までの配列ができる
function yearMonthArray($start_ym,$end_ym,$plus_month,$minus_month){
	// 年月の開始日　・ 年月の終了日
	$start = strtotime($start_ym.'/01 + '.$plus_month.' month ');
	$end   = strtotime($end_ym.'/01 -'.$minus_month.' month ');

	// 年月の配列を作る
	$ret=array();
	$temp = $end;
	while($temp >= $start){
	$ret[(date('Y/m', $temp))] = date('Y/m', $temp);
	$temp = strtotime('-1 month', $temp);
	}// end while
	// 年月の配列を古い月順に並び替える
	ksort($ret);
	return $ret;
}

// 指定した期間 年月 の配列を作る
// ※月額NG一覧ページ
// yearMonthArrayにほかのフィールドも追加したものです。
// $customer_id   顧客ID
// $no            会員番号
// $name_kana     顧客名(カナ)
// $course_id     コースID
// $contract_date 契約日
function yearMonthArrayList($start_ym,$end_ym,$plus_month,$minus_month,$customer_id,$no,$name_kana,$course_id,$contract_date){
	// 年月の開始日　・ 年月の終了日
	$start = strtotime($start_ym.'/01 + '.$plus_month.' month ');
	$end   = strtotime($end_ym.'/01 -'.$minus_month.' month ');

	// 年月の配列を作る
	$ret=array();
	$temp = $end;
	while($temp >= $start){
	$ret['data'] = array(
		'YearMonth'    =>date('Y/m', $temp),
		'option_year'  =>date('Y', $temp),
		'option_month' =>date('n', $temp),
		'customer_id'  =>$customer_id,
		'no'           =>$no,
		'name_kana'    =>$name_kana,
		'course_id'    =>$course_id,
		'contract_date'=>$contract_date
		);
	$temp = strtotime('-1 month', $temp);
	}// end while
	// 年月の配列を古い月順に並び替える
	//ksort($ret);
	return $ret;
}


/**
 * 重複している配列のキーを返す
 * @param array $array
 * @return array
 */
function detectDuplication(array $array)
{
    $duplications = array();
    foreach ( $array as $index => $value )
    {
        if ( is_integer($value) === false and is_string($value) === false )
        {
            $array[$index] = strval($value);
        }
    }
    foreach ( $array as $value )
    {
        $duplications[$value] = array();
    }
    foreach ( $array as $index => $value )
    {
        $duplications[$value][] = $index;
    }
    foreach ( $duplications as $value => $indexes )
    {
        if ( count($indexes) < 2 )
        {
            unset($duplications[$value]);
        }
    }
    return $duplications;
}

function implodeArray($table,$col,$where){
	$data = Get_Table_Array($table,$col,$where);
	$result = implode(",", $data);
	return $result;
}

/**
* 消化単価の計算
* @param $course_array       コース金額の配列(course_idがキーに入っていること前提)
* @param $times_array        コース回数の配列(course_idがキーに入っていること前提)
* @param $discount_array     コース割引金額の配列(course_idがキーに入っていること前提)
* @return array("unit_price"=>$unit_price_array,"surplus"=>$surplus_sum_array); 消化単価、余りの金額の配列(course_idキーごとに格納)
* @param $card            支払金額(カード)	※任意
* @param $transfer        支払金額(振込)	※任意
* @param $loan            支払金額(ローン)	※任意
* @return すべて下記のキーの配列で格納(キーの中にcourse_idキーを持っているためcourse_idで取り出して使ってください)
*			コースごとに算出→ 消化単価 / 消化単価余りの金額 / 支払額(現金/カード/振込/ローン)
* 			array("unit_price"=>$unit_price_array,"surplus"=>$surplus_sum_array,
* 				  "cash"=>$cash_array,"card"=>$card_array,"transfer"=>$transfer_array,"loan"=>$loan_array);
*/
function Unit_Price_Calculation($course_array,$times_array,$discount_array,$cash=0,$card=0,$transfer=0,$loan=0){
     $sum  = array_sum($course_array);               // コース全体金額合計
     $course_array =array_filter($course_array);     // 空の金額を取り除く
     $course_count =count($course_array);            // コース個数
     $unit_price_sum  =0;                            // 合計割引金額
     $surplus =0;                                    // 余り金額
     $discount = array_sum($discount_array);         // 割引金額合計

     // コース金額合計から
     foreach($course_array as $key => $value){

          // 初期化
          $discount_price  = 0; // コース割引金額
          $unit_price        = array(); // 消化単価

          // 消化単価を計算する（1コース1回あたりの金額）
          $discount_price         = $discount_array[$key];                               // コースごとの割引金額
          $course_price           = $value - $discount_price;                            // 定価-割引
          $unit_price             = floor(Division($course_price,$times_array[$key]));   // (定価-割引)/回数
          $unit_price_sum        += $unit_price * $times_array[$key];                    // ((定価-割引)/回数)*回数

          // 消化単価をKEYごとに配列に入れる
          $unit_price_array[$key] = $unit_price;
     }

     // 消化単価合計 - (コース金額合計 - 割引金額合計)での差分
     $surplus = $unit_price_sum - ($sum - $discount);
     $surplus_sum = 0; // 余りの合計金額

     // 余りの合計金額がコース個数以上の場合、
     // コース個数分余りの合計金額を割り振る
     if($course_count <= $surplus){
          foreach($course_array as $key => $value){
               // あまりの合計金額をコース個数分割り振る
               //  ただし、割り振った余りが整数でなければ切り捨てる
               $surplus_sum_array[$key] = floor(Division($surplus,$course_count));  // コース毎の余り
               $surplus_sum += $surplus_sum_array[$key]; // コース毎の余り合計金額
          }
          // 余りの合計金額 - コース毎の余り合計金額の差分
          $surplus_result = $surplus - $surplus_sum;
          if($surplus_result<>0){
               $first_course_key = key($surplus_sum_array);// 最初のコースキー
               // 最初のコースキーの値に余りの合計金額を加算する
               $surplus_sum_array[$first_course_key] = $surplus_sum_array[$first_course_key] + $surplus_result;
          }
     }
     // 余りの合計金額がコース個数未満の場合、
     // コース最初に余りの合計金額を割り振る
     else {
          $flg = true; // 初回フラグ
          foreach($course_array as $key => $value){
               if($flg){
                    $surplus_sum_array[$key] = $surplus;
                    $flg = false;
               } else {
                    $surplus_sum_array[$key] = 0;
               }

          }
     }

    // 支払金額をコースごとに割り振る
    if($cash<>0 || $card<>0 || $transfer<>0 || $loan<>0){
        $cash_price =0;
        $card_price =0;
        $transfer_price =0;
        $loan_price =0;
        foreach($course_array as $key => $value){
            $cash_price      = floor($cash * Division($value,$sum));      // 現金
            $card_price      = floor($card * Division($value,$sum));      // カード
            $transfer_price  = floor($transfer * Division($value,$sum));  // 振込
            $loan_price      = floor($loan * Division($value,$sum));      // ローン
            // 支払金額をキーに入れる
            $cash_array[$key] = $cash_price;
            $cash_sum += $cash_price;
            $card_array[$key] = $card_price;
            $card_sum += $card_price;
            $transfer_array[$key] = $transfer_price;
            $transfer_sum += $transfer_price;
            $loan_array[$key] = $loan_price;
            $loan_sum += $loan_price;
        }
        // 余った金額を最初のコースのキーに加算する
        $cash_surplus = $cash - $cash_sum;
        reset($cash_array);
        $cash_array[key($cash_array)]=$cash_array[key($cash_array)]+$cash_surplus;
        $card_surplus = $card - $card_sum;
        reset($card_array);
        $card_array[key($card_array)]=$card_array[key($card_array)]+$card_surplus;
        $transfer_surplus = $transfer - $transfer_sum;
        reset($transfer_array);
        $transfer_array[key($transfer_array)]=$transfer_array[key($transfer_array)]+$transfer_surplus;
        $loan_surplus = $loan - $loan_sum;
        reset($loan_array);
        $loan_array[key($loan_array)]=$loan_array[key($loan_array)]+$loan_surplus;
        
        // 消化単価、余りの合計金額を配列で返す（キーはコースID）
        return array("unit_price"=>$unit_price_array,"surplus"=>$surplus_sum_array,"cash"=>$cash_array,"card"=>$card_array,"transfer"=>$transfer_array,"loan"=>$loan_array);
    }
    
     // 消化単価、余りの合計金額を配列で返す（キーはコースID）
     return array("unit_price"=>$unit_price_array,"surplus"=>$surplus_sum_array);
}

/**
* 割り算
* 小数点14桁の位で四捨五入した値を返す
* @param $value1   割られる数
* @param $value2   割る数
*/
function Division($value1,$value2){

     return bcdiv($value1,$value2,14);

}


?>
