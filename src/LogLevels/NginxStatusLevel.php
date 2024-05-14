<?php

namespace Opcodes\LogViewer\LogLevels;

class NginxStatusLevel implements LevelInterface
{
    const Debug = 'debug';
    const Info = 'info';
    const Notice = 'notice';
    const Warning = 'warn';
    const Error = 'error';
    const Critical = 'crit';
    const Alert = 'alert';
    const Emergency = 'emerg';

    public string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? self::Error;
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
        ];
    }

    public static function from(?string $value = null): self
    {
        return new self($value);
    }

    public function getName(): string
    {
        switch ($this->value) {
            case self::Warning:
                return 'Warning';
            case self::Critical:
                return 'Critical';
            case self::Emergency:
                return 'Emergency';
            default:
                return ucfirst($this->value);
        }
    }

    public function getClass(): LevelClass
    {
        if ($this->value === self::Debug || $this->value === self::Info || $this->value === self::Notice) {
            return LevelClass::info();
        } elseif ($this->value === self::Warning) {
            return LevelClass::warning();
        } elseif ($this->value === self::Error || $this->value === self::Critical || $this->value === self::Alert || $this->value === self::Emergency) {
            return LevelClass::danger();
        } else {
            return LevelClass::none();
        }
    }

    public static function caseValues(): array
    {
        return self::cases();
    }
}
