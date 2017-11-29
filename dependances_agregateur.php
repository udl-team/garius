<?php
// Fichiers de conf
foreach(glob("conf/*.conf.php") as $fichier)
{
	require_once($fichier);
	if(!TEST)echo "\n Importation conf/$fichier";
}
//classes php
foreach(glob("classes/*.php") as $fichier)
{
	require_once $fichier;
	if(!TEST)echo "\n Importation classes/$fichier";
}
?>
