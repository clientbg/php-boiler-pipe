<?php

namespace Clientbg\PhpBoilerPipe;

use Clientbg\PhpBoilerPipe\Filters\Heuristics\DocumentTitleMatchClassifier;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\TrailingHeadlineToBoilerplateFilter;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\BlockProximityFusion;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\KeepLargestBlockFilter;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\ExpandTitleToContentFilter;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\LargeBlockSameTagLevelToContentFilter;
use Clientbg\PhpBoilerPipe\Filters\Heuristics\ListAtEndFilter;

use Clientbg\PhpBoilerPipe\Filters\Simple\BoilerplateBlockFilter;

use Clientbg\PhpBoilerPipe\Filters\English\TerminatingBlocksFinder;
use Clientbg\PhpBoilerPipe\Filters\English\NumWordsRulesClassifier;
use Clientbg\PhpBoilerPipe\Filters\English\IgnoreBlocksAfterContentFilter;

class ArticleExtractor
{
    protected function process(TextDocument $doc)
    {
        return (new TerminatingBlocksFinder())->process($doc)
        | (new DocumentTitleMatchClassifier)->process($doc)
        | (new NumWordsRulesClassifier)->process($doc)
        | (new IgnoreBlocksAfterContentFilter(60))->process($doc)
        | (new TrailingHeadlineToBoilerplateFilter)->process($doc)
        | (new BlockProximityFusion(1))->process($doc)
        | (new BoilerplateBlockFilter(TextLabels::TITLE))->process($doc)
        | (new BlockProximityFusion(1, true, true))->process($doc)
        | (new KeepLargestBlockFilter(true, 150))->process($doc)
        | (new ExpandTitleToContentFilter)->process($doc)
        | (new LargeBlockSameTagLevelToContentFilter)->process($doc)
        | (new ListAtEndFilter)->process($doc);
    }

    public function getContent($html)
    {
        $content = new HtmlContent($html);
        $document = $content->getTextDocument();

        $this->process($document);

        return $document->getContent();
    }
}