<?php include_once("../library/reservation/one_course_length.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>KIREIMO SYSTEM</title>
<link rel="shortcut icon" href="../images/favicon.ico">
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default">
<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>

<script type="text/javascript" src="../js/auto.jquerykana.js"></script>
<!--  checkbox styling script -->
<script src="../js/jquery/ui.core.js" type="text/javascript"></script>
<script src="../js/jquery/ui.checkbox.js" type="text/javascript"></script>
<script src="../js/jquery/jquery.bind.js" type="text/javascript"></script>
<!-- Custom jquery scripts -->
<script src="../js/jquery/custom_jquery.js" type="text/javascript"></script>
</style>
</head>
<!-- ここまで -->
  <!--  start content-table  -->
	<div id="content_time_table">
    <h2>1回コース・パーツ一覧</h2>
    <ul id="result">
      <li class="total_time">目安時間：<span id="total_time" class="under_line"></span>分</li>
      <li>1回コース数：<span id="checked_course">0</span></li>
      <li>カスタマイズ部位数：<span id="checked_parts">0</span></li>
    </ul>
    <span class="alert">※選べる〇ヵ所パックの部位は選択できません。</span>
				<!--  start product-table ..................................................................................... -->
    <div id="course_time_table">
      <table id="course_time_table_inner">
				<tr>
            <th class="th">コース名 / パーツ</th>
            <th class="th">時間</th>
				</tr>
        <?php
        if ( $dRtn3->num_rows >= 1 ) {
        	$i = 1;
        	while ( $data = $dRtn3->fetch_assoc() ) {
        		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
                      echo  '<td><label class="for_parts"><input type="checkbox" class="checked_course" value="'.$data['id'].'">'.$course_list[$data['id']].'</label></td>';
                      echo  '<td class="course_time">'.$data['part_length'].'</td>';
        		echo '</tr>';
        		$i++;
        	}
        }
        if ( $dRtn32->num_rows >= 1 ) {
          $i = 1;
          while ( $data2 = $dRtn32->fetch_assoc() ) {
            echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
                      echo  '<td><label class="for_parts"><input type="checkbox" class="checked_parts">'.$parts_list[$data2['id']].'</label></td>';
                      echo  '<td class="course_time">'.$data2['part_length'].'</td>';
            echo '</tr>';
            $i++;
          }
        }
        ?>
			</table>
    </div>
    <span id="parts_result_btn">希望箇所一覧</span>
    <span class="alert">※コピーして予約表記載へ入力してください。</span>
    <textarea id="important_remark"></textarea>
    <script src="../js/reservation/one_course_length.js" type="text/javascript" charset="utf-8" async defer></script>
				<!--  end product-table................................... -->
	</div>
	<!--  end content-table  -->
</body>