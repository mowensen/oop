<?
	class cms_bldWeb_macros{
		static function checkFrames($html){
			return stristr($html,"<cmscontent>")?1:0;
		}
		static function checkMacros($html){
			global $BCKS;
			$tags=explode(",","block,lbl,frame,tree,lang");
			foreach($tags as $tg){
				$t="cms$tg";$f="bld$tg";
				while(stristr($html,"<$t")||stristr($html,"¤$t")){
					$cnt="";
					if(stristr($html,"<$t")){$sep1="<";$sep2=">";}else $sep1=$sep2="¤";
					list($id,$qs,$bef,$aft)=self::sepStartTag($html,$t,$sep1,$sep2);
					if(stristr($html,"</$t"))list($cnt,$aft)=self::sepEndTag($aft,$t);
					$html=self::$f($id,$qs,$bef,$aft,$cnt,$sep1);
				}
			}
			$html=cms_bldWeb_menus::bldMnuLvl($html,"",0);
			$html=cms_bldWeb_records::bldRecords($html);
			return $html;
		}
		static function sepStartEndTags($html,$t){
			list($id,$qs,$bef,$aft)=self::sepStartTag($html,$t,"<",">");
			list($cnt,$aft)=self::sepEndTag($aft,$t.$id);
			return array($id,$qs,$bef,$cnt,$aft);
		}
		static function sepStartTag($html,$t,$sep1,$sep2){
			$l=strlen($t);
			$p=stripos($html,$sep1.$t);$bef=substr($html,0,$p);$html=substr($html,$p+strlen($sep1));
			$p=stripos($html,$sep2);$qs=substr($html,$l,$p-$l);$html=substr($html,$p+strlen($sep2));
			if(strstr($qs," ")){$p=stripos($qs," ");$id=substr($qs,0,$p);$qs=substr($qs,$p+1);}else{$id=$qs;$qs="";}
			return array($id,$qs,$bef,$html);
		}
		static function sepEndTag($aft,$t){
			$p=stripos($aft,"</$t");$cnt=substr($aft,0,$p);$aft=substr($aft,$p);
			$p=stripos($aft,">");$aft=substr($aft,$p+1);
			return array($cnt,$aft);
		}
		static function getAttributes($qs){
			$x=explode("=",$qs);$y=array();
			if(count($x)<2)$y["label"]=$qs;// ecriture simplifiée <cmstag value> <=> <cmstag label=value>
			for($i=0;$i<count($x)-1;$i++){
				$p=strrpos($x[$i+1]," ");$v=substr($x[$i+1],0,$p);
				if(substr($v,0,1)=="'"||substr($v,0,1)=='"')$v=substr($v,1,strlen($v)-2);
				if($v=="")$v=$x[$i+1];
				$y[$x[$i]]=$v;
				$x[$i+1]=substr($x[$i+1],$p+1);
			}
			return $y;
		}
		static function bldLang($id,$qs,$bef,$aft,$c){
			$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', ENV);$envLng=$cfg->lng;
			$cnt="";foreach($envLng as $k=>$v)$cnt.=str_replace(array("¤cmsLang id¤","¤cmsLang name¤","¤cmsLang img¤"),array($k,$v,"<img src=".OOP_PATH."img/lng/$k.gif border=0 />"),$c);
			return $bef.$cnt.$aft;
		}
		static function bldFrame($id,$qs,$bef,$aft,$cnt){
			return $bef.cms_bldWeb::buildFrame($id,$cnt).$aft;
		}
		static function bldBlock($id,$qs,$bef,$aft){
			return $bef.cms_bldWeb::buildBlock($id).$aft;
		}
		static function bldTree($idB,$qs,$bef,$aft){
			$x=explode("-",$qs);
			return $bef.cms_bldWeb_trees::makeTree($idB,$x[0],"",$x[1]).$aft;
		}
		static function bldLbl($idB,$qs,$bef,$aft,$cnt,$sep1){
			global $db,$lngId,$ctxMnu;
			$q=self::getAttributes($qs);$lbl=$q["label"];$ttl="label=$lbl";$val="$ttl";$ttl="bloc=$idB $ttl";
			if($db->fetchOne("select count(idB) from ".self::whichTable("cmsLabels")." where idB='$idB' and label='$lbl'")>0)$val=cms_admin_labels::getlabel($idB,$lbl);
			if($sep1=="¤"&&SHW_USR_EDT)
				$ctxMnu.="<textarea class=cmsEditable id=cmsMacro_$idB"."_$lbl onclick=cms.MacroOver(this) onblur=cms.MacroOut(this) title='$ttl'>".
						$val.
					"</textarea><br>";
			$val=preg_replace(array("/\<.*?cmsMacro_$idB"."_$lbl.*?\>/","/\<.*?cmsMacCnt_$idB"."_$lbl.*?\>/i"),"",$val);
			if(ENV==DEV&&$sep1!="¤")
				$edt=(SHW_USR_EDT?
						"<span class=cmsEditable id=cmsMacCnt_$idB"."_$lbl title='$ttl.'>".
							"<div style=display:inline id=cmsMacro_$idB"."_$lbl onmousedown=\"cms.MacroOver(this)\" contenteditable=true onblur=cms.MacroOut(this) oncontextmenu=\"this.contentEditable=this.contentEditable!='true'?'true':'false'\">".
								($val!=""?$val:"empty label $idB $lbl").
							"</div>".
							"<span></span>".
							"<div id=cmsMacro_$idB"."_$lbl"."_mnu class=cmsEasEdit></div>\n".
						"</span>\n"
						:$val);
			else $edt=$val;
			return "$bef$edt$aft";
		}
		static function whichTable($table){
			return (ENV!=DEV?"rls_".ucFirst($table):$table);
		}
	}
?>
