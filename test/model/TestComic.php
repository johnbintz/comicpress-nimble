<?php

require_once('PHPUnit/Framework.php');
require_once(dirname(__FILE__) . '/../../model/Comic.php');
require_once(dirname(__FILE__) . '/../../lib/ComicException.php');

class TestComic extends PHPUnit_Framework_TestCase {
    /**
     * @dataProvider directoryListProvider
     */
    public function testProcessDirectoryList($entries, $results) {
        $this->assertEquals($results, Comic::process_directory($entries));
    }
    
    public function directoryListProvider() {
        return array(
            array(array(), array()),
            array(
                array("2006-01-01.txt"),
                array(new Comic(array('date' => '2006-01-01',
                                      'title' => '2006-01-01',
                                      'copy' => "",
                                      'id' => strtotime('2006-01-01'))))
            )
        ); 
    }
    
    /**
     * @dataProvider badDirectoryInfoProvider
     */
    public function testPassBadDirectoryInfo($bad) {
        $caught = false;
        try {
          Comic::process_directory($bad);
        } catch (ComicException $e) { $caught = true; } 
        $this->assertTrue($caught);
    }
    
    public function badDirectoryInfoProvider() {
        return array(
            array(null),
            array(false)
        ); 
    }
    
    /**
     * @dataProvider badConstructorInfoProvider
     */    
    public function testBadInstantiateComic($bad) {
         $caught = false;
         try {
             $a = new Comic($bad);
         } catch (ComicException $e) { $caught = true; }
         $this->assertTrue($caught);
    }
    
    public function badConstructorInfoProvider() {
        return array(
            array(null),
            array(false),
            array("test"),
            array(1)
        ); 
    }
    
    /**
     * @dataProvider badIsValidIDs
     */
    public function testIsBadValid($id) {
        $a = new Comic();
        $a->id = $id;
        $this->assertFalse($a->is_valid());
    }
    
    public function badIsValidIDs() {
        return array(
            array(null),
            array(""),
            array(array())
        );
    }
    
    /**
     * @dataProvider goodIsValidIDs
     */
    public function testIsValid($id) {
        $a = new Comic();
        $a->id = $id;
        $this->assertTrue($a->is_valid());
    }
    
    public function goodIsValidIDs() {
        return array(
            array("a"),
            array("1")
        ); 
    }
}

?>