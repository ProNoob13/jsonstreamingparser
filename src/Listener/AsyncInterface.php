<?php

    namespace JsonStreamingParser\Listener;

    interface AsyncInterface
    {
        public function proceed(): void;
    }