<?
//admin.php
const TXTdevView="Zone développement => Cliquer pour visualiser la page publiée";
const TXTprdView="Zone production => Cliquer pour visualiser la page en développement";
const TXTblockTree="Block tree";
const TXTusers="Utilisateurs";
const TXTproperties="Propriétés générales";
const TXTsearch="Recherches générales";
const TXTajax="Voir le buffer ajax";
const TXTsql="Votre interface SQL";
const TXTlogout="Déconnexion";
const TXTcacheView="Vue à mettre en cache";
const TXTuploads="Téléchargements";
const TXTinsertBlock="Insérer un nouveau bloc";
const TXTdeleteBlock="Supprimer ce bloc";
const TXTpublish="Publication";
const TXTsearchBlock="Rechercher un bloc";
//block.htm
const TXTmoved="va être déplacé.";
const TXTbefore="avant";
const TXTafter="après";
const TXTbelow="au niveau inférieur";
const TXTok="Valider";
const TXTsav="Sauvegarder";
const TXTcancel="Annuler";
const TXTautomaticContent="Créer automatiquement une étiquette nommée content";
const TXTisInMnu="Intégrer ce bloc dans le menu";
const TXTwhichPosition="Choisissez la position désirée par rapport au bloc";
//easEdit.htm
const TXTshowHtml="Afficher le html";
const TXTlinkIt="Insérer un lien";
const TXTinsImg="Insérer une image";
const TXTinsOl="Liste numérotée";
const TXTinsUl="Liste pointée";
const TXTinsCss="Afficher les styles";
const TXTdelFormat="Supprimer les formats de police";
const TXTshwHelp="Afficher les autres fonctionalités";
const TXTbold="Gras";
const TXTitalic="Italique";
const TXTunderline="Souligner";
const TXTjustifyLeft="Justifier à gauche";
const TXTjustifyCenter="Justifier au centre";
const TXTjustifyRight="Justifier à droite";
const TXTjustify="Justifier";
const TXTheadings="Headings";
const TXTselAll="Tout sélectionner";
const TXTselCut="Couper la sélection";
const TXTselCopy="Copier la sélection";
const TXTpasteClip="Coller le cotenu du presse-papier";
const TXTundo="Défaire la dernière action";
const TXTredo="Refaire la dernière action";
const TXTformats="FORMATS";
const TXTselection="SELECTION";
//editor.htm
const TXTblock="Block";
const TXTassemble="Assemblage des blocs";
const TXTnavigation="La navigation";
const TXTlabels="Les étiquettes";
const TXTrecords="Les enregistrements";
const TXThide="Cacher";
const TXTframeTag="Balise un contenu qui doit être encadré par le block Id cadre et contenant";
const TXTframeContent="Dans un cadre = indique l'emplacement d'insertion";
const TXTblockTag="Insertion du block dont l'identité est Id";
//mnus.htm
const TXTmnuLvl="niveau d'arborescence";
const TXTmnuNb="rang dans le niveau d'arborescence";
const TXTmnuId="identité de la page";
const TXTmnuTitle="titre de la page";
const TXTmnuSelected="est la page sélectionnée";
const TXTmnuPth="fil d'ariane des titres";
const TXTmnuHrf="si apache mod_rewrite gère le href en conséquence";
const TxtmnuLen="nombre de pages de ce menu";

const TXTmnuMacros="Macros des menus";
const TXTpasteSitemap=	"Pour vous familiarisez avec les macros, cliquez <a href=\"javascript:cms.EditIns(cms.Elm('SitMap').innerHTML)\" style=color:green>ICI</a> afin de coller et tester un plan du site complet<br>
			Ce plan du site utilise toutes les macros.<br>
			A vous de supprimer celles qui ne vous interressent pas et d'ajouter si nécessaire des conditions d'affichage en php.<br>";
const TXTcmsMnu0="cmsMnu0 correspond au 1er niveau de l'arborescence";
const TXTopeningMnuTag="tag de début du niveau d'arborescence 0";
const TXTclosingMnuTag="tag de fin du niveau d'arborescence 0";
const TXTnextMnu="Tag d'itération sur les niveaux inferieurs";
const TXTidentifiedBlocks="Ci-dessous les variables d'un bloc identifié par son identité (remplacer IDBLOCK par la bonne valeur)";
const TXTgiveIdentity="Donner une identité";
const TXTlngMacros="Macros des langues";
const TXTopeningLngTag="début du tag des langues";
const TXTclosingLngTag="fin du tag des langues";
//props
const TXTgenProps="PROPRIETE GENERALES";
const TXTrwtMod="Mode rewrite activé";
const TXTautomatic="automatique";
const TXTevery="toutes les";
const TXTsavDatabase="SAUVEGARDES BASE";
const TXTsavNow="Sauvegarder maintenant";
const TXTselectDate="Vous devez sélectionner une date";
const TXTlanguageDefinition="DEFINITION DES LANGUES";
const TXTnewLanguages="Nouvelle langue en développement";
const TXTremovelastLanguage="Retirer la dernière langue";
const TXTpublishLastLanguage="Publier la dernière langue";
const TXTdefaultLanguage="Langue par défaut";
//records.htm
const TXTidentities="Les identités";
const TXThorVerExt="extension horizontale et verticale des tables";
const TXTdefaultId="Par défaut l'identité";
const TXTisFormed="est formé pour ce bloc";
const TXTextendTable="Si vous désirez étendre la table existante d'un autre bloc, la choisir dans le select de 'clés de' et décocher la case id";
const TXTcreateSubTable="Si vous désirez créer une sous-table de la table d'un bloc existant, la choisir dans le select de 'clés de' et cocher la case";
const TXTname="nom";
const TXTcoupleInputName="Le select permet d'insérer les couples 'input (type)' prédéfinis. A vous de modifier le type à votre convenance.<br>
				Si aucun des types ne vous convient, choisissez 'standard' et ajouter le type qui vous convient.<br>
				<b>RQ</b>: Le type tree permet de créer une arborescence (une arborescence à un niveau est un select ou des radio) et crée si<br>
				elle n'existe déjà une entrée spéciale dans le champs title de la table adminTreesLang avec le nom que vous fournissez.";
