<!DOCTYPE html>
<?php
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.\Library\LanguagesManager::getLanguage().'" lang="'.\Library\LanguagesManager::getLanguage().'">';
?>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<title><?php echo isset($title) ? $title : 'my website'; // mon titre géré par variable ?></title>

	<meta name="keywords" content="my website keywords">
	<meta name="robots" content="index">
	<meta name="REVISIT-AFTER" content="7 days">
	
	<!-- logo miniature du site internet -->
	<link rel="shortcut icon" href="../../Web/images/design/favicone.ico" type="image/x-icon"/>

	<!-- fichiers auxiliaire css et javascript -->
	<?php
		echo '<link rel="stylesheet" href="../../Web/css/style.css" type="text/css" media="screen" />';
	?>
	<!-- gestion de la lightbox pour les galeries -->
	<script src="../../Lightbox/js/jquery-1.10.2.min.js"></script>
	<script src="../../Lightbox/js/lightbox-2.6.min.js"></script>

	<link href="../../Lightbox/css/lightbox.css" rel="stylesheet" />
<!-- ANALYTICS -->
<?php include_once("analyticstracking.php") ?>
</head>
<body>
	<div class="bg">
		<div class="header_area">
		 	<div class="header_wrapper">
			<a href="/">
				<div class="logo">
				</div>
			</a>
			</div>
			<a href="/">
			<div class="header">
				My Website
				<br/>
				<div class="img"></div><div class="img"></div>
				<div id="subtitle">Website subtitle</div>
				<div class="languages">
					<?php
						// affiche le choix de langue
						/* désactivation des langues
						foreach (\Library\LanguagesManager::getLanguages() as $value) {
							echo '<div class="';
							if($value == LANGUAGE){
								echo 'selected_';
							}
							echo 'language" onClick="javascript:document.location.href=location.href.replace(\'/'.LANGUAGE.'/\', \'/'.$value.'/\')">'.$value.'</div>';
						}*/
					?>
				</div>
			</div>
			</a>
		</div>
		<div class="main_body">
			<div class="menu_area">

				<?php
				$currentAdded = false;
				foreach ($menu as $singleMenu) {
					if(!$singleMenu->getVisible()){
						continue;
					}

					$menuList = '';
					$submenuList = '';

					if(!is_null($singleMenu->getDescription())){
						$menuList .= '<abbr title="'.$singleMenu->getDescription().'">';
					}

					$menuList .= '<a class="menu';

					foreach ($singleMenu->getSubmenus() as $singleSubmenu) {
						if(!$singleSubmenu->getVisible()){
							continue;
						}

						if(!is_null($singleSubmenu->getDescription())){
							$submenuList .= '<abbr title="'.$singleSubmenu->getDescription().'">';
						}
						// sous-menu, mais on va les mettre comme des menus pour cette phase..
						$submenuList .= '<a class="submenu';
						if($requestURI == $singleSubmenu->getURI() && !$currentAdded){
							$submenuList .= '_current';
							$menuList .= '_current';
							$currentAdded = true;
						}
						$submenuList .= '" href="'.$singleSubmenu->getURI().'">'.$singleSubmenu->getName().'</a>';
						
						if(!is_null($singleSubmenu->getDescription())){
							$submenuList .= '</abbr>';
						}
					}

					// mise en évidence du menu actuel
					if(($singleMenu->getURI() == $requestURI && !$currentAdded) || $requestURI == '/' && $singleMenu == $menu[0]){
						$menuList .= '_current';
						$currentAdded = true;
					}

					$menuList .= '" href="'.$singleMenu->getURI().'">'.$singleMenu->getName().'</a>';

					if(!is_null($singleMenu->getDescription())){
						$menuList .= '</abbr>';
					}

					echo $menuList.$submenuList;
				}
				?>

			</div>

			<div class="content_area">
				<div class="content">
					<?php
					// on ajoute le contenu php dynamique relatif à notre page
					echo $content;
					?>
				</div>
			</div>
			<hr class="clear"/>
		</div>
		<footer class="footer_area_center">
			<div class="footer_area">
				<div class="img_left">
				</div>
				<div class="footer">
					<div style="float:right;display:inline-block;padding-right:15px">Website designed by <a target="_blanck" href="http://www.theophile-blanchon.ch">Théophile Blanchon</a> developped by <a href="mailto:my_email@fai.com">Robin Herzog</a></div><div style="display:inline-block;padding-left:15px">Copyright all rights reserved planetdesign</div> 
				</div>
				<div class="img_right">
				</div>
			</div>
		</footer>
	</div>
</body>
</html>
