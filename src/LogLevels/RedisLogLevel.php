<?php

namespace Opcodes\LogViewer\LogLevels;

class RedisLogLevel implements LevelInterface
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
            '.' => 'debug',
            '-' => 'verbose',
            '*' => 'notice',
            '#' => 'warning',
        ];
    }

    public function getName(): string
    {
        switch ($this->value) {
            case '.':
                return 'Debug';
            case '-':
                return 'Verbose';
            case '*':
                return 'Notice';
            case '#':
                return 'Warning';
            default:
                return $this->value;
        }
    }

    public function getClass(): LevelClass
    {
        switch ($this->value) {
            case '.':
            case '-':
            case '*':
                return LevelClass::info();
            case '#':
                return LevelClass::warning();
            default:
                return LevelClass::none();
        }
    }
}
