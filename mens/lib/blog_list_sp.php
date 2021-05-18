<?php

//BLOG
$blog_list = Get_Result_Sql_Array("SELECT * FROM blog WHERE del_flg=0 AND status=2 ORDER BY blog_date DESC ");
if($blog_list ){
  foreach($blog_list as $key => $val){
	echo '<li>
            <a '.($val['url_nofollow'] ? 'rel="nofollow"' : '').' href="'.str_replace("http://","//",$val['url']).'" '.($val['url_blank'] ? 'target="_blank"' : '').' >
              <strong><img src="'.IMG_DIR.$val['img_name'].'" alt="" width="100%" ></strong>
              <div class="text">
                <p class="date">'.$val['blog_date'].'</p>
                <p>'.$val['comment'].'</p>
              </div>
            </a>
         </li>';
  }
}
?>
