<?php

declare(strict_types=1);

namespace JsonStreamingParser;

use JsonStreamingParser\Listener\AsyncListenerInterface;

class AsyncParser extends Parser
{
    /**
     * AsyncParser constructor.
     * @param resource $stream
     * @param AsyncListenerInterface $listener
     * @param string $lineEnding
     * @param bool $emitWhitespace
     * @param int $bufferSize
     */
    public function __construct($stream, AsyncListenerInterface $listener, string $lineEnding = "\n", bool $emitWhitespace = false, int $bufferSize = 8192)
    {
        $listener->setAsyncCallback([$this, 'proceed']);
        parent::__construct($stream, $listener, $lineEnding, $emitWhitespace, $bufferSize);
    }

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
