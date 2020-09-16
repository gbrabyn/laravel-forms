<?php

namespace App\Entity;

use InvalidArgumentException;

class IpAddress
{
    private $ip;

    public function __construct(string $ipAddress)
    {
        if(filter_var($ipAddress, \FILTER_VALIDATE_IP) === false){
            throw new InvalidArgumentException('Invalid IP address used: '.$ipAddress);
        }

        $this->ip = $ipAddress;
    }

    public function get(): string
    {
        return $this->ip;
    }
}
