<?
	class cms_admin_blocks{
		static function lstBlocks($idB,$rls=0){
			global $BLOCKS;
			$blockTree=cms_db_trees::getTreeArray("blocks",$idB,($rls?"rls_C":"c")."msTreesLang");
			if(!is_array($BLOCKS))$BLOCKS=self::fetchAll();
			$ary=array();
			foreach($blockTree as $a){
				$idB=$a["idT"];$ary[$idB]=array_merge(array("nb"=>$a["nb"],"title"=>$a["title"],"tree"=>$a["tree"]),$BLOCKS[$idB]);
				if($a["next"])$ary+=self::lstBlocks($idB);
			}
			return $ary;
		}
		static function shwBlocks($idB){
			global $BLOCKS;
			$blockTree=cms_db_trees::getTreeArray("blocks",$idB);
			if(!is_array($BLOCKS))$BLOCKS=self::fetchAll();
			$html=	(array_key_exists("admBlock",$_COOKIE)?"<script>cms.SelectedBlock=".$_COOKIE['admBlock']."</script>":"").
				"<div id=cmsTree$idB".($idB!=""?" class=cmsSubTree":"").">".
					"<span id=cmsDropBefore style=display:none;position:absolute>après</span>".
					"<span id=cmsDropAfter style=display:none;position:absolute>après</span>".
					"<span id=cmsDropBelow style=display:none;position:absolute>après</span>";
			foreach($blockTree as $a){
				$idB=$a["idT"];$b=$BLOCKS[$idB];
				$img=	"<table cellspacing=0 cellpadding=0>".
					"<td width=7>".($b["cache"]?"<img src=".OOP_PATH."img/mrq/cac.png>":'')."</td>".
					"<td width=7>".($b["realTime"]?"<img src=".OOP_PATH."img/mrq/rtm.png>":'')."</td>".
					"<td width=7>".($b["fld"]?"<img src=".OOP_PATH."img/mrq/fld.png>":'')."</td>".
					"<td width=7>".($b["frame"]?"<img src=".OOP_PATH."img/mrq/frm.png>":'')."</td>".
					"<td width=7>".($b["lbl"]?"<img src=".OOP_PATH."img/mrq/sta.png>":'')."</td>".
					"<td width=7>".($b["fields"]?"<img src=".OOP_PATH."img/mrq/dyn.png>":'')."</td>".
					"</table>";
				$okAdm=((USR_BLS=='all'||strstr(" ".USR_BLS. " "," $idB "))&&SHW_USR_LNS);
				$html.=	"<table cellspacing=0 border=0 ".
						"onmouseout=\"document.getElementById('cmsTreeShw$idB').style.display='none'\" ".
						"onmouseover=\"document.getElementById('cmsTreeShw$idB').style.display='block'\">".
						"<td valign=middle bgcolor=white><div style=width:41px>$img</div></td>".
						"<td nowrap>".
							"<a id=cmsBlock$idB title=$idB ondrop=cms.DropBlock(this) ondragover='return false' ondragenter=\"return false\" ".
							($okAdm?"href=\"javascript:\" style=cursor:move".($b["isMenu"]?'':';color:orange').
								" tree=".$a["tree"]." sub=".($a["next"]?1:0)." isMenu=".($b["isMenu"]?1:0).
								" onclick=cms.ShwBlock(this) ondragstart=cms.DragBlock(this)"
								:
								"style=font-style:italic;opacity:0.3;cursor:default").
								">".
									$a["title"].
							"</a>".
						"</td>".
						"<td>&nbsp;".
							($b["isModified"]=='1'?
							"<img src=".OOP_PATH."img/rls.gif style=cursor:pointer;margin-bottom:-2px".
							($okAdm?
							" onclick=\"if(confirm('Etes vous sûr de vouloir publier le bloc $idB'))cms.DoRls(1,$idB,'')\""
							:";opacity:0.3;cursor:default").
							" />"
							:"").
						"</td>".
						"<td><div style=width:11px><img id=cmsTreeShw$idB src=".OOP_PATH."img/ey.png style=display:none;cursor:pointer onclick=\"location='?block=$idB'\"></div></td>".
					"</table>".
					($a["next"]?self::shwBlocks($idB):"");
			}
			$html.="</div>";
			return $html;
		}
		static function setBlocks($op,$idB,$tree,$toIdB,$toTree,$title,$after){
			global $db,$idB; //need idB to add label content and set isMnu in ctr/admin_blocks.php
			if($op=="add"){
				$id1=$idB;
				$idB=cms_db_trees::addOption($tree,$title,$idB,$after);
				$db->query("insert into cmsBlocks (idB)values($idB)");
				if($after==2)$toTree="blocks-$id1";else $toTree=$tree;
			}elseif($op=="del"){
				$BCKS=cms_admin_blocks::lstBlocks($idB);$BCKS[$idB]=1;
				foreach($BCKS as $idB=>$b){
					cms_db_trees::delOption($tree,$idB);
					$db->query("delete from cmsBlocks where idB=$idB");
					$db->query("delete from cmsLabelsLang where idB=$idB");
					$db->query("delete from cmsLabels where idB=$idB");
					cms_utils_dirs::delDir(DEV_PATH."upl/sta/$idB");
					cms_utils_dirs::delDir(DEV_PATH."upl/dyn/$idB");
				}
			}elseif($op=="mov"){
				cms_db_trees::movOption($tree,$idB,$toTree,$toIdB,$after);
			}
			if($op!="del"){
				cms_db::updTrace("cmsTreesLang","idT=$idB and tree='$toTree'");
				cms_db::updTrace("cmsBlocks","idB=$idB");
			}
		}
		static function fetchAll(){
			global $db;
			$blocks=$db->fetchAll("select * from cmsBlocks");
			foreach($blocks as $b){
				$k=array_shift($b);
				$u[$k]=$b;
				$u[$k]["realTime"]=file_exists(ENV_PATH."php/rtm/$k.php")?1:0;
				$u[$k]["cache"]=file_exists(ENV_PATH."php/$k.php")?1:0;
				$u[$k]["frame"]=($u[$k]["cache"]?cms_bldWeb_macros::checkFrames(file_get_contents(ENV_PATH."php/$k.php")):0);
				$u[$k]["lbl"]=$db->fetchOne("select count(idB) from cmsLabels where idB=$k");
				$u[$k]["fld"]=is_dir(DEV_PATH."upl/sta/$k");
			}
			return $u;
		}
		static function updateCmsBlocks($tree,$idB,$qry,$qryTtl){
			global $db,$lngId;
			if(count($qry))$db->query("update cmsBlocks set ".implode(",",$qry)." where idB=$idB");
			//cms_utils::alert("update cmsTreesLang set $qryTtl where tree='$tree' and idT=$idB and lngId='$lngId'");
			if($qryTtl)cms_db_trees::updOption($tree,$qryTtl,$idB);
			//$db->query("update cmsTreesLang set $qryTtl where tree='$tree' and idT=$idB and lngId='$lngId'");
		}
		static function checkboxes($idB){
			$blockTree=cms_db_trees::getTreeArray("blocks",$idB);$html="<div id=cmsChkBlocks style=margin-left:".($idB!=""?33:9)."px>";
			foreach($blockTree as $a)
				$html.=	"<table cellspacing=1 border=0>".
						"<td valign=middle bgcolor=white><input type=checkbox value=".$a["idT"]."></td>".
						"<td nowrap>".$a["title"]."</td>".
					"</table>".
					($a["next"]?self::checkboxes($a["idT"]):"");
			return "$html</div>";
		}
	}
?>
