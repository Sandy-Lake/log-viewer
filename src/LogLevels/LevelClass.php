<?php

namespace Opcodes\LogViewer\LogLevels;

class LevelClass
{
    const SUCCESS = 'success';
    const NOTICE = 'notice';
    const INFO = 'info';
    const WARNING = 'warning';
    const DANGER = 'danger';
    const NONE = 'none';
    public string $value;

    public function __construct(
        string $value
    ) {
        $this->value = $value;
    }

    public static function from(?string $value = null): LevelClass
    {
        return new static($value);
    }

    public static function caseValues(): array
    {
        return [
            static::SUCCESS,
            static::NOTICE,
            static::INFO,
            static::WARNING,
            static::DANGER,
            static::NONE,
        ];
    }

    public static function success(): self
    {
        return new static(static::SUCCESS);
    }

    public static function notice(): self
    {
        return new static(static::NOTICE);
    }

    public static function info(): self
    {
        return new static(static::INFO);
    }

    public static function warning(): self
    {
        return new static(static::WARNING);
    }

    public static function danger(): self
    {
        return new static(static::DANGER);
    }

    public static function none(): self
    {
        return new static(static::NONE);
    }
}
