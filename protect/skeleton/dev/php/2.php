<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your website Title</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="description" content="¤cmsLbl3 description¤" />
	<meta name="keywords" content="¤cmsLbl3 keywords¤" />
	<link rel="stylesheet" type="text/css" href="<?=ENV_PATH?>upl/sta/2/main.css" />
	<script src="<?=OOP_PATH?>js/utils/ajx.js"></script>
	<?=strstr($html,'calendar.show(')?'<script src="'.OOP_PATH.'/js/utils/calendar.js"></script>':''?>
	<?=strstr($html,'chkFrm.addChecker(')?'<script src="'.OOP_PATH.'/js/utils/checkForm.js"></script>':''?>
</head>
	<body>
		<cmsContent>
	</body>
</html>
