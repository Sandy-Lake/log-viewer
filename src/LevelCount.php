<?php

namespace Opcodes\LogViewer;

use Opcodes\LogViewer\LogLevels\LevelInterface;

class LevelCount
{
    public LevelInterface $level;
    public int $count;
    public bool $selected;

    public function __construct(
        LevelInterface $level,
        int $count = 0,
        bool $selected = false
    ) {
        $this->level = $level;
        $this->count = $count;
        $this->selected = $selected;
    }
}
