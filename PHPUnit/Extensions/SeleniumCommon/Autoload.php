<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'File/Iterator/Autoload.php';

spl_autoload_register(
    static function ($class) {
        static $classes = null;
        static $path    = null;

        if ($classes === null) {
            $classes = [
                'phpunit_extensions_selenium2testcase' => '/Extensions/Selenium2TestCase.php',
                'phpunit_extensions_selenium2testcase_command' => '/Extensions/Selenium2TestCase/Command.php',
                'phpunit_extensions_selenium2testcase_commandsholder' => '/Extensions/Selenium2TestCase/CommandsHolder.php',
                'phpunit_extensions_selenium2testcase_driver' => '/Extensions/Selenium2TestCase/Driver.php',
                'phpunit_extensions_selenium2testcase_element' => '/Extensions/Selenium2TestCase/Element.php',
                'phpunit_extensions_selenium2testcase_element_accessor' => '/Extensions/Selenium2TestCase/Element/Accessor.php',
                'phpunit_extensions_selenium2testcase_element_select' => '/Extensions/Selenium2TestCase/Element/Select.php',
                'phpunit_extensions_selenium2testcase_elementcommand_attribute' => '/Extensions/Selenium2TestCase/ElementCommand/Attribute.php',
                'phpunit_extensions_selenium2testcase_elementcommand_click' => '/Extensions/Selenium2TestCase/ElementCommand/Click.php',
                'phpunit_extensions_selenium2testcase_elementcommand_css' => '/Extensions/Selenium2TestCase/ElementCommand/Css.php',
                'phpunit_extensions_selenium2testcase_elementcommand_equals' => '/Extensions/Selenium2TestCase/ElementCommand/Equals.php',
                'phpunit_extensions_selenium2testcase_elementcommand_genericaccessor' => '/Extensions/Selenium2TestCase/ElementCommand/GenericAccessor.php',
                'phpunit_extensions_selenium2testcase_elementcommand_genericpost' => '/Extensions/Selenium2TestCase/ElementCommand/GenericPost.php',
                'phpunit_extensions_selenium2testcase_elementcommand_value' => '/Extensions/Selenium2TestCase/ElementCommand/Value.php',
                'phpunit_extensions_selenium2testcase_elementcriteria' => '/Extensions/Selenium2TestCase/ElementCriteria.php',
                'phpunit_extensions_selenium2testcase_exception' => '/Extensions/Selenium2TestCase/Exception.php',
                'phpunit_extensions_selenium2testcase_keys' => '/Extensions/Selenium2TestCase/Keys.php',
                'phpunit_extensions_selenium2testcase_keysholder' => '/Extensions/Selenium2TestCase/KeysHolder.php',
                'phpunit_extensions_selenium2testcase_noseleniumexception' => '/Extensions/Selenium2TestCase/NoSeleniumException.php',
                'phpunit_extensions_selenium2testcase_response' => '/Extensions/Selenium2TestCase/Response.php',
                'phpunit_extensions_selenium2testcase_screenshotlistener' => '/Extensions/Selenium2TestCase/ScreenshotListener.php',
                'phpunit_extensions_selenium2testcase_session' => '/Extensions/Selenium2TestCase/Session.php',
                'phpunit_extensions_selenium2testcase_session_cookie' => '/Extensions/Selenium2TestCase/Session/Cookie.php',
                'phpunit_extensions_selenium2testcase_session_cookie_builder' => '/Extensions/Selenium2TestCase/Session/Cookie/Builder.php',
                'phpunit_extensions_selenium2testcase_session_storage' => '/Extensions/Selenium2TestCase/Session/Storage.php',
                'phpunit_extensions_selenium2testcase_session_timeouts' => '/Extensions/Selenium2TestCase/Session/Timeouts.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_acceptalert' => '/Extensions/Selenium2TestCase/SessionCommand/AcceptAlert.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_active' => '/Extensions/Selenium2TestCase/SessionCommand/Active.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_alerttext' => '/Extensions/Selenium2TestCase/SessionCommand/AlertText.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_click' => '/Extensions/Selenium2TestCase/SessionCommand/Click.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_dismissalert' => '/Extensions/Selenium2TestCase/SessionCommand/DismissAlert.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_file' => '/Extensions/Selenium2TestCase/SessionCommand/File.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_frame' => '/Extensions/Selenium2TestCase/SessionCommand/Frame.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_genericaccessor' => '/Extensions/Selenium2TestCase/SessionCommand/GenericAccessor.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_genericattribute' => '/Extensions/Selenium2TestCase/SessionCommand/GenericAttribute.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_keys' => '/Extensions/Selenium2TestCase/SessionCommand/Keys.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_location' => '/Extensions/Selenium2TestCase/SessionCommand/Location.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_moveto' => '/Extensions/Selenium2TestCase/SessionCommand/MoveTo.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_orientation' => '/Extensions/Selenium2TestCase/SessionCommand/Orientation.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_url' => '/Extensions/Selenium2TestCase/SessionCommand/Url.php',
                'phpunit_extensions_selenium2testcase_sessioncommand_window' => '/Extensions/Selenium2TestCase/SessionCommand/Window.php',
                'phpunit_extensions_selenium2testcase_sessionstrategy' => '/Extensions/Selenium2TestCase/SessionStrategy.php',
                'phpunit_extensions_selenium2testcase_sessionstrategy_isolated' => '/Extensions/Selenium2TestCase/SessionStrategy/Isolated.php',
                'phpunit_extensions_selenium2testcase_sessionstrategy_shared' => '/Extensions/Selenium2TestCase/SessionStrategy/Shared.php',
                'phpunit_extensions_selenium2testcase_statecommand' => '/Extensions/Selenium2TestCase/StateCommand.php',
                'phpunit_extensions_selenium2testcase_url' => '/Extensions/Selenium2TestCase/URL.php',
                'phpunit_extensions_selenium2testcase_waituntil' => '/Extensions/Selenium2TestCase/WaitUntil.php',
                'phpunit_extensions_selenium2testcase_webdriverexception' => '/Extensions/Selenium2TestCase/WebDriverException.php',
                'phpunit_extensions_selenium2testcase_window' => '/Extensions/Selenium2TestCase/Window.php',
                'phpunit_extensions_seleniumbrowsersuite' => '/Extensions/SeleniumBrowserSuite.php',
                'phpunit_extensions_seleniumcommon_remotecoverage' => '/Extensions/SeleniumCommon/RemoteCoverage.php',
                'phpunit_extensions_seleniumcommon_exithandler' => '/Extensions/SeleniumCommon/ExitHandler.php',
                'phpunit_extensions_seleniumtestsuite' => '/Extensions/SeleniumTestSuite.php',
            ];

            $path = dirname(dirname(dirname(__FILE__)));
        }

        $cn = strtolower($class);

        if (isset($classes[$cn])) {
            require $path . $classes[$cn];
        }
    }
);
