<?php

namespace Clientbg\PhpBoilerPipe\Filters;

use Clientbg\PhpBoilerPipe\TextDocument;

interface IFilter
{
    public function process(TextDocument $doc);
}
