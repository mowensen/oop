<pxlaintext>
<?
	foreach(array("op"=>"","attr"=>"","attrVal"=>"","rpcDirs"=>"","rpcFiles"=>"","static"=>"","staticLabels"=>"","searchIpt"=>"","dynamic"=>"","replaceIpt"=>"","rec"=>"","case"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	echo cms_admin_search::shw($op,$attr,$attrVal,$rpcDirs,$rpcFiles,$static,$staticLabels,$searchIpt,$dynamic,$replaceIpt,$rec,$case);
?>
