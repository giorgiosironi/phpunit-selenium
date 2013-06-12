<?php

class Tests_Selenium2TestCase_SessionCommand_FileTest extends Tests_Selenium2TestCase_BaseTestCase
{

  /**
   * @test
   */
  public function testUploadFile() {

    $this->url( 'php/file_upload.php' );

    $remote_file = $this->file( 'selenium-1-tests/html/banner.gif' );

    $this->byName( 'upload_here' )
         ->value( $remote_file );

    $this->byId( 'submit' )
         ->click();

    $msg_displayed    = $this->byId( 'uploaded' )
                             ->displayed();

    $this->assertNotEmpty( $msg_displayed );

  } // testUploadFile

} // Tests_Selenium2TestCase_SessionCommand_FileTest
