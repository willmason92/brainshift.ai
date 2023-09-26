<?php

namespace Tests\Unit;

use App\Rules\HtmlContentRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class HtmlContentRuleTest extends TestCase
{
    public function testValidHtml()
    {
        $rule = new HtmlContentRule();

        $validator = Validator::make([
            'content' => '<p>This is a test</p>',
        ], [
            'content' => [$rule],
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testInvalidHtml()
    {
        $rule = new HtmlContentRule();

        $validator = Validator::make([
            'content' => '<script>alert("XSS attack");</script>',
        ], [
            'content' => [$rule],
        ]);

        $this->assertFalse($validator->passes());
    }
}
