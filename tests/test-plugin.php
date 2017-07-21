<?php
namespace Recras;

class PluginTest extends \WP_UnitTestCase
{
    function __construct()
    {
        update_option('recras_currency', '€');
        update_option('recras_subdomain', 'demo');
        update_option('recras_decimal', '.');
    }

    function testTooLongSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('ThisSubdomainIsLongerThanAllowedButDoesNotContainAnyInvalidCharacters');
        $this->assertFalse($result, 'Too long subdomain should be invalid');
    }

    function testInvalidSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('foo@bar');
        $this->assertFalse($result, 'Subdomain with invalid characters should be invalid');
    }

    function testValidSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('demo');
        $this->assertEquals('demo', $result, 'Valid subdomain should be valid');
    }

}
