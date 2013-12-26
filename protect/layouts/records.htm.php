<?
$sel=	"<select onclick=\"cms.RecInsTyp(this,cms.Elm('Fields'))\" size=9 style=color:green;cursor:pointer;height:96px>";
		$opt=explode(",","text char(255),pass char(33),file char(33),textarea text,tree char(99),checkbox tinyint(1),time timestamp,standard");
		foreach($opt as $o)$sel.="<option>$o";
$sel.=	"</select>";
$adm.=	"<form id=cmsRecFrm style=margin:3px>".
		"<div id=cmsRecHlp style=display:none;position:absolute;padding:3px class=cmsAdm>".
			self::imgLnk("javascript:cms.HidElm('RecHlp')","left.png","Aide","","float:right").
			"<b>".TXTidentities."</b> (".TXThorVerExt."):".
			"<div style=margin-left:33px>".
				TXTdefaultId." id<edt></edt> ".TXTisFormed.".<br>".
				TXTextendTable."<edt></edt>.<br>".
				TXTcreateSubTable." id<edt></edt>.<br>".
			"</div>".
			"<b>Créer vos champs</b>:".
			"<div style=margin-left:33px>".
				"Créer vos champs dans la zone éditable au format:".
					"<div style=margin-left:33px>".
						TXTname."1 input1 (type1)<br>".
						TXTname."2 input2 (type2)<br>".
						"<br>".
						TXTname." inputN (typeN)<br>".
					"</div>".
				TXTcoupleInputName.
			"</div>".
			"<b>Est un formulaire:</b>".
			"<div style=margin-left:33px>".
				TXTprivateData.
			"</div>".
			"<b>Suppressions:</b>".
			"<div style=margin-left:33px>".
				TXTdelTables.
			"</div>".
		"</div>".
		self::imgLnk("javascript:cms.HidElm('RecDiv')","left.png",TXThide,"","float:right;margin-top:2px").
		self::imgLnk("javascript:cms.ShwElm('RecHlp')","hlp.png",TXThelp,"","float:right;margin-top:1px").
		self::imgLnk("javascript:cms.chkRecFrm()","save.gif",TXTok,"","float:right").
		"<table cellspacing=0 cellpadding=0>".
			"<td>&nbsp;&nbsp;<b style=color:black>".TXTnewsForms."</b></td>".
			"<td style=padding-left:44px><input id=cmsisForm type=checkbox onchange=this.form.isForm.value=this.checked?1:0></td>".
			"<td>".TXTisForm.".<input type=hidden name=isForm></td>".
		"</table>".
		"<table cellspacing=0 cellpadding=0>".
			"<td valign=top>".
				"<div class=cmsFre>".
				"&nbsp;<b style=color:black>".TXTnonLanguageDependant."</b>".
				"<table cellspacing=5 border=0>".
					"<tr>".
						"<td rowspan=2>".TXTkeys."</td><td>".TXTidIfChecked."</td>".
						"<td>id<edt></edt> int(11)</td>".
						"<td>".
							"<input type=checkbox id=cmsisVrt checked onchange=cms.chkVrt(this)>".
							"<input type=hidden name=isVrt>".
						"</td>".
					"</tr>".
					"<tr>".
						"<td>".TXTparentId."</td>".
						"<td colspan=2>".
							"<div id=cmsOtherKeys></div>".
							"<select multiple id=cmsRecLnkSel style=float:left;height:33px;></select>".
						"</td>".
					"</tr>".
					"<tr><td colspan=2>Order</td><td>nb int(11)</td></tr>".
					"<tr>
						<td>".TXTeditFields."</td>
						<td>$sel<br><select id=cmsRecTrees></select></td>
						<td bgcolor=white colspan=2>
							<textarea id=cmsFields style='width:150px;height:109px;border:1px solid gray' onfocus=\"with(this){style.height=scrollHeight+'px';style.width=scrollWidth+'px'}\" onblur=\"this.style.height='109px'\"></textarea>
						</td>
					</tr>".
					"<tr><td rowspan=3>".TXTtrace."</td><td>utilisateur</td><td colspan=2>xuser char(15)</td></tr>".
					"<tr><td>IP</td><td colspan=2>xip char(15)</td></tr>".
					"<tr><td>".TXTdate."</td><td colspan=2>xdate timestamp</td></tr>".
				"</table>".
				"</div>".
				"<div class=cmsFre>".
				"&nbsp;<b style=color:black>".TXTlanguageDependant."</b>".
				"<table cellspacing=3>".
					"<tr><td rowspan=3>".TXTkeys."</td><td colspan=2>".TXTallKeys."</td></tr>".
					"<tr><td style=text-align:center>+</td></tr>".
					"<tr><td>".TXTlanguageKey."</td><td>lngId char(2)</td></tr>".
					"<tr>
						<td>Editer vos champs</td><td>".str_replace('Fields','FieldsLang',$sel)."</td>
						<td bgcolor=white width=33 colspan=2>
							<textarea id=cmsFieldsLang style='width:150px;height:99px;border:1px solid gray' onfocus=\"with(this){style.height=scrollHeight+'px';style.width=scrollWidth+'px'}\" onblur=\"this.style.height='99px'\"></textarea>
						</td>
					</tr>".
				"</table>".
				"</div>".
			"</td>".
			"<td valign=top>".
		"<div id=cmsRecMacDiv border=0 style=display:none;margin:auto>".
			"<div class=cmsFre>".
				"<div><b style=color:black>Tags de listing des enregistrements</b></div><br>".
				"<div style=color:green;cursor:pointer onclick=\"cms.RecFrmIns(cms.PstMac(cms.Elm('RecMacLst')))\">".TXTpasteAll."</div><br>".
				"<div style=display:table>".
					"<div class=cmsMacTag onclick=cms.EditIns(this.innerHTML)>&lt;cmsRec<edt></edt>></div>".
					"<div class=cmsMacFld>".TXTbeginListing." <edt></edt></div>".
				"</div>".
				"<div id=cmsRecMacLst>";
			foreach(array("len"=>"len","nb"=>"nb") as $m=>$t)
				$adm.=	"<div style=display:table>".
						"<div class=cmsMacElm onclick=cms.EditIns(this.innerHTML)>¤cmsRec<edt></edt> $m"."¤</div>".
						"<div class=cmsMacFld>$t</div>".
					"</div>";
			$adm.=		"<span id=cmsRecMac></span>".
				"</div>".
				"<div style=display:table>".
					"<div class=cmsMacTag onclick=cms.EditIns(this.innerHTML)>&lt;/cmsRec<edt></edt>></div>".
					"<div class=cmsMacFld>".TXTendListing." <edt></edt></div>".
				"</div>".
			"</div>".
			"<div id=cmsRecMacFrm class=cmsFre>".
				"<div><b style=color:black>".TXTrecordingForm."</b></div><br>".
				"<div style=margin-left:15px>".
					TXTmailTo.
					"<div style=color:green;cursor:pointer onclick=\"cms.RecFrmIns(cms.PstMac(cms.Elm('RecMacSpn')))\">".TXTpasteAll."</div><br>".
				"</div>".
				"<span id=cmsRecMacSpn></span>".
			"</div>".
			"<div id=cmsRecMacFds class=cmsFre>".
				"<div><b style=color:black>Les champs de ce bloc insérable dans le formulaire</b></div><br>".
				"<div style=margin-left:15px>".
					TXTcompulsoryInput.".<br><br>".
					"<div style=color:green;cursor:pointer onclick=\"cms.RecFrmIns(cms.PstMac(cms.Elm('RecMacSpnFds')))\">".TXTpasteAll."</div>".
				"</div>".
				"<span id=cmsRecMacSpnFds></span>".
			"</div>".
		"</div>".
			"</td>".
		"</table>".
	"</form>";
?>
