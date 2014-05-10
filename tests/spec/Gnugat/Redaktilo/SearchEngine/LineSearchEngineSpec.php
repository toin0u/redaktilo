<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\SearchEngine;

use PhpSpec\ObjectBehavior;

class LineSearchEngineSpec extends ObjectBehavior
{
    function it_implements_search_engine()
    {
        $this->shouldImplement('Gnugat\Redaktilo\SearchEngine\SearchEngine');
    }
}