<?
$adm.=
"<form id=cmsRlsFrm class=cmsAdm>".
	TXTblocksPub.
	"<table border=0 style=white-space:nowrap>".
		"<tr>".
			"<td><input name=rad type=radio></td><td>Tout le site</td>".
			"<td><input name=rad type=radio onclick=cms.GatherRls()></td><td>".TXTmultBlocks."</td>".
			"<td><input name=rad type=radio checked></td><td>".TXTselectedBlock."</td>".
		"</tr>".
		"<tr>".
			"<td nowrap><input name=quick type=checkbox checked></td><td colspan=4>".TXTflashPub."</td>".
			"<td>".self::imgLnk("javascript:cms.GatherRls()","rls.gif",TXTpubPop,'','margin-left:33px')."</td>".
		"</tr>".
	"</table>".
"</form>".
"<div id=cmsRlsPrg class=cmsAdm>
<div style=margin:5>".self::imgLnk("javascript:cms.HidElm('RlsPrg')","left.png",TXThide,"","float:right")."<b>".TXTpubResume."</b></div>
<table style=margin:5;border-color:orange;white-space:nowrap frame=box rules=all cellspacing=3 cellpadding=3 align=center>
	<tr>
		<td></td>
		<td align=center width=33 title='cmsBlocks'>".TXTblocks."</td>
		<td align=center width=33 title='cmsTreesLang'>".TXTtrees."</td>
		<td align=center width=33 title='cmsLabels'><img src='".OOP_PATH."/img/pgStat.png' border=0></td>
		<td align=center width=33 title='blockIDxLVL'><img src='".OOP_PATH."/img/pgDyn.png' border=0></td>
	</tr>
	<tr>
		<td id=cmsRlsb1 class=tdRed>".TXTdatabase."</td>
		<td id=cmsRlsdat1 align=center></td>
		<td id=cmsRlsdat2 align=center></td>
		<td id=cmsRlsdat3 align=center></td>
		<td id=cmsRlsdat4 align=center></td>
		<td id=cmsRlstim1></td>
	</tr>
</table><br>
<table style='margin:5;border-color:orange;white-space:nowrap' frame=box rules=all cellspacing=3 cellpadding=3>
	<tr>
		<td colspan=2>".TXTfiles."</td>
		<td title='".TXTfilesUnchanged."'><a >".TXTunchanged."</td>
		<td title='".TXTfilesMoved."'>".TXTpublished."</td>
		<td title='".TXTfilesSaved."'>".TXTdeleted."</td>
	</tr>
	<tr>
		<td><img src='".OOP_PATH."/img/htm.gif'></td>
		<td id=cmsRlsb4 class=tdRed>".TXTcache." ( php/ )</td>
		<td id=cmsRlstd8></td>
		<td id=cmsRlstd9 class=tdOrange></td>
		<td id=cmsRlstd10 class=tdRed></td>
	<tr>
	<tr>
		<td><img src='".OOP_PATH."/img/htm.gif'></td>
		<td id=cmsRlsb5 class=tdRed>".TXTrealTime." ( php/rtm/ )</td>
		<td id=cmsRlstd11></td>
		<td id=cmsRlstd12 class=tdOrange></td>
		<td id=cmsRlstd13 class=tdRed></td>
	<tr>
	<tr>
		<td><img src='".OOP_PATH."/img/fld.gif'></td>
		<td id=cmsRlsb6 class=tdRed>".TXTstatic." ( upl/sta/ )</td>
		<td id=cmsRlstd14></td>
		<td id=cmsRlstd15 class=tdOrange></td>
		<td id=cmsRlstd16 class=tdRed></td>
	<tr>
	<tr>
		<td><img src='".OOP_PATH."/img/fld.gif'></td>
		<td id=cmsRlsb7 class=tdRed>".TXTdynamic." ( upl/dyn/ )</td>
		<td id=cmsRlstd17></td>
		<td id=cmsRlstd18 class=tdOrange></td>
		<td id=cmsRlstd19 class=tdRed></td>
	<tr>
</table>
<table>
	<tr id=cmsRlsprogress>
		<td colspan=6 align=center>
			<span id=cmsRlsrlsInfo></span><br>
			<table cellspacing=0 cellpadding=0 border=0 height=5>
				<td bgcolor=green id=cmsRlstoDo></td>
				<td bgcolor=red id=cmsRlsdone></td>
			</table>
		</td>
	</tr>
	<tr><td id=cmsRlstd22 align=center colspan=6></td></tr>
	<!--<tr><td>&nbsp</td></tr>
	<tr><td id=cmsRlsb2 class=tdRed colspan=5 align=center>".TXThtaccess."</td><td id=cmsRlstim2></td></tr>-->
	<tr><td id=cmsRlstd23 align=center colspan=6></td></tr>
	<tr><td>&nbsp</td></tr>
	<tr>
		<td align=right><span id=cmsRlstd20 class=tdRed> </span><span id=cmsRlstd21 class=tdRed> </span></td>
		<td id=cmsRlsb8 class=tdRed colspan=4>".TXTcacheMoved."</td>
		<td id=cmsRlstim8></td>
	<tr>
</table>
</div>".
// le tableau multipleRls
"<form id=cmsRlsMulFrm class=cmsAdm>".
	"<input type=hidden name=ids><input type=hidden name=toDel>".
	"<table align=center>".
		"<td><input id=cmsQckChkBox type=checkbox checked onclick=with(this)form.quick.value=checked?1:0><input type=hidden name=quick value=1></td>".
		"<td>".TXTflashPub."</td>".
		"<td width=55>&nbsp</td>".
		"<td>".self::imgLnk("javascript:cms.MultipleRlsChk()","rls.gif","Publier")."</td>".
	"</table>".
	"<div id=cmsRlsMulDiv style=text-align:center></div>".
"</form>";
?>