const TXTprivateData="Les données de la ou les tables d'un formulaire n'ont pas besoin d'être publiées.<br>
				Ces données sont privées et ne peuvent servir à l'élaboration d'un module.<br>
				En général, il n'est pas nécessaire de créer des champs dépendants de la langue.";
const TXTdelTables="Les champs supprimés ne sont effectivement effacés dans la table que s'ils sont vides.<br>
				Une table suprimée n'est plus prise en compte par le cms mais elle existe toujours dans la base.<br>
				A vous de nettoyer votre table avec le lien sql (configuré par défaut pour phpmyadmin).";
const TXTnewsForms="ENREGISTREMENTS / FORMULAIRES";
const TXThelp="Aide";
const TXTisForm="Est un formulaire";
const TXTnonLanguageDependant="Table <b>block<edt></edt></b> des champs INDEPENDANTS de la langue";
const TXTkeys="Les clés";
const TXTidIfChecked="identité (si case cochée)";
const TXTparentId="identité parent";
const TXTrecordClassification="Champs de classement des enregistrements";
const TXTeditFields="Editer vos champs";
const TXTtrace="Traçabilité";
const TXTdate="date";
const TXTlanguageDependant="Table <b>block<edt></edt>Lang</b> des champs DEPENDANTS de la langue";
const TXTallKeys="toutes les clés de <b>block<edt></edt></b>";
const TXTlanguageKey="clé de langue";
const TXTpasteAll="Cliquer ici pour coller l'ensemble";
const TXTbeginListing="début de listing";
const TXTendListing="fin de listing";
const TXTrecordingForm="Formulaire d'enregistrement";
const TXTmailTo="L'input mailTo[] est facultatif.<br>
			Une adresse mail dans mailTo[] génère un mail du résultat.<br>
			Vous pouvez ajouter autant de mailTo[] que necessaire.<br><br>";
const TXTcompulsoryInput="Les input tables[] et id sont obligatoires";
//rls.htm
const TXTdatabase="Base de Données";
const TXTblocks="Blocs";
const TXTtrees="Arbres";
const TXThtaccess="Fichiers de variables menus recalculés et enregistrés";
const TXTunchanged="Inchangé";
const TXTpublished="Publié";
const TXTdeleted="Supprimé";
const TXTdynamic="Dynamique";
const TXTstatic="Statique";
const TXTcache="Cache";
const TXTrealTime="Temps réel";
const TXTcacheMoved="Cache déplacée vers sav/tmp";
const TXTfiles="Fichiers";
//const TXTresumeFiles="#n1 fichiers inchangés<br>#n2 fichiers publiés<br>#n3 fichiers supprimés<br><br>durée totale #t s";
//const TXTnone="aucun";
const TXTfilesUnchanged="Ces fichiers sont marqués comme inchangés";
const TXTfilesMoved="Anciens fichiers dans /sav et remplacés par nouveaux fichiers";
const TXTfilesSaved="Ces fichiers on étés sauvegardés dans /sav";
const TXTblocksPub="Publication de blocs";
const TXTmultBlocks="Multiples blocs";
const TXTselectedBlock="Bloc sélectionné";
const TXTflashPub="Publication flash (vérif timestamp)";
const TXTpubPop="Publier";
const TXTpubResume="SYNTHESE PUBLICATION";
//search.htm
const TXTsearchHlp1="Il faut d'abord spécifier les identités dess étiquettes statiques, enregistrements dynamiques, répertoire et (ou) fichiers qui sont des cibles de la recherche.<br>
			Le joker <font color=red>*</font> indiquer de recherche dans tout. 'Tous les répertoires' signifie:";
const TXTsearchSep="Le séparateur des entrées multiples est la '<font color=red>,</font>'.<br>";
const TXTsearchHlp2="Pour chaque entrée séparée par une '<font color=red>,</font>' il faut une entrée correspondante '<font color=red>,</font>' dans lechamps de remplacement.<br>
				Replacements are done in the given order.<br>
				Joker <font color=red>*</font> can be used in search and replace in order to leave the <font color=red>*</font> content unchanged when replacing.<br>
				L'<font color=red>*</font> et la <font color=red>,</font> utilisées en tant que caractère normal doivent être doublées: <font color=red>**</font> et <font color=red>,,</font>";
const TXTsearchHlp3="Avant de cliquer surle bouton remplacement, faites d'abord une recherche avec le bouton de recherche.<br>";
//uploads.htm
const TXTfolder="Répertoire";
const TXTdelete="Supprimer";
const TXTrename="Reommer";
const TXTdimension="Dimension";
//users.htm
const TXTgroup="groupe";
const TXTuser="user";
const TXTpass="passe";
const TXTinterfaceLng="Langue de l'interface";
const TXTcompte="COMPTE";
const TXTprivileges="PRIVILEGES";
const TXTcaracteristics="CARACTERISTIQUES";

//DOUBLONS avec lng-do.php
//bldweb_trees
const TXTup="Monter";
const TXTdown="Descendre";
const TXTsub="Ajouter un niveau inferieur";
const TXTadd="Ajouter";
?>
