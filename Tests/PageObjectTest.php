<?php
class Tests_PageObjectTest extends PHPUnit_Extensions_Selenium2TestCase
{
    public function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    public function testAPageInteractsWithElementsExposingAnHigherLevelApi()
    {
        $this->url('html/test_type_page1.html');
        $page = new Tests_AuthenticationPage($this);
        $page->username('TestUser')
             ->password('TestPassword')
             ->submit();
        $welcomePage = new Tests_WelcomePage($this);
        $welcomePage->assertWelcomeIs('Welcome, TestUser!');

    }
}

class Tests_AuthenticationPage
{
    public function __construct($session)
    {
        $this->usernameInput = $session->byName('username');
        $this->passwordInput = $session->byName('password');
        $this->session = $session;
    }

    public function username($value)
    {
        $this->usernameInput->value($value);
        return $this;
    }

    public function password($value)
    {
        $this->passwordInput->value($value);
        return $this;
    }

    public function submit()
    {
        $this->session->clickOnElement('submitButton');
    }
}

class Tests_WelcomePage
{
    public function __construct($test)
    {
        $this->header = $test->byCssSelector('h2');
        $this->test = $test;
    }
    
    public function assertWelcomeIs($text)
    {
        $this->test->assertRegExp("/$text/", $this->header->text());
    }
}
