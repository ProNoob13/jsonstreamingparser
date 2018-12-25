<?php

declare(strict_types=1);

namespace JsonStreamingParser;

use JsonStreamingParser\Listener\AsyncInterface;

class AsyncParser extends Parser implements AsyncInterface
{
    public function parse(): void
    {
        $this->resetParser();
        $this->proceed();
    }

    public function proceed(): void
    {
        if(!$this->endOfFile) {
            $this->readStream();
        }
    }

    protected function consumeChar(string $char): void
    {
        $lastState = $this->state;

        parent::consumeChar($char);

        if($this->state === $lastState) {
            // the state hasn't changed, so it's safe to assume we're still in a multi-character construct
            // continue reading until we've hit something else, which will have triggered a Listener event
            $this->proceed();
        }
    }
}
