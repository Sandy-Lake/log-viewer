<?php

namespace Opcodes\LogViewer\Readers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Opcodes\LogViewer\LogFile;
use Opcodes\LogViewer\Logs\Log;

interface LogReaderInterface
{
    public static function instance(LogFile $file): self;

    public static function clearInstance(LogFile $file): void;

    public static function clearInstances(): void;

    // Search/querying
    public function search(?string $query = null): self;

    public function skip(int $number): self;

    public function limit(int $number): self;

    // Direction
    public function reverse(): self;

    public function forward(): self;

    public function setDirection(?string $direction = null): self;

    public function getLevelCounts(): array;

    public function only($levels = null): self;

    public function setLevels($levels = null): self;

    public function allLevels(): self;

    public function except($levels = null): self;

    public function exceptLevels($levels = null): self;

    // Retrieving actual logs
    public function get(?int $limit = null): array;

    public function next(): ?Log;

    /** @return LengthAwarePaginator<Log> */
    public function paginate(int $perPage = 25, ?int $page = null);

    public function total(): int;

    // Functional
    public function reset(): self;

    // We should decouple scanning from the LogReader
    public function scan(?int $maxBytesToScan = null, bool $force = false): self;

    public function numberOfNewBytes(): int;

    public function requiresScan(): bool;

    public function percentScanned(): int;
}
