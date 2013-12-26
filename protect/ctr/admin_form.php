<?
	foreach(array("idB"=>"","op"=>"","fields"=>"","isForm"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	if($op=="sav"){
		cms_admin_records::saveFields($idB,trim($fields),$isForm);
	}
?>
