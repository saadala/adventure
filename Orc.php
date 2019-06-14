<?php 

class Orc { 
  public $horizontal;
  public $vertical;
  public $niveau;
  public $nbDeplacement;

  private $minY;
  private $maxY;
  private $direction1;


  function __construct($horizontal, $vertical, $niveau, $nbDeplacement) {
    $this->horizontal = $horizontal;
    $this->vertical = $vertical;
    $this->niveau = $niveau;
    $this->nbDeplacement = $nbDeplacement;
    
    $this->minY = $vertical;
    $this->maxY = $vertical + $nbDeplacement;
    $this->direction = 1;
    $this->alive = 'L';
    $this->nbFight = 0;
  }

  function dead(){
    $this->alive = 'D';
  }

  function move($size) {
    if ($this->vertical > $this->maxY || $size < $this->vertical + 1) {
      $this->direction = -1;
    } else if ($this->vertical < $this->minY) {
      $this->direction = 1;
    } 
    if((0 > $this->vertical - 1)) {
      $this->vertical = 0;
    }
    $this->vertical += $this->direction;
  }
}

?>