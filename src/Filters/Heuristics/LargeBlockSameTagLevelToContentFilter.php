<?php

namespace Clientbg\PhpBoilerPipe\Filters\Heuristics;

use Clientbg\PhpBoilerPipe\Filters\IFilter;
use Clientbg\PhpBoilerPipe\TextDocument;
use Clientbg\PhpBoilerPipe\TextLabels;

class LargeBlockSameTagLevelToContentFilter implements IFilter
{
    public function process(TextDocument $doc)
    {
        $changes = false;

        $level = -1;
        foreach ($doc->getTextBlocks() as $tb) {
            if ($tb->isContent() && $tb->hasLabel(TextLabels::MIGHT_BE_CONTENT)) {
                $level = $tb->getLevel();
                break;
            }
        }

        if ($level == -1) return false;
        foreach ($doc->getTextBlocks() as $tb) {
            if (!$tb->isContent()) {
                if ($tb->getWordCount() >= 100 && $tb->getLevel() == $level) {
                    $tb->setIsContent(true);
                    $changes = true;
                }
            }
        }

        return $changes;
    }
}


