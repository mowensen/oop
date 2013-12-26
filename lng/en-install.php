<?
const TXThtmlTitle="OOPUB WEBSITE INSTALLATION";
const TXTtitle="OOPUB INSTALLATION SCRIPT";

//database
const TXTdbTitle="Database information";
const TXTadapter="Adapter";
const TXThost="Host";
const TXTdbName="Name";
const TXTuser="User";
const TXTpass="Password";
const TXTprefix="Tablesprefix";
const TXTprefixComment="Useful whenever multiple sites in the database.";

//paths
const TXTpath="Path";
const TXTpathIntro="Give a folder name for the installation:";
const TXTfolder="Folder";
const TXTpathComment="The folder will be created in the folder containing oop/";

//create db
const TXTcreateDb="Create the database";
const TXTcreateDbIntro="If database does not exist, please give an account enabled to doit:";

//checks
const TXTdbCreated="Database has been created.";
const TXTdbProblem1="Impossible to connect to";
const TXTdbProblem2="in order to create";
const TXTtreeOk="Site folders have been created.";
const TXTconfigOk="app/config.ini configuration file is updated.";
const TXTdbOk="Database is updated with new oopub tables.";
const TXThtaccessOk1="Paths in dev/.htaccess file are updated.";
const TXThtaccessOk2="You can login";
const TXTcompte="account";
const TXTsecurityOk="SECURITY: install.php has been deleted.";
const TXTchkFolder="A folder name must be specified (without any '/').";
const TXTchkBase="Database account is not correct.";
const TXTchkBaseInfo="Please fill the database information.";

//htdigest
const TXThtDigest=	"OOPub manages account connexions with htdigest (most securitive when SSL is not activated)<br>".
			"If you have problems logging in, your server does not manage htdigest.<br>".
			"Then please rename dev/.htaccess in dev/.xhtaccess and OOPub will use a simple form for logging in.";
const TXThtDigestCheckbox="Server manages htdigest password files canbe modified.<br><i>(Otherwise oopub uses a connection form)</i>";
?>
