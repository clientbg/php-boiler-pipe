<?php

namespace Clientbg\PhpBoilerPipe\Filters\Simple;

use Clientbg\PhpBoilerPipe\Filters\IFilter;
use Clientbg\PhpBoilerPipe\TextDocument;

class BoilerplateBlockFilter implements IFilter
{
    protected $labelToKeep;

    public function __construct($labelToKeep = null)
    {
        $this->labelToKeep = $labelToKeep;
    }

    public function process(TextDocument $doc)
    {
        $changes = false;
        $textBlocks = $doc->getTextBlocks();
        foreach ($textBlocks as $tb) {
            if (!$tb->isContent() && ($this->labelToKeep == null || !$tb->hasLabel($this->labelToKeep))) {
                $doc->removeTextBlock($tb);
                $changes = true;
            }
        }
        return $changes;
    }
}