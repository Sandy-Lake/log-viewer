<?php

namespace Opcodes\LogViewer\LogLevels;

class SupervisorLogLevel implements LevelInterface
{
    const MAP = [
        'CRIT' => 'critical',
        'ERRO' => 'error',
        'WARN' => 'warning',
        'INFO' => 'info',
        'DEBG' => 'debug',
        'TRAC' => 'trace',
        'BLAT' => 'blather',
    ];

    public string $value;

    public function __construct(string $value)
    {
        if (isset(static::MAP[$value])) {
            $value = static::MAP[$value];
        }

        $this->value = $value;
    }

    public static function from(?string $value = null): LevelInterface
    {
        return new static($value);
    }

    public static function caseValues(): array
    {
        return array_values(static::MAP);
    }

    public function getName(): string
    {
        return ucfirst($this->value);
    }

    public function getClass(): LevelClass
    {
        $lowerValue = strtolower($this->value);

        if (in_array($lowerValue, ['info', 'debug', 'trace', 'blather'])) {
            return LevelClass::info();
        } elseif ($lowerValue === 'warning') {
            return LevelClass::warning();
        } elseif (in_array($lowerValue, ['critical', 'error'])) {
            return LevelClass::danger();
        } else {
            return LevelClass::none();
        }
    }
}
