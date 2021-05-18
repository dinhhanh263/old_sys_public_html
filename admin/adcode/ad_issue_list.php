<?php include_once('../library/adcode/ad_issue_list.php');?>
<?php include_once('../include/header_menu.html');?>

    		<div id="main_list">
    		  <table width="1013" cellpadding="0" cellspacing="0">
    		  
    		    <tr id="title_bar"><td colspan="10">広告コード発行</td></tr>

<?php if ( $dRtn->num_rows >= 1 ) {
  while ( $agent_data = $agent->fetch_assoc() ) {
    //代理店別に、未発行広告コードだーたを取得
    $aSql = "SELECT * FROM `adcode` WHERE `agent_id`='".$agent_data['id']."' and `status`=0 order by id desc";
    $adcode = $GLOBALS['mysqldb']->query( $aSql ) or die('query error'.$GLOBALS['mysqldb']->error);
		
    if ( $adcode->num_rows >= 1 ) {
?>
 		    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post">
			  <input name="mode" type="hidden" id="mode" value="issue">
			  <input name="id" type="hidden" id="id" value="<?php echo($agent_data['id']); ?>">
			  
    		  <tr class="bgb" id="aaa-2">
    		      <td width="10%">選択</td>
    		      <td width="20%">媒体名</td>
    		      <td width="60%">広告に設定するURL</td>
    		      <td width="10%">代理店名</td>
  		      </tr>
<?php while ( $data = $adcode->fetch_assoc() ) {?>	
			   <tr class="bgb" id="aaa-2">
   		          <td><input type="checkbox" name="checkboxName[<?php echo $data['id']?>]" /></td>
   		          <td><?php echo($data['name']); ?></td>
   		          <td><?php echo(  HOME_URL."?adcode=".$data['adcode']  ); ?></td>
   		          <td><?php echo $agent_data['name'] ?></td>
  		        </tr>
<?php }?>
			    <tr class="bgb2" id="aaa-2">
    		        <td colspan="4">
    		        	<input type="submit" value="発行済みにする" onClick="return edit_data_issue(this.form);" />&nbsp;&nbsp;
    		        	<input type="submit" value="コード発行" onclick='return issue(this.form);' />
    		        </td>
  		        </tr>				
			  </form>
<?php
		}
	}
}else{
	echo('<tr><td colspan="3">未発行件数0件</td></tr>');
}
?>
	</table>
		</div><!-- end main_list -->

<?php include_once('../include/footer.html') ?>