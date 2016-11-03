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
            'feature: bar baz',
            'nope',
            'feature: dummy feature',
            'feat: lol',
            'also nope',
            'fix: some bugfix',
        ];

        $messageGroups = [
            'Features' => ['feature', 'feat'],
            'Bugfixes' => ['bugfix', 'fix'],
            'Docs'     => ['docs'],
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
        ];

        $this->setLog($log);
        $this->setMessageGroups($messageGroups);

        $this->extract()->shouldReturn($result);
    }

}
