<?
	class cms_bldWeb{
		static function getVar($var,$val){
			$$var=$val;$ary=array($_COOKIE,$_POST,$_GET);
			foreach($ary as $a)if(array_key_exists($var,$a))$$var=$a[$var];
			return $$var;
		}
		static function detect_browser_language($sitLngs) {
			$brwLng=strtolower(trim(preg_replace('/(;q=\d+.\d+)/i', '', $_SERVER["HTTP_ACCEPT_LANGUAGE"])));
			$detected="";foreach($sitLngs as $lng)if(substr($brwLng,0,strlen($lng))==$lng)$detected=$lng;
			return $detected;
		}
		static function buildBlock($block){
			cms_bldWeb::chkRtmAry($block);
			$fil=ENV_PATH."php/$block.php";$html=file_exists($fil)?file_get_contents($fil):"";
			$d1=$d2="";if((SHWADM||(!ALLOWEDT&&SHW_USR_EDT))&&ENV!=PRD){
				$col=$block!=$GLOBALS['block']?'':'Red';
				$d1="<div id=cmsEdb$block qry=".$_SERVER['QUERY_STRING']." class=cmsEditableBlock$col title='Bloc $block'>";
				$d2="</div>";
			}
			return $d1.self::render($html).$d2;
		}
		static function buildFrame($frmBck,$html){
			if($GLOBALS["frame"]&&$frmBck){
				cms_bldWeb::chkRtmAry($frmBck);
				$html=str_ireplace("<cmsContent>",$html,file_get_contents(ENV_PATH."php/$frmBck.php"));
				$html=self::render($html);
			}
			return $html;
		}
		static function render($html){
			global $lngId,$block,$frame,$bock;	// because of the following code evaluation
			$html=cms_bldWeb_macros::checkMacros($html);
			ob_start();ob_implicit_flush(0);eval("?".">$html");$html=ob_get_contents();ob_end_clean();
			// in case of php dependant macros (php creates macros)
			if(strstr($html,"<cms")||strstr($html,"¤cms"))$html=cms_bldWeb_macros::checkMacros($html);
			return $html;
		}
		static function chkRtmAry($rtm){
			global $rtmAry;
			if(file_exists(PHP_PATH."rtm/$rtm.php"))$rtmAry[]=$rtm;
		}
		static function runRtms($html){
			global $rtmAry;
			for($i=count($rtmAry);$i>0;$i--)$html=self::runRtm($html,$rtmAry[$i-1]);
			include OOP_PATH."protect/ctr/frmSbm.php"; // check forms
			return $html;
		}
		static function runRtm($html,$bck){
			global $lngId,$op,$db;
			$rtm=array();include PHP_PATH."rtm/$bck.php";
			if(count($rtm))foreach($rtm as $k=>$v)$html=str_replace("#rtm$bck.$k#",$v,$html);
			return $html;
		}
		static function getCache($var,$fnt){
			global $zndCache;
			if(($data = $zndCache->load($var)) === false) {
				eval("\$data=$fnt;");
				$zndCache->save($data, $var);
			}
			return $data;
		}
		static function checkHead($ary){
			global $html;$head="";
			if(SHWADM||(!ALLOWEDT&&SHW_USR_EDT)){
				if(!strstr(str_replace(" ","",strtolower($html)),"text/html;charset="))
					$head.="<meta http-equiv=Content-Type content=text/html;charset=utf-8 />\n";
				$head.=	"<link rel=\"stylesheet\" type=\"text/css\" href=\"".OOP_PATH."admin.css\" />\n";
				$head.=	"<script>".
						"cms=new Object();".
						"OOP_PATH='".OOP_PATH."';".
						"WEB_PATH='".WEB_PATH."';".
						"USR_BLS=' ".USR_BLS." ';".
						"SHW_USR_LNS=".SHW_USR_LNS.";".
						"cmslngId='".USR_LNG."';".
						"GRP='".GRP."';".
					"</script>";
				foreach(array(	""=>array("admin"),
						"utils/"=>explode(",","ajx,cookies,events,clipboard,calendar"),
						"admin/"=>explode(",","users,uploads,rls,blocks,editor,props,search,labels,records,trace"),
						"bldWeb/"=>array("macros","trees"),
						"codepress/"=>array("codepress"),
						"../lng/"=>array(USR_LNG)) as $fld=>$fils)
							foreach($fils as $f=>$js)
								$head.=	"<script src=".OOP_PATH."js/$fld$js.js></script>\n";
				$head.=	"<script>Evt.Add(document,'mousemove',Evt.GetMousePos)</script>\n";
			}
			foreach($ary as $s=>$c)
				if(strstr($html,$c)&&!strstr($html,"utils/$s.js")&&!strstr($head,"utils/$s.js"))
					$head.="<script src=\"".OOP_PATH."js/utils/$s.js\"></script>\n";
			if($head){
				if(stristr($html,"</head>"))$html=str_ireplace("</head>","$head</head>",$html);else $html.=$head;
			}
			echo trim($html);		
		}
	}
?>
