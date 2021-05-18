<?php

//BLOG
$blog_list = Get_Result_Sql_Array("SELECT * FROM blog WHERE del_flg=0 AND status=2 ORDER BY blog_date DESC limit 10");
if($blog_list ){
  foreach($blog_list as $key => $val){
	echo '<li>
            <strong><a '.($val['url_nofollow'] ? 'rel="nofollow"' : '').' href="'.str_replace("http://","//",$val['url']).'" '.($val['url_blank'] ? 'target="_blank"' : '').'><img src="'.IMG_DIR.'thumb_'.$val['img_name'].'" width="55" height="55" alt="" ></a></strong>
            <div>
                <span>'.$val['blog_date'].'</span>
                    <p><a '.($val['url_nofollow'] ? 'rel="url_nofollow"' : '').' href="'.$val['url'].'" '.($val['url_blank'] ? 'target="_blank"' : '').'>'.$val['title'].'</a></p>
            </div>
         </li>';
  }
}
?>