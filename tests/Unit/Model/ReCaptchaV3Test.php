<?php

namespace Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use App\Model\ReCaptchaV3;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use App\Entity\IpAddress;
use Psr\Log\LoggerInterface;

/** @covers \App\Model\ReCaptchaV3 */
class ReCaptchaV3Test extends TestCase
{
    public function testSuccess()
    {
        $apiResponse = json_encode((object)['success' => true]);
        $response = new Response(200, [], $apiResponse);
        $secret = 'password';
        $formInput = 'abcde';
        $remoteIp = '142.250.66.238';

        $map = [
            [
                'post',
                ReCaptchaV3::API_URL,
                [
                    'form_params' => [
                        'secret' => $secret,
                        'response' => $formInput,
                        'remoteip' => $remoteIp,
                    ]
                ],
                $response
            ]
        ];

        $client = $this->createStub(ClientInterface::class);
        $client->method('request')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())
            ->method($this->anything());

        $captcha = new ReCaptchaV3($client, $secret, $logger, new IpAddress($remoteIp));
        $captcha->setValue($formInput);

        $this->assertTrue($captcha->isValid());
    }

    public function testFailure()
    {
        $apiResponse = json_encode((object)['success' => false]);
        $response = new Response(200, [], $apiResponse);
        $secret = 'password';
        $formInput = 'abcde';

        $map = [
            [
                'post',
                ReCaptchaV3::API_URL,
                [
                    'form_params' => [
                        'secret' => $secret,
                        'response' => $formInput,
                    ]
                ],
                $response
            ]
        ];

        $client = $this->createStub(ClientInterface::class);
        $client->method('request')
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())
            ->method($this->anything());

        $captcha = new ReCaptchaV3($client, $secret, $logger);
        $captcha->setValue($formInput);

        $this->assertFalse($captcha->isValid());
    }

    public function testErrorCodes()
    {
        $apiResponse = json_encode((object)['success' => false, 'error-codes' => ['500', 'abc']]);
        $response = new Response(200, [], $apiResponse);

        $client = $this->createStub(ClientInterface::class);
        $client->method('request')
            ->willReturn($response);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('alert');

        $captcha = new ReCaptchaV3($client, 'password', $logger);
        $captcha->setValue('abcde');
        $captcha->isValid();
    }
}
