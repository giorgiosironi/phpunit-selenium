<?php

namespace Tests\Selenium2TestCase;

class PageObjectTest extends BaseTestCase
{
    public function testAPageInteractsWithElementsExposingAnHigherLevelApi()
    {
        $this->url('html/test_type_page1.html');
        $page = new AuthenticationPage($this);
        $welcomePage = $page->username('TestUser')
                            ->password('TestPassword')
                            ->submit();
        $welcomePage->assertWelcomeIs('Welcome, TestUser!');
    }
}

class AuthenticationPage
{
    public function __construct($test)
    {
        $this->usernameInput = $test->byName('username');
        $this->passwordInput = $test->byName('password');
        $this->test = $test;
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
        $this->test->clickOnElement('submitButton');
        return new WelcomePage($this->test);
    }
}

class WelcomePage
{
    public function __construct($test)
    {
        $this->header = $test->byCssSelector('h2');
        $this->test = $test;
    }

    public function assertWelcomeIs($text)
    {
        $this->test->assertMatchesRegularExpression("/$text/", $this->header->text());
    }
}
