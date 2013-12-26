<?
class extBootstrap extends Zend_Application_Bootstrap_BootstrapAbstract{
	public function run(){
	}
	protected function _initCacheIdentity(){
		global $lngId,$cache,$block,$frame;
		$cache="prd/tmp/frame=$frame&block=$block&lngId=$lngId";
		$qs=$_SERVER["QUERY_STRING"];foreach($_POST as $k=>$v)$qs.="&$k=$v";
		$y=explode("&",$qs);
		for($i=0;$i<count($y);$i++)if(strstr($y[$i],"=")){
			list($k,$v)=explode("=",$y[$i]);
			if($v!=""&&!strstr($cache,"&$k=")&&($k=="pg"||$k=="ids"||substr($k,0,2)=="tb"||substr($k,0,3)=="sel")){
				$cache.="&$k=".($ok?(int)($v):urlencode(urldecode($v)));
			}
		}
	}
	protected function _initView(){
		global $db,$cache,$frame,$block,$rtmAry,$dbg,$BCKS;
		$html=$admHtm="";$rtmAry=array();
		if($block!="-1"){
			if(ENV=="dev"||$dbg||!(file_exists($cache)&&$db->isConnected())){
				$BCKS=cms_admin_blocks::lstBlocks(''); // initialise $BCKS [id][nb,title,tree,next - fields,isModified,isMenu,isForm,xdate,xtrace,xip - realtime,cache,frame]
				//$BCKS=cms_bldWeb::getCache("BCKS","");
				cms_bldWeb_menus::getMnu(''); //cms_bldWeb::getCache("MNU","");		// initialise $MNU  [pth,rwt,title,nxt,len][id]
				$html=cms_bldWeb::buildFrame($frame,cms_bldWeb::buildBlock($block));
			}
			if(ENV=="prd"&&!$dbg){
				if(file_exists($cache)){				// load cache
					$cacFil=file($cache);
				}else{
					if($db->isConnected()){				// build cache
						cms_utils_files::save($cache,@implode(" ",$rtmAry)."\n$html");
						$cacFil=file($cache);
					}else{
						$cacFil=file("sav".substr($cache,3));	// get old cache
					}
				}
				//récupération première ligne des id temps réel
				$rtmStr=trim(array_shift($cacFil));
				$html=str_replace("dev/upl/",ENV."/upl/",implode($cacFil));
				if($rtmStr!="")$rtmAry=explode(" ",$rtmStr);
			}
			$html=cms_bldWeb::runRtms($html);				// run real time stuff
		}
		if(SHWADM){
			$opt = $this->getOption('db');
			define ("DB_ADM_WEB_PATH",$opt['admin']["webpath"]);
			define ("DB_ADM_NAME",$opt['admin']["name"]);
			$html=cms_admin::shwAdm($html);				// show admin
		}
		echo $html;		
	}
}
?>
