<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<title><?php echo isset($title) ? $title : 'Administration' ?></title>
	
	<!-- logo miniature du site internet -->
	<link rel="shortcut icon" href="" type="image/x-icon"/> 
	<link rel="icon" href="" type="image/x-icon"/>

	<!-- fichiers auxiliaire css et javascript -->
	<link rel="stylesheet" href="../../../Applications/Backend/Templates/style.css" type="text/css" media="screen" />

	<!-- permet d'ajouter aux textarea une gestion de contenu via nicedit (Désactivé) -->
	<!--script src="../../Web/nicEdit/nicedit.js" type="text/javascript"></script-->

	<script src="../../Web/js/serialize.js" type="text/javascript"></script>

	<!-- permet d'ajouter aux textarea une gestion de contenu via TinyMCE -->
	<script type="text/javascript" src="../../Web/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	<?php
		$plugins = '"autolink link searchreplace media paste textcolor save';
		if(isset($imgEdit) && $imgEdit){
			$plugins .= ' image';
		}
		$plugins .= '"';
	?>
	tinymce.init({
		menubar:false,
		statusbar: false,
	    selector: "textarea.editor",
	    plugins: [<?php echo $plugins; ?>],
		content_css: "../../Web/css/style.css", // on retire ça pour quelques test.. car span non désirés
		toolbar: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor",
		save_onsavecallback: "saveData",
	    image_list: <?php require('gen_json_for_pics.php'); ?>,
    	link_list: <?php require('gen_json_for_docs.php'); ?>,
		style_formats: [
	        {title: <?php echo '\''.\Library\LanguagesManager::get('title').'\''; ?>, block: 'h1'},
	        {title: <?php echo '\''.\Library\LanguagesManager::get('subtitle').'\''; ?>, block: 'h2'},
	        {title: <?php echo '\''.\Library\LanguagesManager::get('normal').'\''; ?>, inline: ''},
	        {title: <?php echo '\''.\Library\LanguagesManager::get('note').'\''; ?>, inline: 'span', styles: {'font-size' : '12px'}},
			{title: <?php echo '\''.\Library\LanguagesManager::get('image_right').'\''; ?>, selector: 'img', styles: {'float' : 'right', 'margin': '5px 5px 10px 10px'}},
			{title: <?php echo '\''.\Library\LanguagesManager::get('image_left').'\''; ?>, selector: 'img', styles: {'float' : 'left', 'margin': '5px 10px 5px 10px'}}
	    ]
	 });
	tinymce.ui.Control.setDisabled(true);
	</script>
	<!-- laisser l'ouverture / fermeture du script sinon le ctrl-s ne fonctionne pas -->
	<script type="text/javascript">

	// gestion de la sauvegarde en ctrl-s
	var isCtrl = false;
	document.onkeyup=function(e){
		if(e.which == 17) 
			isCtrl = false;
	}

	document.onkeydown = function(e){
		if(e.which == 17){
			isCtrl = true;
		}

		if(e.which == 83 && isCtrl == true) {
			isCtrl = false;
			saveData();
			return false;
		}
	}
	
	// fonctionne de pair avec la classe php ContactFormFormBuilder
	function saveParamsAndHandleFields(numberFields, removedField){
		saveAll();
		var params = new Array();
		params['numberFields'] = ((removedField !== '') ? numberFields : (numberFields + 1));
		params['id'] = document.getElementById('id').value;
		params['name'] = document.getElementById('ContactForm_name').value;
		params['receiver'] = document.getElementById('ContactForm_receiver').value;
		params['description'] = document.getElementById('ContactForm_description-editor').value;

		var shift = 0;
		var i = 0;
		do{
			if(i === removedField){
				shift = 1;
			}

			params['fieldId' + i] = document.getElementById('fieldId' + (i + shift)).value;
			params['metric' + i] = document.getElementById('ContactForm_metric' + (i + shift)).value;
			params['nameField' + i] = document.getElementById('ContactForm_nameField' + (i + shift)).value;
			params['fieldType' + i] = document.getElementById('ContactForm_fieldType' + (i + shift)).value;
			params['obligatory' + i] = (document.getElementById('ContactForm_obligatory' + (i + shift)).checked ? '1' : '0');
			params['helpMessage' + i] = document.getElementById('ContactForm_helpMessage' + (i + shift)).value;

		}while(++i < numberFields);

		// on a tronqué le / par un ? car les slash dans l'url modifie le chemin relatif aux ressources utilisé dans le layout
		document.getElementById("params").value = base64_encode(serialize(params)).replace('/', '?');
	}

	// modifie l'enctype du formulaire principal
	function setEnctype(enctype){
		document.forms['mainForm'].enctype = enctype;
	}

	// demande la suppression d'une image et envoie le formulaire
	function pictureSuppression(id, confirmMsg){
		if(confirm(confirmMsg)){
			var val = parseInt(document.getElementById('suppression' + id).value);
			document.getElementById('suppression' + id).value = ((val + 1) % 2);
			saveData();
		}
	}

	// sauvegarde les données et envoie le formulaire
	function saveData(){
		saveAll();
		document.forms['mainForm'].submit();
	}

	// sauvegarde les données, c'est à dire "synchronise" les champs réél avec les apparences
	function saveAll(){
		// utilisé uniquement pour tinyMCE
		tinymce.triggerSave();
	}

	</script>
