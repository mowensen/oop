<?
	foreach(array("idB"=>"","fld"=>"","op"=>"","fil"=>"","filRen"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	if($op=="upl"){
		$tmpNam=$_FILES["upl"]["tmp_name"];$filNam=$_FILES["upl"]["name"];
		if(file_exists($tmpNam)){
			cms_utils_dirs::makeDir($fld);
			copy($tmpNam,$fld.$filNam);
		}else cms_utils::alert(TXTalrtBadUpl);
	}elseif($op=="ren"){
		rename("$fld/$fil","$fld/$filRen");
	}elseif($op=="del"){
		unlink($fld.$fil);
	}
	list($dirs,$files,$sizes)=@cms_utils_dirs::getDir($fld);
	if(count($files)<1&&is_dir($fld))rmDir($fld);
?>

<script>
	with(top.cms){
		FldFiles=new Array("<?=count($files)?implode('","',$files):""?>");
		FldSizes=new Array("<?=count($sizes)?implode('","',$sizes):""?>");
		<?=$op!="upl"?"":"chkAjxJsFlg=0;SelectedFilNam='$filNam';GotAjxFld()"?>
	}
</script>
