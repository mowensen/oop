<?
	if(!array_key_exists('htdigest',$_POST))$_POST['htdigest']=true;
	foreach(explode(",","zendlibrarypath,adapter,host,dbname,username,password,user,pass,prefix,folder,lngId,dbg,htdigest") as $x)
		$$x=array_key_exists($x,$_POST)?$_POST[$x]:'';
	if(!$adapter)$adapter='pdo_mysql';
	if(!$host)$host='localhost';
	if(array_key_exists('lngId',$_GET))$lngId=$_GET['lngId'];

	// Php display all errors
	ini_set("error_reporting",2147483647);
	ini_set("display_errors",1);
	ini_set("display_startup_errors",1);
	
	// check lib path
	set_include_path("protect/lib/". PATH_SEPARATOR . get_include_path());
	require('Zend/Loader/Autoloader.php');
	$loader=Zend_Loader_Autoloader::getInstance();$loader->registerNamespace(array('cms_'));

	//languages
	$lngs=array('en'=>'English','fr'=>'Français');$enabledLanguages=array_keys($lngs);$dfl=current($enabledLanguages);
	if(!$lngId)$lngId=cms_bldWeb::detect_browser_language($enabledLanguages);
	if(!$lngId)$lngId=$dfl;
	cms_admin_props::defConst('lng/'.$lngId.'-install.php');
	
?>
<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?=TXThtmlTitle?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<style>
			<?=file_get_contents("protect/skeleton/dev/upl/sta/2/main.css")?>
			<?=file_get_contents("protect/skeleton/dev/upl/sta/3/style.css")?>
		</style>
	</head>
	<body>
		<center />
		<div style=display:table;width:670px;text-align:center>
			<div style=float:right>
				<?foreach($lngs as $k=>$v){?>
					<?if($k!=$lngId){?><a href=?lngId=<?=$k?>><?}?>
						<img src=img/lng/<?=$k?>.gif border=0<?=($k!=$lngId?' style=opacity:0.3':'')?> title=<?=$v?>>
					<?if($k!=$lngId){?></a><?}?>
				<?}?>
			</div>
			<h1><?=TXTtitle?></h1>
		</div>
		<div style=display:table>
			<form method=post style=display:table>
				<input type=hidden name=lngId value=<?=$lngId?>>
				<div style=display:table-row>
				<div style=display:table-cell>
					<div class=frame>
						<h2><?=TXTdbTitle?></h2>
						<table>
							<tr><td width=105><?=TXTadapter?></td><td><input name=adapter value=<?=$adapter?>></td></tr>
							<tr><td width=105><?=TXThost?></td><td><input name=host value=<?=$host?>></td></tr>
							<tr><td width=105><?=TXTdbName?></td><td><input name=dbname value=<?=$dbname?>></td></tr>
							<tr><td width=105><?=TXTuser?></td><td><input name=username value=<?=$username?>></td></tr>
							<tr><td width=105><?=TXTpass?></td><td><input type=password name=password value=<?=$password?>></td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td width=105><?=TXTprefix?></td><td><input name=prefix value=<?=$prefix?>></td></tr>
							<tr><td colspan=2 align=center><i><?=TXTprefixComment?></i></td></tr>
						</table><br/>
					</div><br>
				</div>
				<div style=display:table-cell;width:15px>
				</div>
				<div style=display:table-cell>
					<div class=frame>
						<h2><?=TXTpath?></h2>
						<?=TXTpathIntro?>
						<table>
							<tr>
								<td width=105><?=TXTfolder?> <span style=float:right>../</span></td>
								<td><input name=folder value=<?=$folder?>></td>
							</tr>
						</table>
						<i><?=TXTpathComment?></i>
					</div><br>
					<div class=frame>
						<h2><?=TXTcreateDb?></h2>
						<?=TXTcreateDbIntro?>
						<table>
							<tr><td width=105><?=TXTuser?></td><td><input name=user value=<?=$user?>></td></tr>
							<tr><td width=105><?=TXTpass?></td><td><input type=password name=pass value=<?=$pass?>></td></tr>
						</table>
					</div><br>
					<div style=text-align:right;margin-right:15px><input type=submit></div>
				</div>
				</div>
			</form>
			<div class=frame id=htdigest style=display:none>
				<h2>Htdigest</h2>
				<div style=margin-left:55px><?=TXThtDigest?></div>
			</div><br>
		</div>
		<div style=color:red;display:table;text-align:left>
