<?
$mnuPrm=array(	"lvl"	=>TXTmnuLvl,
			"nb"		=>TXTmnuNb,
			"idB"	=>TXTmnuId,
			"title"	=>TXTmnuTitle,
			"selected"=>TXTmnuSelected,
			"pth"	=>TXTmnuPth,
			"hrf"	=>TXTmnuHrf,
			"len"	=>TxtmnuLen);
$mnuGbl=array(	"pth"	=>$mnuPrm["pth"],
			"hrf"	=>$mnuPrm["hrf"],
			"len"	=>$mnuPrm["len"],
			"title"	=>$mnuPrm["title"]);
$sitemap="
<cmsMnu>
	<div style=\"margin:3px;margin-left:33px;padding:3px;border:1px solid black;display:table\">
			<div>".TXTmnuLvl.": ¤cmsMnu lvl¤</div>
			<div>".TXTmnuNb.": ¤cmsMnu nb¤</div>
			<div>".TXTmnuId.": ¤cmsMnu idB¤</div>
			<div>".TXTmnuTitle.": ¤cmsMnu title¤</div>
			<div>".TXTmnuSelected.": ¤cmsMnu selected¤</div>
			<div>".TXTmnuPth.": ¤cmsMnu pth¤</div>
			<div>".TXTmnuHrf.": ¤cmsMnu hrf¤</div>
			<div>".TXTmnuPth.": ¤cmsMnu len¤</div>
			<cmsMnuNext>
	</div>
</cmsMnu>";
$adm.=	self::imgLnk("javascript:cms.HidElm('MnuDiv')","left.png",TXThide,"","float:right;margin:3px").
	"<form id=cmsMnuFrm>".
		"<b>".TXTmnuMacros."</b>".
		"<table border=0 style=white-space:nowrap>".
			"<tr>".
				"<td colspan=3>".
					TXTpasteSitemap.
					"<div id=cmsSitMap style=display:none>".str_replace("<","&lt;",$sitemap)."</div>".
				"</td>".
			"</tr>".
			"<tr><td colspan=3>(".TXTcmsMnu0.")</td></tr>".
			"<tr><td style=color:orange;cursor:pointer onclick=cms.EditInsert('Mnu0','')>&lt;cmsMnu0></td><td colspan=2>".TXTopeningMnuTag."</td></tr>";
	foreach($mnuPrm as $m=>$t)$adm.="<tr><td></td><td style=color:orange;cursor:pointer onclick=cms.EditIns(this.innerHTML)>¤cmsMnu $m"."¤</td><td>$t</td></tr>";
$adm.=			"<tr><td style=color:orange;cursor:pointer onclick=cms.EditInsert('/Mnu0','')>&lt;/cmsMnu0></td><td colspan=2>".TXTclosingMnuTag."</td></tr>".
			"<tr><td style=color:orange;cursor:pointer onclick=cms.EditInsert('MnuNext','')>&lt;cmsMnuNext></td><td colspan=2>".TXTnextMnu."</td></tr>".
			"<tr><td colspan=3>".TXTidentifiedBlocks."</td></tr>";
	foreach($mnuGbl as $m=>$t)$adm.="<tr>".
						"<td style=color:orange;cursor:pointer onclick=\"if(x=prompt('".TXTgiveIdentity."'))cms.EditIns(this.innerHTML.replace('IDBLOCK',x))\">¤cmsMnuIDBLOCK $m"."¤</td>".
						"<td></td>".
						"<td>$t</td>".
					"</tr>";
$adm.=		"</table>".
		"<br><b>".TXTlngMacros."</b>".
		"<table border=0 style=white-space:nowrap>".
			"<tr><td style=color:orange;cursor:pointer onclick=cms.EditInsert('Lang','')>&lt;cmsLang></td><td colspan=2>".TXTopeningLngTag."</td></tr>".
			"<tr><td></td><td style=color:orange;cursor:pointer onclick=\"cms.EditIns(this.innerHTML)\">¤cmsLang id"."¤</td><td></td><td>en, fr, etc...</td></tr>".
			"<tr><td></td><td style=color:orange;cursor:pointer onclick=\"cms.EditIns(this.innerHTML)\">¤cmsLang name"."¤</td><td></td><td>English,Français, etc...</td></tr>".
			"<tr><td style=color:orange;cursor:pointer onclick=cms.EditInsert('/Lang','')>&lt;/cmsLang></td><td colspan=2>".TXTclosingLngTag."</td></tr>".
		"</table>".
	"</form>";
?>
