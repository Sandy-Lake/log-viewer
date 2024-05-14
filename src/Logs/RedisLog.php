<?php

namespace Opcodes\LogViewer\Logs;

use Opcodes\LogViewer\LogLevels\RedisLogLevel;

class RedisLog extends Log
{
    public static string $name = 'Redis';
    public static string $regex = '/^(?<pid>\d+):(?<role_letter>\w) (?<datetime>.*) (?<level>[.\-*#]) (?<message>.*)/';
    public static string $levelClass = RedisLogLevel::class;
    public static array $columns = [
        ['label' => 'Datetime', 'data_path' => 'datetime'],
        ['label' => 'PID', 'data_path' => 'context.pid'],
        ['label' => 'Role', 'data_path' => 'context.role'],
        ['label' => 'Severity', 'data_path' => 'level'],
        ['label' => 'Message', 'data_path' => 'message'],
    ];

    protected function fillMatches(array $matches = []): void
    {
        parent::fillMatches($matches);

        $roleDescription = null;
        if (isset($matches['role_letter'])) {
            switch ($matches['role_letter']) {
                case 'X':
                    $roleDescription = 'sentinel';
                    break;
                case 'S':
                    $roleDescription = 'slave';
                    break;
                case 'M':
                    $roleDescription = 'master';
                    break;
                case 'C':
                    $roleDescription = 'RDB/AOF writing child';
                    break;
            }
        }

        $this->context = [
            'pid' => intval($matches['pid'] ?? null),
            'role' => $matches['role_letter'],
            'role_description' => $roleDescription,
        ];
    }
}
