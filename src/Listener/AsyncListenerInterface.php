<?php

    namespace JsonStreamingParser\Listener;

    interface AsyncListenerInterface extends ListenerInterface
    {
        public function setAsyncCallback(callable $callback): void;
    }