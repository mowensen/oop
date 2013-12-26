<?
//admin.php
const TXTdevView="Development zone => Click to view published page";
const TXTprdView="Production zone => Click to view development page";
const TXTblockTree="Block tree";
const TXTusers="Users";
const TXTproperties="Properties";
const TXTsearch="Search engine";
const TXTajax="See ajax buffer";
const TXTsql="Your SQL link";
const TXTlogout="Log out";
const TXTcacheView="Cache view";
const TXTuploads="Uploads";
const TXTinsertBlock="Insert a new block";
const TXTdeleteBlock="Delete this block";
const TXTpublish="Publishing";
const TXTsearchBlock="Search for a block";
//block.htm
const TXTmoved="is going to be moved.";
const TXTbefore="before";
const TXTafter="after";
const TXTbelow="sub level";
const TXTok="OK";
const TXTsav="Save";
const TXTcancel="Cancel";
const TXTautomaticContent="Automatically create a content label";
const TXTisInMnu="Is part of the menu";
const TXTwhichPosition="Please choose the desired position against block";
//easEdit.htm
const TXTshowHtml="Show html";
const TXTlinkIt="Insert a link";
const TXTinsImg="Insert an image";
const TXTinsOl="Ordered list";
const TXTinsUl="Unordered list";
const TXTinsCss="Show style sheet styles";
const TXTdelFormat="Delete format";
const TXTshwHelp="Show the other functions";
const TXTbold="Bold";
const TXTitalic="Italic";
const TXTunderline="Underline";
const TXTjustifyLeft="Justify left";
const TXTjustifyCenter="Justify center";
const TXTjustifyRight="Justify right";
const TXTjustify="Justify full";
const TXTheadings="Headings";
const TXTselAll="Select all";
const TXTselCut="Cut selection";
const TXTselCopy="Copy selection";
const TXTpasteClip="Paste from clipboard";
const TXTundo="Undoes your last action";
const TXTredo="Redoes your last action";
const TXTformats="FORMATS";
const TXTselection="SELECTION";
//editor.htm
const TXTblock="Block";
const TXTassemble="Assembling blocks";
const TXTnavigation="Navigation";
const TXTlabels="The labels";
const TXTrecords="The records";
const TXThide="Hide";
const TXTframeTag="Surrounds a content which has to be framed with another block containing the insertion point";
const TXTframeContent="In a frame = is the insertion point";
const TXTblockTag="Insert block (which id is Id)";
//mnus.htm
const TXTmnuLvl="tree level";
const TXTmnuNb="row in the tree level";
const TXTmnuId="page identity";
const TXTmnuTitle="page title";
const TXTmnuSelected="is the selected page";
const TXTmnuPth="titles path";
const TXTmnuHrf="if apache's mod_rewriteis enbaled subsequently manages href";
const TxtmnuLen="amount of pages in this menu";

const TXTmnuMacros="Menus tags";
const TXTpasteSitemap=	"To try the complete sitemap please click <a href=\"javascript:cms.EditIns(cms.Elm('SitMap').innerHTML)\" style=color:green>HERE</a><br>
			It uses all the tags.<br>
			Just delete the ones you don't need and add htmland conditions for the layout.<br>";
const TXTcmsMnu0="cmsMnu0 is the (first) top tree level";
const TXTopeningMnuTag="opening tree level 0 tag";
const TXTclosingMnuTag="closing tree level 0 tag";
const TXTnextMnu="sub levels iteration tag";
const TXTidentifiedBlocks="Beneath are the tags identified by a BLOCKID (can be scripted anywhere)";
const TXTgiveIdentity="Give an id";
const TXTlngMacros="Language tags";
const TXTopeningLngTag="Language tags start";
const TXTclosingLngTag="Language tags end";
//props
const TXTgenProps="GENERAL PROPERTIES";
const TXTrwtMod="Activate rewrite mode";
const TXTautomatic="automatic";
const TXTevery="every";
const TXTsavDatabase="DATABASE BACKUP";
const TXTsavNow="Save now";
const TXTselectDate="You need to select a date";
const TXTlanguageDefinition="LANGUAGES DEFINITION";
const TXTnewLanguages="New Language in developpement zone";
const TXTremovelastLanguage="Remove selected language";
const TXTpublishLastLanguage="Move selected language to published zone";
const TXTdefaultLanguage="Default Language";
//records.htm
const TXTidentities="Identities";
const TXThorVerExt="horizontale and vertical tables extension";
const TXTdefaultId="Default identity";
const TXTisFormed="is built forthis block";
const TXTextendTable="If you wish this table to expand another block's table, choose that table in the key select and uncheck id checkbox";
const TXTcreateSubTable="If you wish this table to be another block's sub-table, choose that table in the key select and check the id checkbox";
const TXTname="name";
const TXTcoupleInputName="The select enable to insert predefined input/types couples. Once selection is inserted, if needed you can change the type .<br>
				If none of the types are convenient, choose 'standard' et add the type you need.<br>
				<b>RQ</b>: Tree type enables as much levels as needed (selects, radios and checkboxes are tree types)<br>
				If the tree already exists you can give it's name otherwise a new tre will be created.";
