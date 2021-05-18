<?php include_once("../library/service/extension_contract.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<!--必須項目チェック-->
<!-- <script type='text/javascript' src='../js/jquery.js'></script> -->
<script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>

</head>
<body>

  <div class="clear"></div>
<!-- start content-outer -->
<div >
	<!-- start content -->
	<div id="content">

		<div id="content-table">
				<!--  start content-table-inner -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
      <tbody>
        <tr>
          <th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt=""></th>
          <th class="topleft"></th>
          <td id="tbl-border-top"></td>
          <th class="topright"></th>
          <th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt=""></th>
        </tr>
        <tr>
          <td id="tbl-border-left"></td>
          <td>
    				<div id="content-table-inner" style="padding:0;">
    								<form action="../library/service/extension_contract.php" method="post" id="extension_form" name="extension_form">
    									<input type="hidden" name="action" value="edit" />
                      <input type="hidden" name="extension_flg" value="1">
                      <input type="hidden" name="end_date" value="<?php echo $post_end_date ;?>">
                      <h1>保証期間延長処理</h1>
                      <style type="text/css" media="screen">
                        #extension_box{
                          margin-top: 20px;
                        }
                        .extension_title{
                          width: 30%;
                        }
                        .extension_name{
                          font-size: 16px !important;
                          font-weight: bold;
                        }
                      </style>
                      <dl id="extension_box" class="half w350">
                        <dt class="extension_title">名前</dt>
                        <dd class="extension_name"><?php echo $_GET['name'];?></dd>
                        <dt class="extension_title">会員番号</dt>
                        <dd><?php echo $_GET["customer_no"];?></dd>
                        <dt class="extension_title">契約コース</dt>
                        <dd><?php echo $_GET['course_name'];?></dd>
                        <dt class="extension_title">契約期間</dt>
                        <dd><?php echo $contract_period;?></dd>
                        <?php if(!$extension_flg){ ?>
                          <dt class="extension_title">延長保証期間</dt>
                          <dd><?php echo $period ;?></dd>
                          <dt class="extension_title"></dt>
                          <dd>
                            <input type="button" value="登録する" class="submit" id="submit">
                          </dd>
                        <?php } ?>
                      </dl>
                      <div class="half">
                        <div id="result<?php if($extension_flg === "1"){echo "1";}; ?>">
                          <div id="related-activities">
                            <!--  start related-act-top -->
                            <div id="related-act-top">
                              <div class="title">出力</div>
                            </div>
                            <!-- end related-act-top -->
                            <!--  start related-act-bottom -->
                            <div id="related-act-bottom">
                              <!--  start related-act-inner -->
                              <div id="related-act-inner">
                                <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt=""></a></div>
                                <div class="right">
                                  <h5>
                                    <a id="print" class="side">保証期間延長申請書</a>
                                  </h5>
                                </div>
                                <div class="clear"></div>
                              </div><!-- end related-act-inner -->
                              <div class="clear"></div>
                            </div><!-- end related-act-bottom -->
                          </div>
                        </div>
                      </div>
    								</form>
    				</div>
          </td>
          <td id="tbl-border-right"></td>
        </tr>
        <tr>
          <th class="sized bottomleft"></th>
          <td id="tbl-border-bottom">&nbsp;</td>
          <th class="sized bottomright"></th>
        </tr>
      </tbody>
    </table>
				<!--  end content-table-inner  -->
		</div>
    <div id="loading_box">
      <div id="loading">
        <div class="cssload-loading">
          <i></i>
          <i></i>
          <i></i>
          <i></i>
        </div>
      </div>
    </div>
  <style type="text/css" media="screen">
    #result{
      visibility: hidden;
    }
  </style>
  <script type="text/javascript">
    $(function(){
      function extension_contract_load(button){
        var $button;
          $button = $("#" + button),
        $button.on("click",function(){
          var res = confirm("<?php echo $_GET['name'] ?>様の\n保証期間を延長してよろしいですか？");
          if(res == true){
            extension_ajax("extension_form","print","loading_box","result");
          }else{
            return false;
          }
        })
      }
      function extension_ajax(form_id,target,loading_box,result){
        var $target,loading;
          $target = $("#" + target),
          loading = document.getElementById(loading_box);
          result = document.getElementById(result);
          loading.style.display = "block";
          var alldata,data1,full_url;
          alldata = document.getElementById(form_id),
          data1 = new FormData(alldata);
          $.ajax({
            url:"../library/service/extension_contract.php<?php echo $pdf_param6 ?>",
            type:"post",
            dataType:"html",
            data:data1,
            processData: false,
            contentType: false
          }).done(function(response){
            loading.style.display = "none";
            result.style.visibility = "visible";
            $target.html("保証期間延長申請書");
            print($target,response);
            alert("登録完了");
            //console.log(response);
          }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            loading.style.display = "none";
            $target.html("読み込みに失敗しました。");
          })
      }
      function print($target,url){
        $target.on("click",function(){
          window.open(('../pdf/pdf_out6.php'+ url), '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');
          location.reload();
          return false;
        });
      }
      <?php if($extension_flg === "1"){ ?>
        var $target = $("#print");
        print($target,"<?php echo $pdf_param6 ?>");
      <?php }else{ ?>
        extension_contract_load("submit");
      <?php } ?>
    })
  </script>
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->


</body>
</html>