<?php

namespace Netresearch\ContextsGeolocation\Event;

use Netresearch\ContextsGeolocation\AbstractAdapter;

class AdapterInstanceEvent
{
    protected $adapter;
    protected $ip;

    public function __construct(?string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getAdapter(): ?AbstractAdapter
    {
        return $this->adapter;
    }

    public function setAdapter(AbstractAdapter $adapter): void
    {
        $this->adapter = $adapter;
    }
}
