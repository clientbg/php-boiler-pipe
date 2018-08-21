<?php

namespace Clientbg\PhpBoilerPipe\Filters\Heuristics;

use Clientbg\PhpBoilerPipe\Filters\IFilter;
use Clientbg\PhpBoilerPipe\TextDocument;
use Clientbg\PhpBoilerPipe\TextLabels;
use Clientbg\PhpBoilerPipe\TextBlock;

class TrailingHeadlineToBoilerplateFilter implements IFilter
{
    public function process(TextDocument $doc)
    {
        $change = false;

        /**
         * @var TextBlock[] $textBlocks
         */
        $textBlocks = $doc->getTextBlocks();
        $textBlocks = array_reverse($textBlocks);

        foreach ($textBlocks as $tb) {
            if ($tb->isContent()) {
                if ($tb->hasLabel(TextLabels::HEADING)) {
                    $tb->setIsContent(false);
                    $change = true;
                } else {
                    break;
                }
            }
        }
        return $change;
    }
}
