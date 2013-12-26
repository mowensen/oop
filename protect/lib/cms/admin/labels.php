<?
	class cms_admin_labels{
		static function getLabels($idB){
			global $db,$lngId,$dfl;
			$cmsLabels=cms_bldWeb_macros::whichTable("cmsLabels");
			$ary=$db->fetchPairs("select $cmsLabels.label,title from $cmsLabels,$cmsLabels"."Lang where $cmsLabels.idB=$idB and $cmsLabels.idB=$cmsLabels"."Lang.idB and $cmsLabels.label=$cmsLabels"."Lang.label and lngId='$lngId'");
			foreach($ary as $k=>$v)if($v=="")$ary[$k]=self::getLabel($idB,$k);
			return $ary;
		}
		static function getLabel($idB,$label){
			global $db,$lngId,$dfl;
			$cmsLabels=cms_bldWeb_macros::whichTable("cmsLabels");
			$wh="where $cmsLabels.label='$label' and $cmsLabels.idB=$idB and $cmsLabels.idB=$cmsLabels"."Lang.idB and $cmsLabels.label=$cmsLabels"."Lang.label";
			$qry="select title from $cmsLabels,$cmsLabels"."Lang $wh";
			$val=$db->fetchOne("$qry and lngId='$lngId'");
			if($val=="")$val=$db->fetchOne("$qry and lngId='$dfl'");
			return $val;
		}
		static function setLabels($op,$idB,$label,$title){
			global $db,$lngId;
			$msg="";$wh=" where label='$label' and idB=$idB";
			$t=$db->fetchOne("select title from cmsLabelsLang $wh and lngId='$lngId'");
			if($op=="upd"){
				if($db->fetchOne("select count(idB) from cmsLabels $wh")<1){
					$nb=$db->fetchOne("select ".cms_db::getFunction("ifnull")."(max(nb)+1,0) from cmsLabels where idB=$idB");
					$db->query("insert into cmsLabels (idB,label,nb)values($idB,'$label',$nb)");
				}
				if($db->fetchOne("select count(idB) from cmsLabelsLang $wh and lngId='$lngId'")<1)
					$db->query("insert into cmsLabelsLang (lngId,idB,label)values('$lngId',$idB,'$label')");
				cms_db::setLangRecord("cmsLabels",array("idB"=>$idB,"label"=>$label),array(),array("title"=>$title));
			}elseif($op=="del"){
				$db->query("delete from cmsLabelsLang $wh");
				$db->query("delete from cmsLabels $wh");
			}
			if($t!=$title){ //traçabilité
				$trace=implode(",",cms_db::fieldValues(cms_db::getTrace()));
				$db->query("update cmsLabels set isModified=1,$trace $wh");
				$db->query("update cmsBlocks set isModified=1,$trace where idB=$idB");
			}
			return $msg;
		}
	}
?>
