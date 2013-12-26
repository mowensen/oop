<?
$adm.="
<form id=cmsSchFrm method=post class=cmsAdm onsubmit=\"return false\">
	<input type=hidden name=do value=admin_search>
	<input type=hidden name=op>
	<div style=margin:3pxclass=cmsAdm>".
		self::imgLnk("javascript:cms.HidElm('SchFrm')","left.png",TXThide,"","float:right").
		self::imgLnk("javascript:cms.ShwElm('SchHlp')","hlp.png",TXThelp,"","float:right").
		"&nbsp;<b>Search / Replace</b>
		<span id=cmsSchHlp class=cmsAdm style=position:absolute;display:none;:1px solid navy;color:blue;font-size:12px;padding:5px;margin-top:9px;margin-left:55px;width:633px>".
			self::imgLnk("javascript:cms.HidElm('SchHlp')","left.png",TXThide,"","float:right").
			TXTsearchHlp1."<br>
			<div style=padding-left:333px;display:block;visibility:visible;position:relative>
				<font color=red><?=ENV_PATH?>php/</font> (".TXTcache.")<br>
				<font color=red><?=ENV_PATH?>php/rtm/</font>(".TXTrealTime.")<br>
				<font color=red><?=ENV_PATH?>upl/sta/</font> (".TXTstatic.")<br>
				<font color=red><?=ENV_PATH?>upl/dyn/</font> (".TXTdynamic.")<br>
			</div>
			<br>".
			TXTsearchSep.
			"<br>
			Search entry:
			<div style=padding-left:55px;display:block;visibility:visible;position:relative>".
			TXTsearchHlp2.
			"</div>
			<br>".
			TXTsearchHlp3.
		"</span>
		<span id=cmsSchSpn></span>
	</div>
</form>";
?>
