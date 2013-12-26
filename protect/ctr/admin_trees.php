<?
	foreach(array("op"=>"","title"=>"","id"=>"","idB"=>"","tree"=>"","idTree"=>"","toId"=>"","toTree"=>"","after"=>1,"type"=>"select") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	$selectedId=$id;
	if($op=="del"){
		cms_db_trees::delOption($tree,$id);
	}elseif($op=="upd"){
		cms_db_trees::updOption($tree,$title,$id);
	}elseif($op=="addTree"){
		cms_db_trees::addTree($tree,$id,$title);
	}elseif($op=="mov"){
		if($toTree==""){$toId=$id;$toTree=$tree;}
		cms_db_trees::movOption($tree,$id,$toTree,$toId,$after);
	}elseif($op=="add"){
		$selectedId=cms_db_trees::addOption($tree,$title,$id,$after);
	}
	$x=explode("-",$tree);$tree=$x[0];
	$sel=cms_bldWeb_trees::makeTree($idB,$tree,"",$type,$selectedId);
	$sel=substr($sel,strpos($sel,">")+1);
	$sel=substr($sel,0,strpos($sel,"</span></span>")+7);
	echo $sel;
?>
