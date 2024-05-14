<?php

namespace Opcodes\LogViewer\LogLevels;

class LaravelLogLevel implements LevelInterface
{
    const Debug = 'DEBUG';
    const Info = 'INFO';
    const Notice = 'NOTICE';
    const Warning = 'WARNING';
    const Error = 'ERROR';
    const Critical = 'CRITICAL';
    const Alert = 'ALERT';
    const Emergency = 'EMERGENCY';
    const None = '';

    public string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? self::None;
    }

    public static function cases(): array
    {
        return [
            self::Debug,
            self::Info,
            self::Notice,
            self::Warning,
            self::Error,
            self::Critical,
            self::Alert,
            self::Emergency,
            self::None,
        ];
    }

    public static function from(?string $value = null): self
    {
        return new self($value);
    }

    public function getName(): string
    {
        switch ($this->value) {
            case self::None:
                return 'None';
            default:
                return ucfirst(strtolower($this->value));
        }
    }

    public function getClass(): LevelClass
    {
        switch ($this->value) {
            case self::Debug:
            case self::Info:
                return LevelClass::info();
            case self::Notice:
                return LevelClass::notice();
            case self::Warning:
                return LevelClass::warning();
            case self::Error:
            case self::Critical:
            case self::Alert:
            case self::Emergency:
                return LevelClass::danger();
            default:
                return LevelClass::none();
        }
    }

    public static function caseValues(): array
    {
        return self::cases();
    }
}
