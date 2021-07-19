<?php

namespace common\components\Parser;

interface ParserInterface
{
    public function parse(): void;

    public function curlSuccess($instance): void;
    public function curlError($instance): void;
    public function curlComplete($instance): void;
}