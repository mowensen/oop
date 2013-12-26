<?
	foreach(array("idB"=>"","tree"=>"","fld"=>"","op"=>"","title"=>"","content"=>"","isMenu"=>"","fil"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	$fil=(substr($fld,strlen($fld)-1,1)!="/"?$fld:"dev/$fld$idB.php");
	$content=trim($content);
	if($op=="get"){
		$lst=$scr=$lbls=$bcks=$scr="";
		if(file_exists($fil))echo file_get_contents($fil);
	}elseif($op=="sav"){
		if($content!=""){
			if($content!=file_get_contents($fil)){
				cms_utils_files::save($fil,$content);
				if($idB!="")cms_db::updTrace("cmsBlocks","idB=$idB");
			}
		}elseif(array_key_exists("content",$_POST)) unlink($fil);
		// Vérification des champs de la table cmsBlocks
		$qry=array();$qryTtl="";$flds=explode(",","title,isMenu,labels");
		foreach($flds as $f)
			if(isset($_POST[$f])){
				$q=substr($f,0,2)!="is"?"'":"";$t="$f=$q".$$f.$q;
				if($f!="title")$qry[]=$t;else $qryTtl=$$f;
			}
		if(count($qry)||$qryTtl)cms_admin_blocks::updateCmsBlocks($tree,$idB,$qry,$qryTtl);
	}
?>
