<?php 

class Gobelin { 
  public $horizontal;
  public $vertical;
  public $niveau;
  public $nbDeplacement;
  public $alive;
  public $nbFIght;

  function __construct($horizontal, $vertical, $niveau, $nbDeplacement) {
    $this->horizontal = $horizontal;
    $this->vertical = $vertical;
    $this->niveau = $niveau;
    $this->nbDeplacement = $nbDeplacement;

    $this->minY = $horizontal ;
    $this->maxY = $horizontal + $nbDeplacement;
    $this->direction = 1;
    $this->alive = 'L';
    $this->nbFight = 0;
  }

  function dead(){
    $this->alive = 'D';
  }

  function move($size) {
    if ($this->horizontal > $this->maxY || $size < $this->horizontal + 1) {
      $this->direction = -1;
    } else if ($this->horizontal < $this->minY) {
      $this->direction = 1;
    } 
    if((0 > $this->horizontal - 1)) {
      $this->direction = 0;
    }
    $this->horizontal += $this->direction;
  }
}

?>