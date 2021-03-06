<?php
	include_once "FonctionsCommunes.php";
	
	//Les projets auxquels a accès le visiteur
	$resuPROJ = Requis("SELECT id, project_id FROM projects_users WHERE user_id = ".$_GET["Qui"]."", $db);
	if (Nombre($resuPROJ) > 0) {
		while ($QuelPROJ = Fetche($resuPROJ)) {
			$lesProj[] = $QuelPROJ["project_id"];
		}
	}
	$mesProjets = implode(",", $lesProj);

	//Afficher les liens vers les informations obtenues par la recheche
	////Afficher les projets actifs parmi les ceux auxquels a accès le visiteur
	$requPROJ = (is_numeric($_GET["Quoi"])) ? "SELECT id,name,status FROM projects WHERE id LIKE '%".$_GET["Quoi"]."%' AND id IN (".$mesProjets.")" : "SELECT id,name,status FROM projects WHERE name LIKE '%".$_GET["Quoi"]."%' AND status = 1 AND id IN (".$mesProjets.")";
	$resuPROJ = Requis($requPROJ, $db);
	if (Nombre($resuPROJ) > 0) {
		while ($QuelPROJ = Fetche($resuPROJ)) {
			echo '<div class="ResuRech Projet">';
			echo '<a href="'.$adresse.'project/'.$QuelPROJ["id"].'/issues?tag_id=1">'.$QuelPROJ["name"].'</a>';
			echo '</div>';
		}
	}

	////Afficher les résultats parmi les billets des différents projets actifs auxquels a accès le visiteur et dont le titre ou le contenu contient l'élément recherché.
	$requISSU = (is_numeric($_GET["Quoi"])) ? "SELECT ISSU.id, IF(ISSU.closed_by IS NULL, 'Actif', 'Closed') AS etat,ISSU.status,ISSU.title, ISSU.project_id, PROJ.name FROM projects_issues AS ISSU LEFT JOIN projects AS PROJ ON PROJ.id = ISSU.project_id WHERE ISSU.id LIKE '%".$_GET["Quoi"]."%'AND project_id IN (".$mesProjets.") ORDER BY etat ASC, ISSU.updated_at DESC" : "SELECT ISSU.id, IF(ISSU.closed_by IS NULL, 'Actif', 'Closed') AS etat,ISSU.status,ISSU.title, ISSU.project_id, PROJ.name FROM projects_issues AS ISSU LEFT JOIN projects AS PROJ ON PROJ.id = ISSU.project_id WHERE (title LIKE '%".$_GET["Quoi"]."%' OR body  LIKE '%".$_GET["Quoi"]."%') AND project_id IN (".$mesProjets.") ORDER BY etat ASC, ISSU.updated_at DESC";
	$resuISSU = Requis($requISSU, $db );
	if (Nombre($resuISSU) > 0) {
		while ($QuelISSU = Fetche($resuISSU)) {
			echo '<div class="ResuRech'.(($QuelISSU["etat"] == 'Closed') ? ' Closed' : '').'">';
			echo '<a href="'.$adresse.'project/'.$QuelISSU["project_id"].'/issue/'.$QuelISSU["id"].'">'.$QuelISSU["title"].'</a> ( '.$QuelISSU["name"].' )';
			echo '</div>';
		}
	}

	////Afficher les résultats parmi les commentaires des différents projets actifs auxquels a accès le visiteur et dont les contenu contient l'élément recherché.
	if (!is_numeric($_GET["Quoi"]) and strlen($_GET["Quoi"]) > 5)  {
		$requCOMM = "SELECT COMM.id, COMM.issue_id AS 'NumTicket', IF(ISSU.closed_by IS NULL, 'Actif', 'Closed') AS etat, ";
		$requCOMM .= "ISSU.status,ISSU.title, ";
		$requCOMM .= "ISSU.project_id, ";
		$requCOMM .= "PROJ.name, SUBSTRING(COMM.comment, 4, 20) AS content ";
		$requCOMM .= "FROM projects_issues_comments AS COMM ";
		$requCOMM .= "LEFT JOIN projects_issues AS ISSU ON COMM.issue_id = ISSU.id ";
		$requCOMM .= "LEFT JOIN projects AS PROJ ON PROJ.id = COMM.project_id ";
		$requCOMM .= "WHERE (comment LIKE '%".$_GET["Quoi"]."%') AND COMM.project_id IN (".$mesProjets.") ";
		$requCOMM .= "ORDER BY etat ASC, ISSU.updated_at DESC";
		$resuCOMM = Requis($requCOMM, $db );
		if (Nombre($resuCOMM) > 0) {
			while ($QuelCOMM = Fetche($resuCOMM)) {
				echo '<div class="ResuRech'.(($QuelCOMM["etat"] == 'Closed') ? ' Closed' : '').' ResuRechComment">';
				echo '<a href="'.$adresse.'project/'.$QuelCOMM["project_id"].'/issue/'.$QuelCOMM["NumTicket"].'">'.$QuelCOMM["title"].'</a> ( '.$QuelCOMM["content"].'... )';
				echo '</div>';
			}
		}
	}
?>
