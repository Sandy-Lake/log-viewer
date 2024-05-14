<?php

namespace Opcodes\LogViewer\Logs;

use Opcodes\LogViewer\LogTypeRegistrar;

class LogType
{
    const DEFAULT = 'log';
    const LARAVEL = 'laravel';
    const HTTP_ACCESS = 'http_access';
    const HTTP_ERROR_APACHE = 'http_error_apache';
    const HTTP_ERROR_NGINX = 'http_error_nginx';
    const HORIZON_OLD = 'horizon_old';
    const HORIZON = 'horizon';
    const PHP_FPM = 'php_fpm';
    const POSTGRES = 'postgres';
    const REDIS = 'redis';
    const SUPERVISOR = 'supervisor';

    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function name(): string
    {
        $class = $this->logClass();

        if ($this->value === self::LARAVEL) {
            return 'Laravel';
        } elseif ($this->value === self::HTTP_ACCESS) {
            return 'HTTP Access';
        } elseif ($this->value === self::HTTP_ERROR_APACHE) {
            return 'HTTP Error (Apache)';
        } elseif ($this->value === self::HTTP_ERROR_NGINX) {
            return 'HTTP Error (Nginx)';
        } elseif ($this->value === self::HORIZON_OLD) {
            return 'Horizon (Old)';
        } elseif ($this->value === self::HORIZON) {
            return 'Horizon';
        } elseif ($this->value === self::PHP_FPM) {
            return 'PHP-FPM';
        } elseif ($this->value === self::POSTGRES) {
            return 'Postgres';
        } elseif ($this->value === self::REDIS) {
            return 'Redis';
        } elseif ($this->value === self::SUPERVISOR) {
            return 'Supervisor';
        } else {
            return isset($class) ? ($class::$name ?? 'Unknown') : 'Unknown';
        }
    }

    /**
     * @return string|Log|null
     */
    public function logClass(): ?string
    {
        return app(LogTypeRegistrar::class)->getClass($this->value);
    }

    public function isUnknown(): bool
    {
        return $this->value === static::DEFAULT;
    }
}
