<?php
include_once "db.php";

$retour = 0;
if ($_GET["Quoi"] == 1) {
	$tags = $_GET["tags"] ?? 1;
	$attached = $_GET["attached"] ?? 1;
	$retour = 1;
	if ($_GET["Etat"] == 0) {
		$retour = (Requis("INSERT INTO following VALUES(NULL, ".$_GET["Qui"].", ".$_GET["Project"].", ".$_GET["Quel"].", 0, ".$attached.", ".$tags.")")) ? 2 : $retour;
	} else {
		$retour = (Requis("DELETE FROM following WHERE user_id = ".$_GET["Qui"]." AND project_id = ".$_GET["Project"]." AND issue_id = ".$_GET["Quel"]." AND project = 0")) ? 3 : $retour;
	}
}
if ($_GET["Quoi"] == 2) {
	$retour = 4;
	if ($_GET["Etat"] == 0) {
		$retour = (Requis("INSERT INTO following VALUES(NULL, ".$_GET["Qui"].", ".$_GET["Project"].", 0, 1, 0, 0)")) ? 5 : $retour;
	} else {
		$retour = (Requis("DELETE FROM following WHERE user_id = ".$_GET["Qui"]." AND project_id = ".$_GET["Project"]." AND project = 1")) ? 6 : $retour;
	}
}
echo $retour.' car nous avons reçu : '.$_GET["Etat"];
?>
