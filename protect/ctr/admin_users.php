<?
	foreach(array("user"=>"","group"=>"","pass"=>"","op"=>"","blocks"=>"","icons"=>"","lngs"=>"","ips"=>"","interface"=>"") as $k=>$v)
		$$k=cms_bldWeb::getVar($k,$v);
	if($op=="add"||$op=="upd"||$op=="del"){
		if(GRP!='admins'){$user=USR;$group=GRP;}
		$msg=cms_admin_users::setDigestAccount($op,$user,$group,$pass,$interface,$blocks,$icons,$lngs,$ips);
		if($msg)cms_utils::alert($msg);
	}
	if($user==""){$user=USR;$group=GRP;}//if first time show the user
	if(GRP=='admins')echo cms_admin_users::shwUsers($user,$group);
?>
