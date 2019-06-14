<?php 
include('Carte.php');
include('Tresor.php');
include('Montagne.php');
include('Aventurier.php');
include('Gobelin.php');
include('Orc.php');
?>

<?php
//Read file
$myfile = fopen("entry.txt", "r") or die("Unable to open file!");
while(!feof($myfile)) {
  $a = fgets($myfile);
  $arr = explode(' - ',$a);
  if($arr[0][0] == 'C') {
    $carte = new Carte($arr[1], $arr[2]);
  }
  if($arr[0][0] == 'M') {
    $montagne = new Montagne($arr[1], $arr[2]);
    $arrayMontagne[] = $montagne;
  }
  
  if($arr[0][0] == 'T') {
    $tresor = new Tresor($arr[1], $arr[2], $arr[3]);
    $arrayTresor[] = $tresor;
  }
  
  if($arr[0][0] == 'A') {
    $aventurier = new Aventurier($arr[1], $arr[2], $arr[3], $arr[4], $arr[5]);
    $arrayAventurier[] = $aventurier;
  }
  if($arr[0][0] == 'G') {
    $gobelin = new Gobelin($arr[1], $arr[2], $arr[3], $arr[4]);
    $arrayGobelin[] = $gobelin;
  }
  if($arr[0][0] == 'O') {
    $orc = new Orc($arr[1], $arr[2], $arr[3], $arr[4]);
    $arrayOrc[] = $orc;
  }
  $arr = null;
}
fclose($myfile);


//Afficher carte
function showMap($carte, $arrayMontagne, $arrayTresor, $arrayAventurier, $arrayOrc, $arrayGobelin) {
  echo '<table>';
  for($i = 0; $i<$carte->hauteur;$i++){
    echo '<tr>';
    for($j = 0; $j<$carte->largeur; $j++){
      //Show Montagne
      $a = true;
      if($a) {
        foreach($arrayAventurier as &$aventurier) {
          if($i == $aventurier->vertical && $j == $aventurier->horizontal && $aventurier->alive == 'L'){
            $a = false;
            echo '<td>A('.$aventurier->name.')</td>';
          }
        }
      }
      if($a) {
        foreach($arrayMontagne as &$montagne) {
          if($i == $montagne->vertical && $j == $montagne->horizontal){
            $a = false;
            echo '<td>M</td>';
          }
        }
      }
      if($a) {
        foreach($arrayTresor as &$tresor) {
          if($i == $tresor->vertical && $j == $tresor->horizontal){
            $a = false;
            echo '<td>T('.$tresor->nbTresor.')</td>';
          }
        }
      }
      if($a) {
        foreach($arrayGobelin as &$gobelin) {
          if($i == $gobelin->vertical && $j == $gobelin->horizontal && $gobelin->alive == 'L'){
            $a = false;
            echo '<td>G</td>';
          }
        }
      }
      if($a) {
        foreach($arrayOrc as &$orc) {
          if($i == $orc->vertical && $j == $orc->horizontal){
            $a = false;
            echo '<td>O</td>';
          }
        }
      }
      if($a) {
        echo '<td> . </td>';
      }
    }
    echo '</tr>';
  }
  echo '</table>';
}

function bloquerDeplacement($carte, $arrayMontagne, $aventurierHorizontal, $aventurierVertical, $arrayAventurier) {
  $a = true;
  for($i = 0; $i<sizeof($arrayMontagne); $i++) {
    if($aventurierVertical == $arrayMontagne[$i]->vertical && $aventurierHorizontal == $arrayMontagne[$i]->horizontal){
      $a = false;
      echo ' Impossible de se deplacer ';
    }
  }
  for($i = 0; $i<sizeof($arrayAventurier); $i++) {
    if($aventurierVertical == $arrayAventurier[$i]->vertical && $aventurierHorizontal == $arrayAventurier[$i]->horizontal){
      $a = false;
      echo ' Impossible de se deplacer ';
    }
  }
  if($aventurierVertical < 0 || $aventurierHorizontal < 0 || $aventurierHorizontal == $carte->largeur || $aventurierVertical == $carte->hauteur) {
    $a = false;
    echo ' Impossible de se deplacer ';
  }
  return $a;
}

