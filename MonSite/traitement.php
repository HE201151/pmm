<?php 
function connexion{

	if((!empty($_POST['pseudo'])) and (!empty($_POST['pass']))){
		$authentified=True;
		creeMenu($authentified);
	}
	else{
		echo 'erreur <br/>';
		if(empty($_POST['pseudo'])){
			echo 'Pseudo vide';
		}
		else{
			echo 'Mot de passe vide';
		}
	}
}
?>