<?
	if(array_key_exists('username',$_POST)){
		if($_POST['username']){
			if($_POST['user']){
				$db = Zend_Db::factory($adapter, array("host"=>$host,"dbname"=>"","username"=>$user,"password"=>$pass));
				$db->getConnection();
				if($db->isConnected()){
					$db->query("create database if not exists $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
					$db->query("grant all privileges on $dbname.* to $username@$host identified by '$password'");
					?><div><?=TXTdbCreated?></div><?
				}else{?><div><?=TXTdbProblem1?> <?=$host?> <?=TXTdbProblem2?> <?=$dbname?>.</div><?}
			}
			$db = Zend_Db::factory($adapter, array("host"=>$host,"dbname"=>$dbname,"username"=>$username,"password"=>$password));
			$db->getConnection();
			if($db->isConnected()){
				if(!is_dir("../$folder")){
					$folder="../$folder";
					cms_utils_dirs::copyDir("protect/skeleton/","$folder/");
					?><div><?=TXTtreeOk?></div><?
					//modif du fichier de config
					$cfgFile=$folder."/app/config.ini";
					$cfg=file_get_contents($cfgFile);
					foreach(explode(",","adapter,host,dbname,username,password,prefix") as $x){
						$z=$x;$z="db.params.$x";if($x=='adapter')$z="db.$x";
						$y=explode($z,$cfg);
						$pos=strpos($y[1],"\n");$cfg=$y[0].$z.substr($y[1],0,$pos).$_POST[$x].substr($y[1],$pos);
					}
					cms_utils_files::save($cfgFile,$cfg);
					?><div><?=TXTconfigOk?><br></div><?
					//création de la base avec test de la config
					$CFG=new Zend_Config_Ini($cfgFile,'app');
					date_default_timezone_set("Europe/Paris");
					$db=new cms_db();
					$db->query(utf8_decode(file_get_contents('protect/skeleton/app/db/my.sql')));
					foreach(explode('','cmsBlocks cmsLabels cmsLabelsLang cmsTreesLang') as $table){
						$db->query("drop table if exists rls_".ucFirst($table).";create table rls_".ucFirst($table)." select * from $table");
					}
					?><div><?=TXTdbOk?></div><?
					//modif du fichier dev/.htaccess
					$hta=file_get_contents($folder."/dev/.htaccess");
					$d0=getcwd();chdir($folder);$d1=getcwd();chdir($d0);
					cms_utils_files::save($folder."/dev/.htaccess",str_replace('app/',"$d1/app/",$hta));
					?><div>
						<?=TXThtaccessOk1?><br>
						<?=TXThtaccessOk2?> <a href="<?=$folder?>/dev"><?=$folder?>/dev</a> <?=TXTcompte?> <span><?=TXTuser?>=admin <?=TXTpass?>=admin</span>.
					</div><?
					if(!is_link($folder.'/oop')&&!is_dir($folder.'/oop'))symlink('../oop',$folder.'/oop');
					unlink ('install.php');
					if(!is_file('install.php')){?><div><?=TXTsecurityOk?><br></div><?}
					?><script>document.getElementById('htdigest').style.display='block'</script><?
				}else{
					?><div><?=TXTchkFolder?></div><?
				}
			}else{
				?><div><?=TXTchkBase?></div><?
			}
		}else{
			?><div><?=TXTchkBaseInfo?></div><?
		}
	}
?>
		</div>
	</body>
</html>

