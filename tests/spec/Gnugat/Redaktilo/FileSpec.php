<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

class FileSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/%s/life-of-brian.txt';

    private $filename;
    private $content;

    function let()
    {
        $rootPath = __DIR__.'/../../../../';

        $sourceFilename = sprintf(self::FILENAME, $rootPath, 'sources');
        $copyFilename = sprintf(self::FILENAME, $rootPath, 'copies');

        $fileCopier = new FileCopier();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->filename = $copyFilename;
        $this->content = file_get_contents($copyFilename);

        $this->beConstructedWith($this->filename, $this->content, "\n");
    }

    function it_has_a_filename()
    {
        $this->getFilename()->shouldBe($this->filename);
    }

    function it_has_a_content()
    {
        $newContent = 'This is an EX parrot!';

        $this->read()->shouldBe($this->content);
        $this->write($newContent);
        $this->read()->shouldBe($newContent);
    }

    function it_has_lines()
    {
        $lines = explode("\n", $this->content);
        $newLines = array(
            'And now for something',
            'Completly different',
        );

        $this->readlines()->shouldBe($lines);
        $this->writelines($newLines);
        $this->readlines()->shouldBe($newLines);
    }

    function it_has_a_current_line()
    {
        $this->getCurrentLineNumber()->shouldBe(0);

        $lines = explode(PHP_EOL, $this->content);
        $middleLine = count($lines) / 2;

        $this->setCurrentLineNumber($middleLine);
        $this->getCurrentLineNumber()->shouldBe($middleLine);
    }

    function it_inserts_lines()
    {
        $rootPath = __DIR__.'/../../../../';
        $expectedFilename = sprintf(self::FILENAME, $rootPath, 'expectations');
        $expectedContent = file_get_contents($expectedFilename);

        $line = "Pontius Pilate: '...Dickus?'";
        $lineNumber = 6;

        $this->insertLineAt($line, $lineNumber);
        $this->read()->shouldBe($expectedContent);
    }

    function it_changes_lines()
    {
        $rootPath = __DIR__.'/../../../../';
        $expectedFilename = sprintf('%s/tests/fixtures/%s/life-of-brian-1.txt', $rootPath, 'expectations');
        $expectedContent = file_get_contents($expectedFilename);

        $line = "[Even more sniggering]";
        $lineNumber = 5;

        $this->changeLineTo($line, $lineNumber);
        $this->read()->shouldBe($expectedContent);
    }

    function it_removes_lines()
    {
        $rootPath = __DIR__.'/../../../../';
        $expectedFilename = sprintf('%s/tests/fixtures/%s/life-of-brian-2.txt', $rootPath, 'expectations');
        $expectedContent = file_get_contents($expectedFilename);

        $lineNumber = 1;

        $this->removeLine($lineNumber);
        $this->read()->shouldBe($expectedContent);
    }

    function it_checks_line_presence()
    {
        $existingLine = '[Sniggering]';
        $nonExistingLine = "Isn't there a Saint Aaaaarrrrrrggghhh's in Cornwall?";

        $this->hasLine($existingLine)->shouldBe(true);
        $this->hasLine($nonExistingLine)->shouldBe(false);
    }
}
