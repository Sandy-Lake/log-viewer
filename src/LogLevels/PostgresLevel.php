<?php

namespace Opcodes\LogViewer\LogLevels;

class PostgresLevel implements LevelInterface
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(?string $value = null): LevelInterface
    {
        return new static($value);
    }

    public static function caseValues(): array
    {
        return [
            'DEBUG',
            'INFO',
            'STATEMENT',
            'NOTICE',
            'WARNING',
            'ERROR',
            'LOG',
            'FATAL',
            'PANIC',
        ];
    }

    public function getName(): string
    {
        return ucfirst(strtolower($this->value));
    }

    public function getClass(): LevelClass
    {
        $lowerValue = strtolower($this->value);

        if (in_array($lowerValue, ['log', 'notice', 'statement', 'info', 'context', 'detail', 'hint', 'query',
            'debug', 'debug1', 'debug2', 'debug3', 'debug4', 'debug5'])) {
            return LevelClass::info();
        } elseif ($lowerValue === 'warning') {
            return LevelClass::warning();
        } elseif (in_array($lowerValue, ['error', 'panic', 'fatal'])) {
            return LevelClass::danger();
        } else {
            return LevelClass::none();
        }
    }
}
