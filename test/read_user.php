<?php 
echo "\nIMPORTATION DES DEPENDANCES \n";
require_once ("dependances_agregateur.php");

echo "\nINSTANCIATION uid=1 \n";
$udlperson=new Person(1);

echo "\nString:\n";
echo $udlperson->to_string();

echo "\nJson:\n";
echo $udlperson->to_json();


		
?>