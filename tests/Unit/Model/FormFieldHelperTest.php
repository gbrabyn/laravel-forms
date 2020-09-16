<?php

namespace Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use App\Model\FormFieldHelper;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Contracts\Support\MessageBag;

/** @covers \App\Model\FormFieldHelper */
class FormFieldHelperTest extends TestCase
{
    /** @dataProvider getErrorNameProvider */
    public function testGetErrorName($fieldName, $expected)
    {
        $errors = $this->createStub(ViewErrorBag::class);
        $helper = new FormFieldHelper($fieldName, $errors, []);

        $this->assertEquals($expected, $helper->getErrorName());
    }

    public function getErrorNameProvider(): array
    {
        return [
            ['aaa', 'aaa'],
            ['aaa.bbb[ccc]', 'aaa_bbb.ccc'],
            ['ty_7-f[]', 'ty_7-f'],
        ];
    }

    /**
     * @dataProvider attributesWithoutErrorProvider
     */
    public function testGetAttributesWithoutError($attributes, $expected)
    {
        $errors = $this->createStub(ViewErrorBag::class);
        $helper = new FormFieldHelper('aaaa', $errors, $attributes);

        $this->assertSame($expected, $helper->getAttributes());
    }

    public function attributesWithoutErrorProvider(): array
    {
        return [
            [array(), array()],
            [
                ['class' => 'form-field small', 'id' => 'email'],
                ['class' => 'form-field small', 'id' => 'email']
            ]
        ];
    }

    /**
     * @dataProvider attributesWithErrorProvider
     */
    public function testGetAttributesWithError($attributes, $expected)
    {
        $key = 'aaaa';
        $errors = new ViewErrorBag();
        $messageBag = $this->createStub(MessageBag::class);
        $messageBag->method('has')
            ->will($this->returnValueMap([
                [$key, true],
            ]));
        $errors->put('default', $messageBag);

        $helper = new FormFieldHelper($key, $errors, $attributes);

        $this->assertSame($expected, $helper->getAttributes());
    }

    public function attributesWithErrorProvider(): array
    {
        return [
            [array(), array('class' => 'error')],
            [
                ['class' => 'form-field small', 'id' => 'email'],
                ['class' => 'form-field small error', 'id' => 'email']
            ]
        ];
    }

    /**
     * @dataProvider getAttributesStringWithoutErrorProvider
     */
    public function testGetAttributesStringWithoutError($attributes, $expected)
    {
        $errors = $this->createStub(ViewErrorBag::class);
        $helper = new FormFieldHelper('aaaa', $errors, $attributes);

        $this->assertEquals($expected, $helper->getAttributesString());
    }

    public function getAttributesStringWithoutErrorProvider(): array
    {
        return [
            [array(), ''],
            [
                ['class' => 'form-field small', 'id' => 'email'],
                'class="form-field small" id="email"'
            ]
        ];
    }

    /**
     * @dataProvider getAttributesStringWithErrorProvider
     */
    public function testGetAttributesStringWithError($attributes, $expected)
    {
        $key = 'aaaa';
        $errors = new ViewErrorBag();
        $messageBag = $this->createStub(MessageBag::class);
        $messageBag->method('has')
            ->will($this->returnValueMap([
                [$key, true],
            ]));
        $errors->put('default', $messageBag);

        $helper = new FormFieldHelper($key, $errors, $attributes);

        $this->assertSame($expected, $helper->getAttributesString());
    }

    public function getAttributesStringWithErrorProvider(): array
    {
        return [
            [array(), 'class="error"'],
            [
                ['class' => 'form-field small', 'id' => 'email'],
                'class="form-field small error" id="email"'
            ],
            [
                ['id' => 'email', 'readonly'],
                'id="email" readonly class="error"'
            ]
        ];
    }

    /**
     * @dataProvider removeArrayEndingFromFieldNameProvider
     */
    public function testRemoveArrayEndingFromFieldName($fieldName, $expected)
    {
        $this->assertEquals($expected, FormFieldHelper::removeArrayEndingFromFieldName($fieldName));
    }

    public function removeArrayEndingFromFieldNameProvider()
    {
        return [
            ['aaa', 'aaa'],
            ['aaa[bbb]', 'aaa[bbb]'],
            ['aaa[]', 'aaa'],
            ['aa.a[bbb][]', 'aa.a[bbb]'],
        ];
    }
}
