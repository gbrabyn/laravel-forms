<?php
namespace App\Model;

/**
 * Validates Googles reCAPTCHA v3 - to verify user is human
 *
 * @author G Brabyn
 */
class ReCaptchaV3
{
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var string
     */
    private $secret;

    private $options = [
        'sendUsersIpAddress' => false,
    ];

    private $apiUrl = 'https://www.google.com/recaptcha/api/siteverify';

    
    public function __construct(string $secret, array $options=[])
    {
        $this->secret = $secret;
        $this->setOptions($options);
    }

    private function setOptions(array $options)
    {
        foreach($options as $k => $v){
            if(\array_key_exists($k, $this->options)){
                $this->options[$k] = (bool)$v;
            }
        }
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function isValid() : bool
    {
        $response = $this->getApiResponse($this->value);

        if(!empty($response->{'error-codes'})){
            \error_log('reCaptcha API error: '.print_r($response->{'error-codes'}, true));
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
        $data = ['secret'=>$this->secret, 'response'=>$responseFromForm];

        if($this->options['sendUsersIpAddress'] === true){
            $data['remoteip'] = $this->getUserIpAddr();
        }

        $query = \http_build_query($data);

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".\strlen($query)
        );

        $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => \implode("\r\n", $header),
                    'content' => $query,
                ]
        ];

        $context  = \stream_context_create($options);
        $verify = \file_get_contents($this->apiUrl, false, $context);

        return \json_decode($verify);
    }

    private function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}
