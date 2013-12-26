<?
	function getTree($rls,$idB=""){
		global $db,$devBcks,$okRls;
		$blockTree=cms_db_trees::getTreeArray("blocks",$idB,($rls?"rls_C":"c")."msTreesLang");
		$blocks=$db->fetchAssoc("select * from ".($rls?"rls_C":"c")."msBlocks");
		$html=	"<div".($idB!=""?" class=cmsSubTree":"").">";
		foreach($blockTree as $a){
			$idB=$a["idT"];
			$b=$blocks[$idB];$ok=(!$rls&&$b["isModified"])||($rls&&!strstr($devBcks,",$idB,"));if($ok)$okRls=1;
			$html.=	"<table cellspacing=0 border=0>".
					"<td>".
						"<input type=checkbox ".
							"onchange=\"cms.RlsToggleVal(this,'".($rls?'toDel':'ids')."',$idB)\"".
							($ok?"":" style=display:none").">".
					"</td>".
					"<td nowrap>".$a["title"]." ($idB)</td>".
				"</table>".
				($a["next"]?getTree($rls,$idB):"");
		}
		$html.=	"</div>";
		return $html;
	}
	$x=$db->fetchCol("select idB from cmsBlocks");$devBcks=",".implode(",",$x).",";
	$okRls=0;$dev=getTree(0);$prd=getTree(1);
	echo 	(!$okRls?"<span style=color:red>".TXTnoneModified."</span>":"").
		"<table><td valign=top class=cmsFre>".TXTtoPublish."$dev</td><td valign=top class=cmsFre>".TXTpublished."$prd</td></table>";
?>

