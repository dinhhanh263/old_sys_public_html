<?php //phpinfo(); 
$smpartpit_info['smpartpit_no']='2082288382564';
$smpartpit_no = substr($smpartpit_info['smpartpit_no'],0,3)." ".
					substr($smpartpit_info['smpartpit_no'],3,3)." ".
					substr($smpartpit_info['smpartpit_no'],6,3)." ".
					substr($smpartpit_info['smpartpit_no'],9);
echo $smpartpit_no;
phpinfo();
?>