const TXTprivateData="Record datas do not need to be published.<br>
				The data is private and cannot be used to create a module.<br>
				Usually, form's donot need language dependant fields.";
const TXTdelTables="Deleted fields are only removed from the table if no data exists in that field.<br>
				A deleted table is not used by OOPUB but still exists in the database.<br>
				You can manually delete it with the sql link (default configured tolink to phpmyadmin).";
const TXTnewsForms="RECORDS / FORMS";
const TXThelp="Help";
const TXTisForm="Is a form";
const TXTnonLanguageDependant="Table <b>block<edt></edt></b> NONE language DEPENDANT fields";
const TXTkeys="The keys";
const TXTidIfChecked="identity (if checked)";
const TXTparentId="parent identity";
const TXTrecordClassification="Champs de classement des enregistrements";
const TXTeditFields="Edit the fields";
const TXTtrace="Trace";
const TXTdate="date";
const TXTlanguageDependant="Table <b>block<edt></edt>Lang</b> language DEPENDANT fields";
const TXTallKeys="all <b>block<edt></edt> keys</b>";
const TXTlanguageKey="Language key";
const TXTpasteAll="Click here to paste all";
const TXTbeginListing="listing start";
const TXTendListing="listing end";
const TXTrecordingForm="Records form";
const TXTmailTo="mailTo[] input is optional.<br>
			A summary mail will be sent to mailTo[] if the address is valid.<br>
			You can add as much mailTo[] inputs as needed.<br><br>";
const TXTcompulsoryInput="tables[] inputs and id are compulsory";
//rls.htm
const TXTdatabase="Database";
const TXTblocks="Blocks";
const TXTtrees="Trees";
const TXThtaccess="Menu arrays files rebuilt and saved";
const TXTunchanged="Unchangede";
const TXTpublished="Released";
const TXTdeleted="Deleted";
const TXTdynamic="Dynamic";
const TXTstatic="Static";
const TXTcache="Cache";
const TXTrealTime="Real time";
const TXTcacheMoved="Cached Files moved to sav/tmp";
const TXTfiles="Files";
//const TXTresumeFiles="#n1 files unchanged<br>#n2 files released<br>#n3 files deleted<br><br>total time #t s";
//const TXTnone="no";
const TXTfilesUnchanged="These files are marked as unchanged";
const TXTfilesMoved="Old files saved in /sav and new files published";
const TXTfilesSaved="Have been saved in /sav";
const TXTblocksPub="Publication de blocs";
const TXTmultBlocks="Multiples blocs";
const TXTselectedBlock="Selected block";
const TXTflashPub="Flash publish  (timestamp check)";
const TXTpubPop="Publish";
const TXTpubResume="PUBLICATION SUMMARY";
//search.htm
const TXTsearchHlp1="First specify which static labels ids, dynamic records ids and directories/ and (or) files to search in.<br>
			Joker <font color=red>*</font> in these inputs means search in all. All directories are the following:";
const TXTsearchSep="If multiple entries, use the '<font color=red>,</font>' seperator</font>.<br>";
const TXTsearchHlp2="For each '<font color=red>,</font>' seperated search entry, you need a corresponding '<font color=red>,</font>' seperated replace entry when replacing.<br>
				Replacements are done in the given order.<br>
				Joker <font color=red>*</font> can be used in search and replace in order to leave the <font color=red>*</font> content unchanged when replacing.<br>
				To use <font color=red>*</font> and <font color=red>,</font> as normal characters use <font color=red>**</font> and <font color=red>,,</font>";
const TXTsearchHlp3="Then first click on <font color=red>search button</font>.<br>
			A list of objects containing the search string is displayed in the white area.<br>";
//uploads.htm
const TXTfolder="Folder";
const TXTdelete="Delete";
const TXTrename="Rename";
const TXTdimension="Dimension";
//users.htm
const TXTgroup="group";
const TXTuser="user";
const TXTpass="pass";
const TXTinterfaceLng="Langue de l'interface";
const TXTcompte="ACCOUNT";
const TXTprivileges="PRIVILEGES";
const TXTcaracteristics="CHARACTERISTICS";

//DOUBLES with lng-do.php
//bldweb_trees
const TXTup="Up";
const TXTdown="Down";
const TXTsub="Add in sub-level";
const TXTadd="Add";

?>
