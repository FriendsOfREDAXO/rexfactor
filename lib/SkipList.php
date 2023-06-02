<?php

namespace rexfactor;

use Rector\Core\Contract\Rector\RectorInterface;

final class SkipList {
    /**
     * @var list<class-string<RectorInterface>>
     */
    private $skipList = [];

    /**
     * @param list<class-string<RectorInterface>> $skipList
     */
    public function __construct(array $skipList) {
        $this->skipList = $skipList;
    }

    /**
     * @param list<string> $skipList
     * @return void
     */
    static public function fromStrings(array $skipList): self {
        $skipped = [];
        foreach($skipList as $skipItem) {
            if (!is_subclass_of($skipItem, RectorInterface::class)) {
                throw new \InvalidArgumentException('Invalid skip list item');
            }

            $skipped[] = $skipItem;
        }

        return new self($skipped);
    }

    public function addSkipItem(string $skipItem): self {
        if (!is_subclass_of($skipItem, RectorInterface::class)) {
            throw new \InvalidArgumentException('Invalid skip list item');
        }

        $skipList = $this->skipList;
        $skipList[] = $skipItem;
        return new self($skipList);
    }

    public function removeSkipItem(string $skipItem): self {
        if (!is_subclass_of($skipItem, RectorInterface::class)) {
            throw new \InvalidArgumentException('Invalid skip list item');
        }

        $skipped = [];
        foreach($this->skipList as $skipListItem) {
            if ($skipListItem === $skipItem) {
                continue;
            }

            $skipped[] = $skipListItem;
        }

        return new self($skipped);
    }

    public function toUrl(): string {
        $url = [];

        foreach($this->skipList as $skipItem) {
            $url[] = 'skip[]=' . $skipItem;
        }

        return implode('&', $url);
    }

    public function toRectorSkipList(): array {
        $skipList = [];

        foreach($this->skipList as $skipItem) {
            $skipList[] = $skipItem. '::class';
        }

        return $skipList;
    }
}
