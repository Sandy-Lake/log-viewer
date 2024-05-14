<?php

namespace Opcodes\LogViewer\Concerns\LogReader;

use Opcodes\LogViewer\Direction;

trait CanSetDirectionUsingIndex
{
    public function reverse(): self
    {
        return $this->setDirection(Direction::Backward);
    }

    public function forward(): self
    {
        return $this->setDirection(Direction::Forward);
    }

    public function setDirection(?string $direction = null): self
    {
        $direction = $direction === Direction::Backward
            ? Direction::Backward
            : Direction::Forward;

        $this->index()->setDirection($direction);

        return $this->reset();
    }
}
