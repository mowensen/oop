<?
	foreach(array("users"=>"","tables"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	list($tbles,$usrs,$ary)=cms_db::getTraces($tables,$users);
	$t='';
	$t.="<select name=tables onchange=cms.ShwTraces() style=margin-left:99px><option value=''>Tables";foreach($tbles as $tbl)$t.="<option value=$tbl".($tables!=$tbl?'':' selected').">$tbl";$t.="</select>";
	$t.="<select name=users onchange=cms.ShwTraces() style=margin-left:99px><option value=''>Users";foreach($usrs as $usr)$t.="<option value=$usr".($users!=$usr?'':' selected').">$usr";$t.="</select>";
	foreach($ary as $tbl=>$ar)$t.="<div>$tbl<div style=margin-left:33px>".implode('<br>',$ar).'</div></div>';
	echo $t;
?>
