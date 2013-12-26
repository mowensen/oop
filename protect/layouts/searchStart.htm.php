<table align=center bgcolor="#f7f7f7" height=100%>
	<td>
		<table align=center>
			<tr>
				<td><?=TXTstatic?></td><td><input name=static value="<?=$static?>" size=33></td>
				<td><?=TXTlabels?></td><td><input name=staticLabels value="<?=str_replace("-",",",$staticLabels)?>" size=33></td>
			</tr>
			<tr>
				<td><?=TXTdynamic?></td><td><input name=dynamic value="<?=$dynamic?>" size=33></td>
				<!--<td>Do not search in</td><td><input name=noDynamic value="<?//=$noDynamic?>" size=33></td>-->
			</tr>
			<tr>
				<td><?=TXTfolders?></td><td><input name=rpcDirs value="<?=$rpcDirs?>" size=33></td>
				<td><?=TXTfiles?></td><td><input name=rpcFiles value="<?=$rpcFiles?>" size=33></td>
			</tr>
		</table>
		<table>
			<tr>
				<td colspan=4><input name=searchIpt value="<?=$searchIpt?>" size=79></td>
				<td align=center>
					<input type=button value=Search onclick="with(this.form){elements['op'].value='search';cms.Search()}">
				</td>
			</tr>
			<tr>
				<td align=right>
					<input name=rec type=hidden value=<?=($rec!="on"?"off":"on")?>>
					<input type=checkbox <?=($rec!="on"?"":"checked")?>  onclick="this.form.elements['rec'].value=this.checked?'on':'off'">
				</td>
				<td><?=TXTrecurse?></td>
				<td align=right>
					<input name=case type=hidden value=<?=($case!="i"?"on":"off")?>>
					<input type=checkbox<?=($case?"":" checked")?> onclick="this.form.elements['case'].value=this.checked?'on':'off'">
				</td>
				<td><?=TXTcaseSensitive?></td>
			</tr>
			<tr>
				<td colspan=4><input name=replaceIpt value="<?=$replaceIpt?>" size=79></td>
				<td align=center>
					<input type=button value=Replace onclick="if(confirm('No undo here. To save database, use backup.To save files, use ftp.')){with(this.form){elements['op'].value='replace';cms.Search()}}">
				</td>
			</tr>
		</table>
	</td>
</table>

