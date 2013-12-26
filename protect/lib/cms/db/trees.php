<?
	class cms_db_trees{
		static function allTreeArray($tree,$idT){
			$ary=$lst=self::getTreeArray($tree,$idT);
			foreach($lst as $a)if($a["next"])$ary=array_merge($ary,self::allTreeArray($a["tree"],$a["idT"]));
			return $ary;
		}
		static function getTreeArray($tree,$idT,$cmsTreesLang="cmsTreesLang"){
			global $db,$lngId,$dfl;
			$tree=self::treeRoot($tree);
			$trees=$db->fetchAll("select idT,tree,nb,title from $cmsTreesLang where tree='$tree$idT' and lngId='$dfl' order by nb");
			if($lngId!=$dfl){// on met à jour la langue de title
				$ttl=$db->fetchPairs("select idT,title from $cmsTreesLang where tree='$tree$idT' and lngId='$lngId'");
				if(count($trees))foreach($trees as $k=>$ary)if(array_key_exists($ary["idT"],$ttl))$trees[$k]["title"]=$ttl[$ary["idT"]];
			}
			// on vérifie pour chacun des résultats s'il existe une sous arborescence
			foreach($trees as $b=>$a)$trees[$b]["next"]=$db->fetchOne("select title from $cmsTreesLang where tree='$tree".$a["idT"]."' and lngId='$dfl'");
			return $trees;
		}
		static function addOption($tree,$title,$idT,$after){
			global $db,$dfl,$lngId;
			if($after!=2){//ajout avant ou après la cible
				$nb=$db->fetchOne("select nb from cmsTreesLang where tree='$tree' and idT=$idT and lngId='$dfl'");$nb+=$after;
			}else{	//ajout au niveau inférieur de la cible
				$nb=0;$tree=self::treeRoot($tree).$idT;
			}
			$db->query("update cmsTreesLang set nb=nb+1 where tree='$tree' and nb>=$nb");
			$idT=self::getidT($tree);
			$db->query("insert into cmsTreesLang (tree,lngId,idT,nb,title) values ('$tree','$dfl',$idT,$nb,'$title')");
			cms_db::updTrace("cmsTreesLang","tree='$tree' and lngId='$dfl' and idT=$idT");
			if($lngId!=$dfl)self::updOption($tree,$title,$idT);
			return $idT;
		}
		static function updOption($tree,$title,$idT){
			global $db,$dfl,$lngId;
			$wh=$wh0="idT=$idT and tree='$tree' and lngId=";$wh.="'$lngId'";$wh0.="'$dfl'";
			if($db->fetchOne("select idT from cmsTreesLang where $wh")==""){
				$nb=$db->fetchOne("select nb from cmsTreesLang where $wh0");
				$db->query("insert into cmsTreesLang (lngId,idT,tree,nb)values('$lngId',$idT,'$tree',$nb)");
			}
			$db->query("update cmsTreesLang set title='$title' where $wh");
			cms_db::updTrace("cmsTreesLang",$wh);
		}
		static function addTree($tree,$id,$title){
			global $db,$dfl;
			$idT=self::getidT($tree);$tree=self::treeRoot($tree);
			$db->query("insert into cmsTreesLang (tree,lngId,idT,nb,title) values ('$tree$id','$dfl',$idT,0,'$title')");
			cms_db::updTrace("cmsTreesLang","tree='$tree' and lngId='$dfl' and idT=$idT");
		}
		static function movOption($tree,$idT,$toTree,$toidT,$after){
			global $db,$dfl;
			$nb=$db->fetchOne("select nb from cmsTreesLang where tree='$tree' and idT=$idT and lngId='$dfl'");
			$db->query("update cmsTreesLang set nb=nb-1 where tree='$tree' and nb>$nb");
			if($after!=2){//déplacement avant ou après la cible
				$toNb=$db->fetchOne("select nb,tree from cmsTreesLang where tree='$toTree' and idT=$toidT and lngId='$dfl'");
				$toNb=$toNb+$after;
				$db->query("update cmsTreesLang set nb=nb+1 where tree='$toTree' and nb>=$toNb");
			}else{	//déplacement au niveau inferieur de la cible
				$toNb=0;$toTree=self::treeRoot($tree).$toidT;
			}
			$wh="tree='$tree' and idT=$idT";
			$db->query("update cmsTreesLang set tree='$toTree',nb=$toNb where $wh");
			cms_db::updTrace("cmsTreesLang",$wh);
		}
		static function delOption($tree,$idT){
			global $db,$dfl;
			$substring=cms_db::getFunction("substring");$len=strlen($tree);
			$nb=$db->fetchOne("select nb from cmsTreesLang where tree='$tree' and idT=$idT and lngId='$dfl'");
			$db->query("update cmsTreesLang set nb=nb-1 where tree='$tree' and nb>$nb");
			$lst=self::allTreeArray($tree,$idT);$lst[]=array("idT"=>$idT,"tree"=>$tree);
			foreach($lst as $k=>$a)$db->query("delete from cmsTreesLang where tree='".$a["tree"]."' and idT=".$a["idT"]);
		}
		static function getidT($tree){
			global $db,$dfl;
			$treeRoot=self::treeRoot($tree);
			$substring=cms_db::getFunction("substring");
			$len=strlen($treeRoot);
			return $db->fetchOne("select ".cms_db::getFunction("ifnull")."(max(idT)+1,0) from cmsTreesLang where $substring(tree,1,$len)='$treeRoot' and lngId='$dfl'");
		}
		static function treeRoot($tree){
			return strstr($tree,"-")?substr($tree,0,strpos($tree,"-")+1):"$tree-";
		}
		static function getOption($tree,$idT){
			global $db,$dfl;
			return $db->fetchOne("select title from cmsTreesLang where tree='$tree' and idT=$idT and lngId='$dfl'");
		}
	}
?>
