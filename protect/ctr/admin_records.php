<?
	foreach(array("idB"=>"","idR"=>"","op"=>"","fields"=>"","uid"=>"","uidP"=>"","isForm"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	if($op=="sav"){ // sauve l'architecture d'une table
		cms_admin_records::saveFields($idB,trim($fields),$isForm);
		$op="chkTbl";
	}
	if($op=="del"){
	}
	if($op=="getForm"){
		$x=explode('_',$uid);$idB=array_shift($x);
		$f=explode("\n\n",$db->fetchOne("select fields from cmsBlocks where idB=$idB"));$ids=explode("\n",$f[0]);
		$wh=array();for($i=0;$i<count($x);$i++)if(trim($x[$i])!='')$wh[]=trim($ids[$i])."=".$x[$i];
		echo cms_bldWeb_records::bldForm($idB,$uid,$uidP,$wh);
	}
	if($op=="chkTbl"){
		list($RecLnk,$isVrt,$isForm,$flds,$fldsLang,$trees,$form)=cms_admin_records::checkRecords($idB);
		echo	"<script>
				with(cms.Elm('RecFrm')){isVrt.value=$isVrt;isForm.value=$isForm}
				cms.Elm('isVrt').checked=$isVrt;
				cms.Elm('isForm').checked=$isForm;
				var sel=cms.Elm('RecLnkSel');
				sel.innerHTML=\"".mysql_escape_string($RecLnk)."\";
				sel.style.height=Math.min(9,sel.options.length)*12+'px';
				cms.Elm('Fields').innerHTML=\"".mysql_escape_string($flds)."\";
				cms.Elm('FieldsLang').innerHTML=\"".mysql_escape_string($fldsLang)."\";
				cms.Elm('RecTrees').innerHTML=\"".mysql_escape_string($trees)."\";
				cms.ShwRec();
			</script>";
	}
?>
