<?
	// Php display all errors
	ini_set("error_reporting",2147483647);
	ini_set("display_errors",1);
	ini_set("display_startup_errors",1);

	// Inclusions
	define("APP_PATH","app/");
	set_include_path(get_include_path().":".OOP_PATH."protect/lib/".":dev/php/");
	require_once 'Zend/Loader/Autoloader.php';
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->registerNamespace('cms_');

	// Folders environnement constants
	$CFG=new Zend_Config_Ini(APP_PATH."config.ini",'folders');
	define ('DEV', $CFG->dev);define ('DEV_PATH', DEV."/");
	define ('PRD', $CFG->prd);define ('PRD_PATH', PRD."/");
	define ('SAV', $CFG->sav);define ('SAV_PATH', SAV."/");
	define ('CMS_IMG', OOP_PATH."img/");
	define ('WEB_PATH', substr($_SERVER["PHP_SELF"],0,strrpos($_SERVER["PHP_SELF"],"/")));

	// Zend cache initialisation
	$zndCache = Zend_Cache::factory(	'Core',
						'File', array('lifetime' => 6000,
						'automatic_serialization' => true),
						array('cache_dir' => APP_PATH."tmp"));

	// Database
	$CFG=new Zend_Config_Ini(APP_PATH."config.ini",'app');
	date_default_timezone_set($CFG->timezone);
	$db=new cms_db();

	// Variables

	     // Control variables ($cache=cacheIdentity, $db=databaseConnection, $rtmAry=realTimeBlockList)
		$cache="";$rtmAry=Array();
		$ary=explode(" ","env:prd block:0 frame:3 Frame: lngId: op: shwAdm:1 allowEdt:0 cookies:block;0,lngId;1,sec;0 sec:0 dbg: do: ctxMnu:");
		foreach($ary as $a){list($k,$v)=explode(":",$a);$$k=cms_bldWeb::getVar($k,$v);}

		// Language
		$lngs=$CFG->lng->toArray();$enabledLanguages=array_keys($lngs);$dfl=current($enabledLanguages);
		if(!$lngId)$lngId=cms_bldWeb::detect_browser_language($enabledLanguages);
		if(!$lngId)$lngId=$dfl;
		define("DFL",$dfl);define("LNGID",$lngId);

	// User connexion checks
	
		// htaccess constants
		cms_login::setHtConst();

		// security
		if($env!=DEV&&!$sec){
			$sec=$allowEdt=0;define('GRP','');define('SHW_USR_EDT','');
		}else{
			$sec=cms_login::checkSecure();
		}
		if((GRP!="admins"&&GRP!="pubs"&&GRP!="guests")||!$sec)$env=PRD;
		setCookie("env",$env);
		if($sec){$acc=cms_login::checkAccess();if(!$acc)$env=PRD;}

	 	// environment and security constants modifications
		define('SEC', $sec);
		define('ALLOWEDT', $allowEdt);
		define('SHWADM',($shwAdm&&SEC&&(GRP=="admins"||GRP=="pubs"||GRP=="guests")?1:0));
		define('ENV', $env);
		define('ENV_PATH', ENV."/");
		define('PHP_PATH', ENV_PATH."php/");

		// register models
		set_include_path(get_include_path().":".ENV_PATH."/php/");
		$loader->registerNamespace('mdl_');

	// Cookies
	if($Frame!=""){$frame=$Frame;$cookies.=',frame';}
	foreach(explode(",",$cookies) as $a){$c=explode(";",$a);setCookie($c[0],${$c[0]},($c[1]?time()+1000000:0),"");} 
	
	if(!$do){	// build the page

		// Build cache identity
		$cache="prd/tmp/frame=$frame&block=$block&lngId=$lngId";
		$qs=$_SERVER["QUERY_STRING"];foreach($_POST as $k=>$v)$qs.="&$k=$v";
		$y=explode("&",$qs);
		for($i=0;$i<count($y);$i++)if(strstr($y[$i],"=")){
			list($k,$v)=explode("=",$y[$i]);
			if($v!=""&&!strstr($cache,"&$k=")&&($k=="pg"||$k=="ids"||substr($k,0,2)=="tb"||substr($k,0,3)=="sel")){
				$cache.="&$k=".($ok?(int)($v):urlencode(urldecode($v)));
			}
		}

		//build view
		$html=$admHtm="";$rtmAry=array();
		if(SHWADM)cms_admin_props::defConst(OOP_PATH.'lng/'.USR_LNG.'.php');
		if($block>=0){
			if(ENV=='dev'||$dbg==2||!(file_exists($cache)&&$db->isConnected())){
				$BCKS=cms_admin_blocks::lstBlocks(''); // initialise $BCKS [id][nb,title,tree,next - fields,isModified,isMenu,isForm,xdate,xtrace,xip - realtime,cache,frame]
				//$BCKS=cms_bldWeb::getCache("BCKS","");
				cms_bldWeb_menus::getMnu(''); //cms_bldWeb::getCache("MNU","");		// initialise $MNU  [pth,rwt,title,nxt,len][id]
				$html=cms_bldWeb::buildFrame($frame,cms_bldWeb::buildBlock($block));
				if(strstr($html,"¤cmsMnu$block pth¤"))$html=str_replace("¤cmsMnu$block pth¤",$BCKS[$block]['title'],$html);
			}
			if(ENV=='prd'&&$dbg!=2){
				if(file_exists($cache)){				// load cache
					$cacFil=file($cache);
				}else{
					if($db->isConnected()){				// save cache
						$html=str_replace('dev/upl/','prd/upl/',$html);
						cms_utils_files::save($cache,@implode(" ",$rtmAry)."\n$html");
						$cacFil=file($cache);
					}else{
						$cacFil=file("sav".substr($cache,3));	// get old cache
					}
				}
				// get the cache first line to find out which real time files must be run
				$rtmStr=trim(array_shift($cacFil));
				$html=implode($cacFil);
				if($rtmStr!="")$rtmAry=explode(" ",$rtmStr);
			}
			$html=cms_bldWeb::runRtms($html);			// run real time
		}
		if(SHWADM){
			define ('DB_ADM_WEB_PATH',$CFG->db->admin->webpath);
			define ('DB_ADM_NAME',$CFG->db->admin->name);
			$html=cms_admin::shwAdm($html);				// show admin
			cms_db_backup::checkLast();				// check backups
		}
		// check utils scripts to add in <head>
		cms_bldWeb::checkHead(array('ajx'=>'AJX.','events'=>'EVT.','calendar'=>'calendar.show(','checkForm'=>'.addChecker('));
	}else{	// run the "do" action
		if($sec)cms_admin_props::defConst(OOP_PATH.'lng/'.USR_LNG.'-do.php');
		if(SEC||substr($do,0,6)!="admin_")include OOP_PATH."protect/ctr/$do.php";
	}
?>