</head>
<?php echo '<body '.(isset($onload) ? 'onload="'.$onload.'"' : '').'>'; ?>
	<div class="cMain">
		<div class="cHeader">
			<h2>Espace d'administration</h2>
			<?php 
			// on écrit l'éventuel message pour l'utilisateur
			echo $currentUser->hasMessage() ? \Library\LanguagesManager::get('message').' : '.$currentUser->getMessage() : \Library\LanguagesManager::get('no_message'); 
			echo '<a id="adm_btn1" href="/admin/'.\Library\LanguagesManager::getLanguage().'/contenu-utilisateur-'.$currentUser->getUser()->getLogin().'.html">'.\Library\LanguagesManager::get('manage_admin').'</a>'; 
			echo '<a id="adm_btn2" href="/admin/'.\Library\LanguagesManager::getLanguage().'/deconnexion.html">'.\library\LanguagesManager::get('disconnection').'</a>';
			?>
			<div class="buttons">
				<?php echo '<a href="/admin/'.LANGUAGE.'/insert-page.html" id="home">'; ?></a>
				<a href="javascript:window.location=document.referrer" id="back"></a>
				<a href="javascript:history.go(+1);" id="next"></a>
				<a href="javascript:saveData()" id="save"></a>
				<br/>
				<?php
					// affiche le choix de langue
					echo '<select onChange="javascript:document.location.href=location.href.replace(\'/'.LANGUAGE.'/\', \'/\'+this[this.selectedIndex].value+\'/\')">';
					foreach (\Library\LanguagesManager::getLanguages() as $value) {
						echo '<option value="'.$value.'"';
						if($value == LANGUAGE){
							echo ' selected';
						}
						echo '>'.$value.'</option>';
					}
					echo '</select>';
				?>
			</div>

		</div>
		<div class="cMainBody">

			<div class="cLeftSide">
				<?php
					echo '<h3>'.\Library\LanguagesManager::get('page_list').'</h3><ul class="pagesMenu">';
					foreach ($menu as $singleMenu) {
						echo '<hr style="background-color:#819fa7;margin:0px;border:none;height:3px" /><li><a';
						
						if('/admin/'.LANGUAGE.'/page-'.$singleMenu->getId().'.html' == $requestURI){
							echo ' class="selectedMenu"';
						}
						echo ' href="/admin/'.LANGUAGE.'/page-'.$singleMenu->getId().'.html">'.$singleMenu->getName().'</a>';
						$subMenu = $singleMenu->getSubmenus();
						if(!empty($subMenu)){
							echo '<ul>';
							foreach ($subMenu as $singleSubmenu) {
								echo '<li><a';
								if('/admin/'.LANGUAGE.'/page-'.$singleMenu->getId().'-'.$singleSubmenu->getId().'.html' == $requestURI){
									echo ' class="selectedMenu"';
								}
								echo ' href="/admin/'.LANGUAGE.'/page-'.$singleMenu->getId().'-'.$singleSubmenu->getId().'.html">'.$singleSubmenu->getName().'</a></li>';
							}
							echo '</ul>';
						}
						echo '</li>';
					}
					echo '</ul><br/>';
				?>
			</div>

			<div class="cRightSide">
				<div class="cTopMenu">
				<?php
					// premièrement on affiche tous les menus présent sur le site
					// ici on ajoute les menus de gestion de module ou ne définissant pas la modification d'un menu existant
					echo '<ul class="menu">
					<li><a href="/admin/'.LANGUAGE.'/insert-page.html">'.\library\LanguagesManager::get('create_menu').'</a></li>
					<li><a href="/admin/'.LANGUAGE.'/insert-news.html">'.\library\LanguagesManager::get('news').'</a>
						<ul>
							<li><a href="/admin/'.LANGUAGE.'/insert-news.html">'.\library\LanguagesManager::get('adding').'</a></li>
							<li><a href="/admin/'.LANGUAGE.'/liste-news.html">'.\library\LanguagesManager::get('management').'</a></li>
						</ul>
					</li>
					<li><a href="/admin/'.LANGUAGE.'/insert-news-category.html">'.\library\LanguagesManager::get('news-category').'</a>
						<ul>
							<li><a href="/admin/'.LANGUAGE.'/insert-news-category.html">'.\library\LanguagesManager::get('adding').'</a></li>
							<li><a href="/admin/'.LANGUAGE.'/modify-news-category.html">'.\library\LanguagesManager::get('management').'</a></li>
						</ul>
					</li>
					<li><a href="/admin/'.LANGUAGE.'/insert-form.html">'.\library\LanguagesManager::get('formulaire').'</a>
						<ul>
							<li><a href="/admin/'.LANGUAGE.'/insert-form.html">'.\library\LanguagesManager::get('adding').'</a></li>
							<li><a href="/admin/'.LANGUAGE.'/manage-formulaire.html">'.\library\LanguagesManager::get('management').'</a></li>							
						</ul>
					</li>
					<li><a href="/admin/'.LANGUAGE.'/insert-galery.html">'.\library\LanguagesManager::get('galery').'</a>
						<ul>
							<li><a href="/admin/'.LANGUAGE.'/insert-galery.html">'.\library\LanguagesManager::get('adding').'</a></li>
							<li><a href="/admin/'.LANGUAGE.'/manage-galery.html">'.\library\LanguagesManager::get('management').'</a></li>							
						</ul>
					</li>
					<li><a href="/admin/'.LANGUAGE.'/insert-file.html">'.\library\LanguagesManager::get('files').'</a>
						<ul>
							<li><a href="/admin/'.LANGUAGE.'/insert-file.html">'.\library\LanguagesManager::get('adding').'</a></li>
							<li><a href="/admin/'.LANGUAGE.'/manage-files.html">'.\library\LanguagesManager::get('management').'</a></li>							
						</ul>
					</li>';
					//echo '<li><a href="/admin/'.LANGUAGE.'/insert-news-subject.html">'.\library\LanguagesManager::get('create_news_subject').'</a></li>';
					//echo '<li><a href="/admin/'.LANGUAGE.'/insert-form.html">'.\library\LanguagesManager::get('create_form').'</a></li>';
					echo '</ul><br/>';
					?>
				</div>
				<hr style="background-color:#cad3d8;margin:0px;border:none;height:3px" />
				<div class="cBody">
					<?php
					
					// on ajoute le contenu php dynamique relatif à notre page
					echo $content;
					?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>