<?php
  use PHPUnit\Framework\TestCase;

  include_once __DIR__.'/../../src/PHP/ajout_evenement.php';
  include_once __DIR__.'/../../src/PHP/bibli_generale.php';

  class ajout_evenementTest extends TestCase {
    public function test_deux_dates_premiere_anterieure(){
      $res = compare_deux_dates(2, 2, 2010, 4, 2, 2010);
      $this->assertEquals(0, $res);
    }

    public function test_deux_dates_deuxieme_anterieure(){
      $res = compare_deux_dates(4, 2, 2010, 2, 2, 2010);
      $this->assertEquals(1, $res);
    }

    public function test_deux_dates_meme(){
      $res = compare_deux_dates(4, 4, 2015, 4, 4, 2015);
      $this->assertEquals(2, $res);
    }

    public function test_heures(){
      $res = compare_deux_heures(4, 50, 4, 50);
      $this->assertEquals(2, $res);
    }

    public function test_heures_premiere_future(){
      $res = compare_deux_heures(4, 50, 4, 45);
      $this->assertEquals(0, $res);
    }

    public function test_heures_premiere_future2(){
      $res = compare_deux_heures(6, 50, 4, 45);
      $this->assertEquals(0, $res);
    }

    public function test_heures_premiere_future3(){
      $res = compare_deux_heures(16, 50, 4, 45);
      $this->assertEquals(0, $res);
    }

    public function test_heure_premiere_anterieure(){
      $res = compare_deux_heures(4, 50, 15, 25);
      $this->assertEquals(1, $res);
    }

  }

 ?>
