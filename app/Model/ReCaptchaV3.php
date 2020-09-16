<?php

namespace App\Model;

use GuzzleHttp\ClientInterface;
use App\Entity\IpAddress;
use Psr\Log\LoggerInterface;

/**
 * Validates Googles reCAPTCHA v3 - to verify user is human
 *
 * @author G Brabyn
 */
class ReCaptchaV3
{
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /** @var mixed */
    protected $value;

    /** @var string */
    private $secret;

    /** @var ClientInterface */
    private $httpClient;

    /** @var LoggerInterface */
    private $logger;

    /** @var IpAddress */
    private $usersIp;


    public function __construct(
        ClientInterface $httpClient,
        string $secret,
        LoggerInterface $logger,
        ?IpAddress $usersIp = null
    ) {
        $this->httpClient = $httpClient;
        $this->secret = $secret;
        $this->logger = $logger;
        $this->usersIp = $usersIp;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function isValid(): bool
    {
        $response = $this->getApiResponse($this->value);

        if (!empty($response->{'error-codes'})) {
            $this->logger->alert('reCaptcha API error: ' . print_r($response->{'error-codes'}, true));
        }

        return ($response->success == true);
    }

    /**
     *
     * @param string $responseFromForm
     * @return stdObject
     */
    private function getApiResponse(string $responseFromForm)
    {
        $data = ['secret' => $this->secret, 'response' => $responseFromForm];

        if ($this->usersIp !== null) {
            $data['remoteip'] = $this->usersIp->get();
        }

        $response = $this->httpClient->request('post', self::API_URL, ['form_params' => $data]);

        return \json_decode($response->getBody());
    }
}
