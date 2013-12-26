<?
	foreach(array("idB"=>"","label"=>"","title"=>"","op"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	if($op=="upd"||$op=="del"||$op=="mov"){
		$msg=cms_admin_labels::setLabels($op,$idB,$label,$title);
		if($msg)cms_utils::alert($msg);
	}
	
	if($op!="del"&&$op!="getLblLst"){
		echo cms_admin_labels::getlabel($idB,$label);
	}else{
		$bcks=cms_admin_blocks::lstBlocks('');
		$x=$db->fetchAll("select label,idB from cmsLabels order by idB");
		if(count($x)){
			foreach($x as $y)$lbls[$y["idB"]][]=$y["label"];
			$lst="";foreach($bcks as $k=>$b)if(array_key_exists($k,$lbls))if(count($lbls[$k])){
				$stl="float:right;clear:both;display:".($k!=$idB?"none":"block");
				$lst.=	"<div style=color:black;cursor:pointer;display:table onclick=cms.ShwSubLbl(this)>".
						"<img src=".OOP_PATH."img/add.gif title='".TXTaddLabel."' onclick=cms.InsLbl($k) style=float:right;cursor:pointer;display:none;margin-left:3px>".
						"<span style=padding-right:13px>".mysql_escape_string($b["title"])."</span>".
					"</div>".
					"<table style=$stl>";
					foreach($lbls[$k] as $l)$lst.=	"<tr>".
										"<td onclick=cms.EditInsLbl($k,this.innerHTML) class=a>".mysql_escape_string($l)."</td>".
										"<td><img src=".OOP_PATH."img/drop.gif style=cursor:pointer onclick=cms.DelLbl($k,'$l')></td>".
									"</tr>";
				$lst.=	"</table>";
			}
		}else $lst=	"<table style=float:left>".
					"<td style=color:black;cursor:pointer onclick=cms.ShwSubLbl(this)>".mysql_escape_string($bcks[$idB]["title"])."</td>".
					"<td><img src=".OOP_PATH."img/add.gif title='".TXTaddLabel."' onclick=cms.InsLbl($idB) style=cursor:pointer /></td>".
				"</table>";
		echo $lst;
	}
?>
