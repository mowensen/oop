<?if($op){?>
	<table align=center>
		<tr style="color:red">
			<td><?=count($rlsIds)?> <?=TXTblocks?> <?=($op=="search"?TXTfound:TXTchanged)?></td>
			<td width=33>&nbsp;</td>
			<td><?=$cSta?> <?=TXTstaticLabels?> <?=($op=="search"?TXTfound:TXTchanged)?></td>
			<td width=33>&nbsp;</td>
			<td><?=$cDyn?> <?=TXTdynamicLabels?> <?=($op=="search"?TXTfound:TXTchanged)?></td>
			<td width=33>&nbsp;</td>
			<td><?=$cTpl?> <?=TXTfiles?> <?=($op=="search"?TXTfound:TXTchanged)?></td>
		</tr>
		<tr>
			<td valign=top><?=@implode("<br>",$rlsIds)?></td>
			<td></td>
			<td valign=top><?=str_replace(",","<br>",$staticFound)?></td>
			<td></td>
			<td valign=top><?=str_replace(",","<br>",$dynamicFound)?></td>
			<td></td>
			<td valign=top><?=str_replace(",","<br>",$foundFiles)?></td>
		</tr>
	</table>
<?}?>

