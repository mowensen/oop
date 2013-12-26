<?
	foreach(array("idB"=>"","tree"=>"","toidB"=>"","toTree"=>"","op"=>"","title"=>"","after"=>1,"isMnu"=>"","content"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	$blocks=cms_admin_blocks::fetchAll();
	if($op=="add"||$op=="upd"||$op=="del"||$op=="mov"){
		$msg=cms_admin_blocks::setBlocks($op,$idB,$tree,$toidB,$toTree,$title,$after);
		if($op=="add"){
			if($content)cms_utils_files::save(DEV_PATH."php/$idB.php","<cmsLbl$idB content>");
			if($isMnu)$db->query("update cmsBlocks set isMenu=1 where idB=$idB");
			$_COOKIE["admBlock"]=$idB;
		}
		if($msg)cms_utils::alert($msg);
	}
	echo cms_admin_blocks::shwBlocks('');
?>
