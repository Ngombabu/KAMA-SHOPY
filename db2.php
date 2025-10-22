<?php
  //connexion a la base de donnée
  $serveur="sql100.byetcluster.com";
  $sesion="if0_38971387";
  $mdp="ngombabu2002";
  $dbname="if0_38971387_kama";
  $connecte=new mysqli($serveur,$sesion,$mdp,$dbname);

  if ($connecte->connect_error) {
      die("echec de connexion à la base de donnée: " . $connecte->connect_error);
  }
?>

