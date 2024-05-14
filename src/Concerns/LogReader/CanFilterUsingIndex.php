<?php

namespace Opcodes\LogViewer\Concerns\LogReader;

use Opcodes\LogViewer\Utils\Utils;

trait CanFilterUsingIndex
{
    protected ?string $query = null;
    protected ?int $onlyShowIndex = null;

    /**
     * Load only the provided log levels
     *
     * @alias setLevels
     *
     * @return CanFilterUsingIndex
     */
    public function only($levels = null): self
    {
        return $this->setLevels($levels);
    }

    public function setLevels($levels = null): self
    {
        $this->index()->forLevels($levels);

        return $this;
    }

    public function allLevels(): self
    {
        return $this->setLevels(null);
    }

    /**
     * Load all log levels except the provided ones.
     *
     * @alias exceptLevels
     */
    public function except($levels = null): self
    {
        return $this->exceptLevels($levels);
    }

    /**
     * Load all log levels except the provided ones.
     */
    public function exceptLevels($levels = null): self
    {
        $this->index()->exceptLevels($levels);

        return $this;
    }

    public function skip(int $number): self
    {
        $this->index()->skip($number);

        return $this;
    }

    public function limit(int $number): self
    {
        $this->index()->limit($number);

        return $this;
    }

    public function search(?string $query = null): self
    {
        return $this->setQuery($query);
    }

    protected function setQuery(?string $query = null): self
    {
        $this->closeFile();

        if (!empty($query) && strpos($query, 'log-index:') === 0) {
            $this->query = null;
            $this->only(null);
            $this->onlyShowIndex = intval(explode(':', $query)[1]);
        } elseif (!empty($query)) {
            $query = '~' . $query . '~iu';

            Utils::validateRegex($query);

            $this->query = $query;
        } else {
            $this->query = null;
        }

        return $this;
    }
}
