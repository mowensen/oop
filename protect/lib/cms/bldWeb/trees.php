<?
	class cms_bldWeb_trees{
		static function makeTree($idB,$tree,$idTree,$type,$selectedId=''){
			$display=$selectedId!=''?'table':'none';
			return	
				(SHWADM?"<span id=cmsTree-$tree onmouseover=\"this.firstChild.nextSibling.style.display='table'\" style=white-space:nowrap>":"").
					(SHWADM?"<span>":"").
							self::makeField($idB,$tree,$idTree,$type,$selectedId,1).
					(SHWADM?"</span>".
						"<span style=\"display:$display\" class=cmsTree onmouseout=\"this.style.display='none'\">".
							"<span id=cmsTreeSpn-$tree class=cmsTreeMnu>".
								cms_admin::imgLnk("javascript:cms.DoTree('mov','$idB','$tree','$idTree',0)",'up.gif',TXTup).
								cms_admin::imgLnk("javascript:cms.DoTree('mov','$idB','$tree','$idTree',1)",'dwn.gif',TXTdown).
								cms_admin::imgLnk("javascript:cms.DoTree('addTree','$idB','$tree','$idTree',2)",'sub.png',TXTsub).
								cms_admin::imgLnk("javascript:cms.DoTree('upd','$idB','$tree','$idTree')",'ren.gif',TXTrename).
								cms_admin::imgLnk("javascript:cms.DoTree('add','$idB','$tree','$idTree')",'add.gif',TXTadd).
								cms_admin::imgLnk("javascript:cms.DoTree('del','$idB','$tree','$idTree')",'drop.gif',TXTdelete).
							"</span>".
							self::tree($tree,$idTree,$type,$selectedId).
						"</span>".
					"</span>"
				:"");
		}
		static function makeField($idB,$tree,$idTree,$type,$selectedId,$selted){
			$lst=cms_db_trees::getTreeArray($tree,$idTree);
			if(count($lst)<1)$lst=array(''=>array('idT'=>0,'title'=>'new option','next'=>''));
			$nm=$tree.$idB."[-$idTree"."]";
			$html="";$inf="";$display=$selted?'inline':'none';
			if($type=="checkbox"||$type=="radio"){
				$html.=	"<div style=display:$display id=cms$tree-$idTree>";
				foreach($lst as $k=>$b){
					$idT=$b["idT"];$selted1=strstr(" $selectedId "," $idT ");
					$html.="<input type=$type name=$nm"."[] value=$idT".($selted1?" checked":" opt$nm"."[$idT"."]")." onclick=\"try{with(this)if(checked){try{cms$tree"."_.style.display='none'}catch(e){};cms$tree"."_=document.getElementById('cms$tree-'+value);cms$tree"."_.style.display='inline'}}catch(e){}\">".$b["title"];
					if($b["next"])$inf[$idT]=$selted1;
				}
				$html.=	"</div>";
			}elseif(substr($type,0,6)=="select"){
				$html.="<$type name=$nm"."[] style=display:$display id=cms$tree-$idTree onmouseup=\"try{with(this){try{cms$tree"."_.style.display='none'}catch(e){};cms$tree"."_=document.getElementById('cms$tree-'+options[selectedIndex].value);cms$tree"."_.style.display='inline'}}catch(e){}\">";
				foreach($lst as $k=>$b){
					$idT=$b["idT"];$selted1=strstr(" $selectedId "," $idT ");
					$html.="<option value=$idT".($selted1?" selected":" opt$nm"."[$idT"."]").">".$b["title"];
					if($b["next"])$inf[$idT]=$selted1;
				}
				$html.="</select>";
			}
			if($inf)foreach($inf as $id=>$ok)$html.=self::makeField($idB,$tree,$id,$type,$selectedId,$ok);
			return $html;
		}
		static function tree($tree,$idTree,$type,$selectedId=''){
			$lst=cms_db_trees::getTreeArray($tree,$idTree);
			if(count($lst)<1)$lst=array(''=>array('idT'=>0,'title'=>'new option','next'=>''));
			$html="<div>";
			$nb=$nbSelected=0;$inf=$idT='';foreach($lst as $k=>$b){
				$id1=$idT;$idT=$b["idT"];
				$html.=	"<div id=cmsTree-$tree-$idT".
						(GRP!="guests"?" onclick=\"cms.TreeChged(this,'$tree','$idTree',$idT,$nb,".count($lst).",'$id1','$type')\" style=cursor:pointer":"").
						">".
						$b["title"].
					"</div>";
				if($idT==$selectedId)$nbSelected=$nb;$nb++;
				if($b["next"])$html.=self::tree($tree,$idT,$type,$selectedId);
			}
			$html.="</div>";
			return $html;
		}
	}
?>
