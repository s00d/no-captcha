<?php

namespace s00d\NoCaptcha\Tests;

use PHPUnit\Framework\TestCase;
use s00d\NoCaptcha\NoCaptcha;

class NoCaptchaTest extends TestCase
{
    private const CLIENT_API = 'https://www.recaptcha.net/recaptcha/api.js';
    private const VERIFY_URL = 'https://www.recaptcha.net/recaptcha/api/siteverify';

    private NoCaptcha $captcha;

    protected function setUp(): void
    {
        parent::setUp();

        $this->captcha = new NoCaptcha(
            '{secret-key}',
            '{site-key}',
            self::CLIENT_API,
            self::VERIFY_URL
        );
    }

    public function testVerifyEmptyResponse(): void
    {
        $this->assertFalse($this->captcha->verifyResponse(''));
        $this->assertFalse($this->captcha->verifyResponse(null));
    }

    public function testJsLink(): void
    {
        $simple = '<script src="'.self::CLIENT_API.'?" async defer></script>'."\n";
        $withLang = '<script src="'.self::CLIENT_API.'?hl=vi" async defer></script>'."\n";
        $withCallback = '<script src="'.self::CLIENT_API.'?render=explicit&onload=myOnloadCallback" async defer></script>'."\n";

        $this->assertEquals($simple, $this->captcha->renderJs());
        $this->assertEquals($withLang, $this->captcha->renderJs('vi'));
        $this->assertEquals($withCallback, $this->captcha->renderJs(null, true, 'myOnloadCallback'));
    }

    public function testDisplay(): void
    {
        $simple = '<div data-sitekey="{site-key}" class="g-recaptcha"></div>';
        $withAttrs = '<div data-theme="light" data-sitekey="{site-key}" class="g-recaptcha"></div>';

        $this->assertEquals($simple, $this->captcha->display());
        $this->assertEquals($withAttrs, $this->captcha->display(['data-theme' => 'light']));
    }

    public function testDisplaySubmit(): void
    {
        $javascript = '<script>function onSubmittest(){document.getElementById("test").submit();}</script>';
        $simple = '<button data-callback="onSubmittest" data-sitekey="{site-key}" class="g-recaptcha"><span>submit</span></button>';
        $withAttrs = '<button data-theme="light" class="g-recaptcha 123" data-callback="onSubmittest" data-sitekey="{site-key}"><span>submit123</span></button>';

        $this->assertEquals($simple.$javascript, $this->captcha->displaySubmit('test'));
        $withAttrsResult = $this->captcha->displaySubmit('test', 'submit123', ['data-theme' => 'light', 'class' => '123']);
        $this->assertEquals($withAttrs.$javascript, $withAttrsResult);
    }

    public function testDisplaySubmitWithCustomCallback(): void
    {
        $withAttrs = '<button data-theme="light" class="g-recaptcha 123" data-callback="onSubmitCustomCallback" data-sitekey="{site-key}"><span>submit123</span></button>';

        $withAttrsResult = $this->captcha->displaySubmit('test-custom', 'submit123', [
            'data-theme' => 'light',
            'class' => '123',
            'data-callback' => 'onSubmitCustomCallback',
        ]);
        $this->assertEquals($withAttrs, $withAttrsResult);
    }
}
