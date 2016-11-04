<?php

namespace ReadmeGen\Vcs\Type;

class Git extends AbstractType
{
    /**
     * Parses the log.
     *
     * @return array
     */
    public function parse()
    {
        return array_filter(array_map('trim', explode(self::MSG_SEPARATOR, $this->getLog())));
    }

    public function getToDate()
    {
        $arguments = $this->getArguments();

        $to = (true === isset($arguments['to'])) ? $arguments['to'] : 'HEAD';

        $fullDate = $this->runCommand(sprintf('git log -1 -s --format=%%ci %s', $to));
        $date = explode(' ', $fullDate);

        return $date[0];
    }

    protected function getLog()
    {
        return $this->runCommand($this->getCommand());
    }

    /**
     * Returns the compiled VCS log command.
     *
     * @return string
     */
    public function getCommand()
    {
        $options = $this->getOptions();
        $arguments = $this->getArguments();

        $to = null;
        $from = $arguments['from'];

        if (true === isset($arguments['to'])) {
            $to = $arguments['to'];
        }

        array_walk($options, function (&$option) {
            $option = '--'.$option;
        });

        array_walk($arguments, function (&$value, $argument) {
            $value = '--'.$argument.'='.$value;
        });

        return trim(sprintf('%s %s %s', $this->getBaseCommand(), $this->getRange($from, $to), implode(' ', $options)));
    }

    /**
     * Returns the base VCS log command.
     *
     * @return string
     */
    protected function getBaseCommand()
    {
        return 'git log --pretty=format:"%s{{MSG_SEPARATOR}}%b"';
    }

    protected function getRange($from, $to = null)
    {
        $range = $from.'..';

        return $range.(($to) ?: 'HEAD');
    }
}
