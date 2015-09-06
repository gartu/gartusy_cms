<?php

namespace Library;

class Utils{

	// --------------------------------------------------- http://j-reaux.developpez.com/tutoriel/php/fonctions-troncature-texte/
	// RÉSUMÉ d'un texte HTML : en fonction du NOMBRE de CARACTERES
	public static function texte_resume_html($texte, $nbreCar) {
		if(is_numeric($nbreCar)){
			$PointSuspension		= '...'; // points de suspension
			$LongueurAvantSansHtml	= strlen(trim(strip_tags($texte)));
			$MasqueHtmlSplit		= '#</?([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?>#';
			$MasqueHtmlMatch		= '#<(?:/([a-zA-Z1-6]+)|([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?)>#';
			$texte					.= ' ';
			$BoutsTexte				= preg_split($MasqueHtmlSplit, $texte, -1,  PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);
			$NombreBouts			= count($BoutsTexte);
			if( $NombreBouts == 1 ){
				$texte				.= ' ';
				$LongueurAvant		= strlen($texte);
				$texte 				= substr($texte, 0, strpos($texte, ' ', $LongueurAvant > $nbreCar ? $nbreCar : $LongueurAvant));
				if ($PointSuspension!='' && $LongueurAvant > $nbreCar) {
					$texte			.= $PointSuspension;
				}
			} else {
				$longueur				= 0;
				$indexDernierBout		= $NombreBouts - 1;
				$position				= $BoutsTexte[$indexDernierBout][1] + strlen($BoutsTexte[$indexDernierBout][0]) - 1;
				$indexBout				= $indexDernierBout;
				$rechercheEspace		= true;
				foreach( $BoutsTexte as $index => $bout )
				{
					$longueur += strlen($bout[0]);
					if( $longueur >= $nbreCar )
					{
						 $position_fin_bout = $bout[1] + strlen($bout[0]) - 1;
						 $position = $position_fin_bout - ($longueur - $nbreCar);
						 if( ($positionEspace = strpos($bout[0], ' ', $position - $bout[1])) !== false  )
						 {
								$position	= $bout[1] + $positionEspace;
								$rechercheEspace = false;
						 }
						 if( $index != $indexDernierBout )
								$indexBout	= $index + 1;
						 break;
					}
				}
				if( $rechercheEspace === true ){
					for( $i=$indexBout; $i<=$indexDernierBout; $i++ ){
						 $position = $BoutsTexte[$i][1];
						 if( ($positionEspace = strpos($BoutsTexte[$i][0], ' ')) !== false ){
								$position += $positionEspace;
								break;
						 }
					}
				}
				$texte					= substr($texte, 0, $position);
				preg_match_all($MasqueHtmlMatch, $texte, $retour, PREG_OFFSET_CAPTURE);
				$BoutsTag				= array();
				foreach( $retour[0] as $index => $tag ){
					if( isset($retour[3][$index][0]) ){
						 continue;
					}
					if( $retour[0][$index][0][1] != '/' ){
						 array_unshift($BoutsTag, $retour[2][$index][0]);
					} else {
						 array_shift($BoutsTag);
					}
				}
				if( !empty($BoutsTag) ){
					foreach( $BoutsTag as $tag ){
						 $texte		.= '</'.$tag.'>';
					}
				}
				if ($PointSuspension!='' && $LongueurAvantSansHtml > $nbreCar) {
					$texte				.= 'ReplacePointSuspension';
					$pattern			= '#((</[^>]*>[\n\t\r ]*)?(</[^>]*>[\n\t\r ]*)?((</[^>]*>)[\n\t\r ]*)?(</[^>]*>)[\n\t\r ]*ReplacePointSuspension)#i';
					$texte				= preg_replace($pattern, $PointSuspension.'${2}${3}${5}', $texte);
				}
			}
		}
		// correction d'un bug si le texte commence par une balise <p>
		if(substr($texte, 0, 3) == '<p>' && substr($texte, -4, 4) != '</p>'){
			return $texte.'</p>';
		}else{
			return $texte;
		}
	}
};
?>