<?php

namespace Opcodes\LogViewer;

use Opcodes\LogViewer\Utils\Utils;

class Host
{
    public bool $is_remote;

    public ?string $identifier;
    public string $name;
    public ?string $host;
    public ?array $headers;
    public ?array $auth;

    public function __construct(
        ?string $identifier,
        string $name,
        ?string $host = null,
        ?array $headers = null,
        ?array $auth = null
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->host = $host;
        $this->headers = $headers;
        $this->auth = $auth;
        $this->is_remote = $this->isRemote();
    }

    public static function fromConfig($identifier, array $config = []): self
    {
        return new static(
            is_string($identifier) ? $identifier : Utils::shortMd5($config['host']),
            $config['name'] ?? (is_string($identifier) ? $identifier : $config['host']),
            $config['host'] ?? null,
            $config['headers'] ?? [],
            $config['auth'] ?? [],
        );
    }

    public function isRemote(): bool
    {
        return ! is_null($this->host);
    }
}
