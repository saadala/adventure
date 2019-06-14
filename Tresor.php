<?php 

class Tresor { 
  public $horizontal;
  public $vertical;
  public $nbTresor;

  function __construct($horizontal, $vertical, $nbTresor) {
    $this->horizontal = $horizontal;
    $this->vertical = $vertical;
    $this->nbTresor = $nbTresor;
  } 
}

?>