<?php 

class Aventurier {
  public $name;
  public $horizontal;
  public $vertical;
  public $orientation;
  public $sqMouvement;
  public $niveau;

  function __construct($name, $horizontal, $vertical, $orientation, $sqMouvement) {
    $this->name = $name;
    $this->horizontal = $horizontal;
    $this->vertical = $vertical;
    $this->orientation = $orientation;
    $this->sqMouvement = $sqMouvement;
    $this->niveau = 0;
    $this->alive = 'L';
  }
}
?>