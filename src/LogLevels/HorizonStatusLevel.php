<?php

namespace Opcodes\LogViewer\LogLevels;

class HorizonStatusLevel implements LevelInterface
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
            'Processing',
            'Running',
            'Processed',
            'Done',
            'Failed',
            'Fail',
        ];
    }

    public function getName(): string
    {
        return ucfirst(strtolower($this->value));
    }

    public function getClass(): LevelClass
    {
        $lowerValue = strtolower($this->value);

        return $lowerValue === 'processing' || $lowerValue === 'running'
            ? LevelClass::info()
            : ($lowerValue === 'processed' || $lowerValue === 'done'
                ? LevelClass::success()
                : ($lowerValue === 'failed' || $lowerValue === 'fail'
                    ? LevelClass::danger()
                    : LevelClass::none()));
    }
}
