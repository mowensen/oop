<?
$adm.=	"<div id=cmsUsrDiv class=cmsAdm>".
		"<form id=cmsUsrFrm>".
			"<table align=center>".
				"<tr>".
					"<td colspan=5>".
						self::imgLnk("javascript:cms.HidElm('UsrDiv')","left.png",TXThide,'','float:right').
						"<b>".TXTcompte."</b>".
					"</td>".
				"</tr>".
				"<tr>".
					"<td>".TXTgroup."</td><td><input name=group tabindex=1></td>".
					"<td>".TXTuser."</td><td><input name=user tabindex=2></td>".
				"</tr>".
				"<tr>".
					"<td>".TXTpass."</td><td><input type=password name=pass tabindex=3></td>".
					"<td>".TXTpass."</td><td><input type=password name=pass1  tabindex=4></td>".
					"<td>".self::imgLnk("javascript:cms.ChkUsrFrm()","save.gif",TXTok,"tabindex=6")."</td>".
				"</tr>".
			"</table>".
			"<br>".
			"<table>".
				"<tr><td colspan=5><b>".TXTcaracteristics."</b></td></tr>".
				"<tr>".
					"<td align=right>".TXTinterfaceLng." </td><td> </td>".
					"<td><select name=interface tabindex=5><option value=fr>fr<option value=en>en<option value=zh>zh</select></td>".
				"</tr>".
			"</table>".
			"<input type=hidden name=op>".
			"<br>".
			"<table align=center".(GRP!='admins'?' style=display:none':'').">".
				"<tr><td colspan=2><b>".TXTprivileges."</b></td></tr>".
				"<tr>".
					"<td valign=top align=left>";
				// blocks
				$adm.= cms_admin_blocks::checkboxes('')."<input type=hidden name=blocks>";
				$adm.=	"</td>".
					"<td width=33>&nbsp;</td>".
					"<td valign=top align=center>";
					// IPS
					$adm.="IP restrisction<br><input name=ips><br><br>";
					// languages
					$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', 'dev');$devLng=$cfg->lng;
					$adm.="<select multiple name=lngsSel style=height:".(12.3*count($devLng))."px>";
							foreach($devLng as $k=>$v)$adm.="<option value=$k>".trim($v);
					$adm.="</select><input type=hidden name=lngs><br><br><br>";
					// menus
					$adm.="<div id=cmsUsrIcos>";
					//$ar=explode(",","0eye.gif,0bck.png,1users.png,1prop.png,0search.gif,1AJX,1SQL");
					$ar=explode(",","0edit.gif;oe;Edition en ligne");
					$ary="";foreach($ar as $a)$ary[substr($a,1)]=substr($a,0,1);// niveaux
					foreach($ary as $x=>$n){
						list($a,$v,$b)=explode(";",$x);
						$lnk=$a;if(strstr($a,"."))$lnk="<img src=".OOP_PATH."img/$a>";
						$adm.=	"<table style=margin-left:".($n*15)."px>".
								"<td><input type=checkbox value='$v'></td>".
								"<td title=\"$b\">$lnk</td>".
							"</table>";
					}
					$adm.="<input type=hidden name=icons></div>";
		$adm.=			"</td>".
				"</tr>".
			"</table>".
		"</form>".
	"</div>";
?>
