<?
	foreach(array("op"=>"","dev"=>"","prd"=>"","fil"=>"","auto"=>"","rwt"=>"","every"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	if($op=="sav"){
		$msg=cms_admin_props::setPropLng($op,$dev,$prd);
		if($msg)cms_utils::alert($msg);
	}elseif($op=="dump"){
		cms_db_backup::dumpDb();
	}elseif($op=="restore"&&$fil){
		cms_db_backup::restoreDb($fil);
	}elseif($op=="auto"){
		cms_admin_props::setPropAuto($auto,$every);
	}elseif($op=="savRwt"){
		cms_admin_props::setPropRwt($rwt);
	}
	echo cms_admin_props::shwProps();
?>
