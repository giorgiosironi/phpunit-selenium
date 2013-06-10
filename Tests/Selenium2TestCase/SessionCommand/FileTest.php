<?php

class Tests_Selenium2TestCase_SessionCommand_FileTest extends Tests_Selenium2TestCase_BaseTestCase
{

  /**
   * @test
   */
  public function uploadFile() {

    $this->url( 'php/file_upload.php' );

    $remote_file      = $this->file( 'html/banner.gif' );

    $upload_criteria  = $this->using( 'id' )
                             ->value( 'upload_here' );

    $submit_criteria  = $this->using( 'id' )
                             ->value( 'submit' );

    $msg_criteria     = $this->using( 'id' )
                             ->value( 'uploaded' );

    $this->element( $upload_criteria )
         ->value( $remote_file );

    $this->element( $submit_criteria )
         ->click();

    $msg_displayed    = $this->element( $msg_criteria )
                             ->displayed();

    $this->assertNotEmpty( $msg_displayed );

  } // uploadFile

} // Tests_Selenium2TestCase_SessionCommand_FileTest
