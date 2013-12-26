<?
if(array_key_exists("inc",$_POST)){
	include "oop/protect/ctr/utils.php";
}else{?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<script src="oop/js/utils/ajx.js"></script>
		<script src="oop/js/utils/calendar.js"></script>
	</head>
	<body>
		This demo needs to be put on top of oop directory
		<div style=display:table><input id=date1 name=date1 type=hidden><img src='oop/img/cal.gif' onclick=calendar.show(this,'date1','da') style=cursor:pointer></div>
		<div style=display:table><input id=date2 name=date2 type=hidden><img src='oop/img/cal.gif'  onclick=calendar.show(this,'date2','') style=cursor:pointer></div>
	</body>
</html>
<?}?>