function recupererTresor($arrayTresor, $aventurierHorizontal, $aventurierVertical, $aventurier){
  for($i = 0; $i<sizeof($arrayTresor); $i++) {
    if($aventurierVertical == $arrayTresor[$i]->vertical && $aventurierHorizontal == $arrayTresor[$i]->horizontal && $arrayTresor[$i]->nbTresor > 0){
      $arrayTresor[$i]->nbTresor = $arrayTresor[$i]->nbTresor - 1;
      $aventurier->niveau = $aventurier->niveau + 1;
    }
  }
}

function deplacement($carte,$tour, $aventurier, $arrayMontagne, $arrayAventurier, $arrayTresor){
  if($aventurier->sqMouvement[$tour] && $aventurier->alive == 'L'){
    $movementTour = $aventurier->sqMouvement[$tour];
    $orientation = $aventurier->orientation;
    if($orientation == 'S') {
      if($movementTour == 'A') {
        if(bloquerDeplacement($carte, $arrayMontagne, $aventurier->horizontal, $aventurier->vertical + 1, $arrayAventurier)){
          recupererTresor($arrayTresor, $aventurier->horizontal, $aventurier->vertical + 1, $aventurier);
          $aventurier->vertical = $aventurier->vertical + 1;
        }
      }
      if($movementTour == 'D') {
        $aventurier->orientation = 'O';
      }
      if($movementTour == 'G') {
        $aventurier->orientation = 'E';
      }
    }
    if($orientation == 'N') {
      if($movementTour == 'A') {
        if(bloquerDeplacement($carte, $arrayMontagne, $aventurier->horizontal, $aventurier->vertical - 1, $arrayAventurier)){
          recupererTresor($arrayTresor, $aventurier->horizontal, $aventurier->vertical - 1, $aventurier);
          $aventurier->vertical = $aventurier->vertical - 1;
        }
      }
      if($movementTour == 'D') {
        $aventurier->orientation = 'E';
      }
      if($movementTour == 'G') {
        $aventurier->orientation = 'O';
      }
    }
    if($orientation == 'O') {
      if($movementTour == 'A') {
        if(bloquerDeplacement($carte, $arrayMontagne, $aventurier->horizontal - 1, $aventurier->vertical, $arrayAventurier)){
          recupererTresor($arrayTresor, $aventurier->horizontal - 1, $aventurier->vertical, $aventurier);
          $aventurier->horizontal = $aventurier->horizontal - 1;
        }
      }
      if($movementTour == 'D') {
        $aventurier->orientation = 'N';
      }
      if($movementTour == 'G') {
        $aventurier->orientation = 'S';
      }
    }
    if($orientation == 'E') {
      if($movementTour == 'A') {
        if(bloquerDeplacement($carte, $arrayMontagne, $aventurier->horizontal + 1, $aventurier->vertical, $arrayAventurier)){
          recupererTresor($arrayMontagne, $aventurier->horizontal + 1, $aventurier->vertical, $aventurier);
          $aventurier->horizontal = $aventurier->horizontal + 1;
        }
      }
      if($movementTour == 'D') {
        $aventurier->orientation = 'S';
      }
      if($movementTour == 'G') {
        $aventurier->orientation = 'N';
      }
    }
  }
}

function fight($monstre, $aventurier){
  if($monstre->niveau <= $aventurier->niveau) {
    return true;
  } else {
    return false;
  }
}


