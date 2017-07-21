<?php
namespace Recras;

class ProductsTest extends \WP_UnitTestCase
{
    function testShortcodeWithoutID()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product]'
        ]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
    }

    function testInvalidIDinShortcode()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
    }

    function testShortcodeWithValidIDWithoutShow()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=8]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: "show" option not set' . "\n", $content, 'Not setting "show" option should fail');
    }

    function testShortcodeWithInvalidShow()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=8 show=invalid]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: invalid "show" option' . "\n", $content, '...');
    }

    function testGetProducts()
    {
        $plugin = new Products;
        $products = $plugin::getProducts('demo');
        $this->assertGreaterThan(0, count($products), 'getProducts should return a non-empty array');
    }

    function testGetProductsInvalidDomain()
    {
        $plugin = new Products;
        $products = $plugin::getProducts('ObviouslyFakeSubdomainThatDoesNotExist');
        $this->assertTrue(is_string($products), 'getProducts on a non-existing subdomain should return an error message');
    }

    function testShortcodeShowTitle()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=8 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-title">ATB-clinic</span>' . "\n", $content, 'Should show title');
    }

    function testShortcodeShowPrices()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=8 show=price_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 16.53</span>' . "\n", $content, 'Should show price excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=8 show=price_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 20.00</span>' . "\n", $content, 'Should show price incl. vat');
    }

    function testShortcodeShowDescription()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=48 show=description]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-description">Bowlen op onze met led lampen verlichte bowlingbaan</span>' . "\n", $content, 'Should show description');
    }

    function testShortcodeShowLongDescription()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=1 show=description_long]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, 'span class="recras-description"><p>Op onze klimbaan met de langste zipine van Nederland kan iedereen zich helemaal uitleven.&nbsp;</p>'), 'Should show long description');
    }

    function testShortcodeShowEmptyLongDescription()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=48 show=description_long]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('' . "\n", $content, 'Should not show empty description');
    }

    function testShortcodeShowDuration()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=48 show=duration]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-duration">1:00</span>' . "\n", $content, 'Should show duration');
    }

    function testShortcodeShowEmptyDuration()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=73 show=duration]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('' . "\n", $content, 'Should not show duration');
    }

    function testShortcodeShowMinimumAmount()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-product id=80 show=minimum_amount]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-amount">1</span>' . "\n", $content, 'Should show minimum amount');
    }
}
