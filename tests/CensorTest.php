<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload


use Snipe\BanBuilder\CensorWords;

class CensorTest extends PHPUnit_Framework_TestCase {
 
  public function testSetDictionary()
  {
    $censor = new CensorWords;
    $censor->setDictionary('fr');
    $this->assertNotEmpty($censor->badwords);
  }

  public function testAddDictionary()
  {
    $censor = new CensorWords();
    $censor->addDictionary('fr');

    $this->assertNotEmpty($censor->badwords);

    $string1 = $censor->censorString('fuck');
    $this->assertEquals('****', $string1['clean']);

    $string2 = $censor->censorString('nique');
    $this->assertEquals('*****', $string2['clean']);
  }

  public function testAddWords()
  {
    $censor = new CensorWords();
    $censor->addDictionary('fr');

    $this->assertNotEmpty($censor->badwords);

    $string1 = $censor->censorString('testing');
    $this->assertEquals('testing', $string1['clean']);

    $words = ['testing', 'additional'];
    $censor->addWords($words);

    $string2 = $censor->censorString('testing');
    $this->assertEquals('*******', $string2['clean']);
  }
  
  /**
  * @expectedException PHPUnit_Framework_Error
  */
  public function testInvalidDictionaryException()
  {
	$censor = new CensorWords;
    $this->assertNotEmpty($censor->setDictionary('poopfaced-blahblah-this-file-isnt-real'));	  
  }
  
  public function testFuckeryClean()
  {
    $censor = new CensorWords;
    $string = $censor->censorString('fuck');
    $this->assertEquals('****', $string['clean']);
    
  }
  
  public function testWordFuckeryClean()
  {
    $censor = new CensorWords;
    $string = $censor->censorString('abc fuck xyz', true);
    $this->assertEquals('abc **** xyz', $string['clean']);

    $string2 = $censor->censorString('Hello World', true);
    $this->assertEquals('Hello World', $string2['clean']);

    $string3 = $censor->censorString('fuck...', true);
    $this->assertEquals('****...', $string3['clean']);
  }
  
  public function testFuckeryOrig()
  {
    $censor = new CensorWords;
    $badwords = $censor->setDictionary('en-us');
    $string = $censor->censorString('fuck');
    $this->assertEquals('fuck', $string['orig']);
    
  }
  
  public function testFuckeryCustomReplace()
  {
    $censor = new CensorWords;
    $censor->setReplaceChar("X");
    $string = $censor->censorString('fuck');
    $this->assertEquals('XXXX', $string['clean']);
 
  }
  
  public function testFuckeryCustomReplaceException()
  {
    $censor = new CensorWords;
    $censor->setReplaceChar("x");
    $string = $censor->censorString('fuck');
    $this->assertNotEquals('****', $string['clean']);
 
  }
  
  
  public function testSameCensorObj()
  {
    $censor = new CensorWords;
    $string = $censor->censorString('fuck');
    $this->assertEquals('****', $string['clean']);
    $string2 = $censor->censorString('fuck');
    $this->assertEquals('****', $string2['clean']);
 
  }
    
 
}