showMap($carte, $arrayMontagne, $arrayTresor, $arrayAventurier, $arrayOrc, $arrayGobelin);
for($i = 0; $i<19; $i++){
  // Move
  foreach ($arrayAventurier as &$aventurier) {
    deplacement($carte,$i, $aventurier, $arrayMontagne, $arrayAventurier, $arrayTresor);
  }
  foreach ($arrayOrc as &$orc) {
    $orc->move($carte->hauteur);
  }
  foreach ($arrayGobelin as &$gobelin) {
    echo $gobelin->horizontal;
    $gobelin->move($carte->largeur);
  }

  //Fight
  $iAventurier = 0;
  foreach ($arrayAventurier as &$aventurier) {
    $iOrc = 0;
    $iGobelin = 0;
    foreach ($arrayOrc as &$orc) {
      if($aventurier->horizontal == $orc->horizontal && $aventurier->vertical == $orc->vertical && $orc->alive == 'L') {
        $arrayOrc[$iOrc]->nbFight = $arrayOrc[$iOrc]->nbFight + 1;
        if(fight($orc, $aventurier)){
          $arrayAventurier[$iAventurier]->niveau += $arrayAventurier[$iAventurier]->niveau;
          $orc->dead();
        } else {
          $arrayAventurier[$iAventurier]->alive = 'D';
        }
      }
      $iOrc = $iOrc + 1;
    }
    foreach ($arrayGobelin as &$gobelin) {
      if($aventurier->horizontal == $gobelin->horizontal && $aventurier->vertical == $gobelin->vertical && $gobelin->alive == 'L') {
        $arrayGobelin[$iGobelin]->nbFight = $arrayGobelin[$iGobelin]->nbFight + 1;
        if(fight($gobelin, $aventurier)){
          $arrayAventurier[$iAventurier]->niveau += $arrayAventurier[$iAventurier]->niveau;
          $gobelin->dead();
        } else {
          $arrayAventurier[$iAventurier]->alive = 'D';
        }
      }
      $iGobelin = $iGobelin + 1;
    }
    $iAventurier = $iAventurier + 1;
  }
  echo '<br />';
  echo '<br />';
  showMap($carte, $arrayMontagne, $arrayTresor, $arrayAventurier, $arrayOrc, $arrayGobelin);
}


//Write file
$myfile = fopen("exit.txt", "w");
$carte = "C - ".$carte->largeur." - ".$carte->hauteur;
fwrite($myfile, $carte);
foreach($arrayMontagne as &$montagne) {
  $txtMontagne = "M - ".$montagne->horizontal." - ".$montagne->vertical;
  fwrite($myfile, $txtMontagne);
}
$commentaire = "# {T comme Trésor} - {Axe horizontal} - {Axe vertical} - {Nb. de trésors restants}\n";
fwrite($myfile, $commentaire);
foreach($arrayTresor as &$tresor) {
  if($tresor->nbTresor !== 0){
    $txtTresor = "T - ".$tresor->horizontal." - ".$tresor->vertical." - ".$tresor->nbTresor;
    fwrite($myfile, $txtTresor);
  }
}
fwrite($myfile, "# {A comme Aventurier} - {Nom de l’aventurier} - {Axe horizontal} - {Axe vertical} - {Orientation} - {Level Obtenu}\n");
foreach($arrayAventurier as &$aventurier) {
  if($aventurier->alive == 'L'){
    $txtAventurier = "A - ".$aventurier->name." - ".$aventurier->horizontal." - ".$aventurier->vertical." - ".$aventurier->orientation." - ".$aventurier->niveau."\n";
    fwrite($myfile, $txtAventurier);
  }
}
fwrite($myfile, "# {G comme Gobelin} - {Axe horizontal} - {Axe vertical} - {Etat D -> dead L -> Live} {Nb combat effectué}\n");
foreach($arrayGobelin as &$gobelin) {
  $txtAventurier = "G - ".$gobelin->horizontal." - ".$gobelin->vertical." - ".$gobelin->alive." - ".$gobelin->nbFight."\n";
  fwrite($myfile, $txtAventurier);
}
fwrite($myfile, "# {O comme Orc} - {Axe horizontal} - {Axe vertical} - {Etat D -> dead L -> Live} {Nb combat effectué}\n");
foreach($arrayOrc as &$orc) {
  $txtAventurier = "O - ".$orc->horizontal." - ".$orc->vertical." - ".$orc->alive." - ".$orc->nbFight."\n";
  fwrite($myfile, $txtAventurier);
}
fclose($myfile);

?>
