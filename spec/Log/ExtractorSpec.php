<?php

namespace spec\ReadmeGen\Log;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtractorSpec extends ObjectBehavior
{

    function it_extracts_messages_from_log()
    {
        $log = [
            'foo',
            '[+] bar baz',
            'nope',
            '[+] dummy feature',
            'add lol',
            'also nope',
            'fix some bugfix',
            '[*] refactoring',
        ];

        $messageGroups = [
            'Features'        => ['add', '[+]'],
            'Bugfixes'        => ['[!]', 'fix'],
            'Refactoring'     => ['[*]'],
        ];

        $result = [
            'Features' => [
                'bar baz',
                'dummy feature',
                'lol',
            ],
            'Bugfixes' => [
                'some bugfix',
            ],
            'Refactoring' => [
                'refactoring',
            ],
        ];

        $this->setLog($log);
        $this->setMessageGroups($messageGroups);

        $this->extract()->shouldReturn($result);
    }

}
