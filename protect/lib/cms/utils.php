<?
	class cms_utils{
		static function alert($txt){
			echo '<script>alert("'.str_replace(array("\n","\r",'"'),array("\\n","","''"),$txt).'")</script>';
		}
		static function lngSel($ok){
			$cfg = new Zend_Config_Ini(APP_PATH.'/config.ini', ENV);$lngs=$cfg->lng;
			$t="";foreach($lngs as $k=>$v)$t.="<option value=$k".($k!=$GLOBALS["lngId"]?"":" selected").">".($ok?$k:$v);
			return "<select id=cmsLngSel onchange=\"with(this)location.replace('?lngId='+options[selectedIndex].value)\">$t</select>";
		}
	}
?>
