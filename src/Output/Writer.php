<?php
namespace ReadmeGen\Output;

use ReadmeGen\Output\Format\FormatInterface;

/**
 * Output writer.
 * Class Writer
 * @package ReadmeGen\Output
 */
class Writer
{
    /**
     * Format specific writer.
     * @var FormatInterface
     */
    protected $formatter;
    /**
     * Output breakpoint.
     * @var string
     */
    protected $break;

    public function __construct(FormatInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Writes the output to a file.
     * @return boolean
     */
    public function write()
    {
        $result   = false;
        $messages = $this->formatter->generate();
        if (count($messages)) {
            $log    = implode("\n", $messages)
                      . "\n";
            $result = $this->appendLog($log, $this->formatter->getFileName());
        }

        return $result;
    }

    /**
     * @param string $log
     * @param string $fileName
     *
     * @return boolean
     */
    public function appendLog($log, $fileName)
    {
        $originalContent = $this->getFileContent($fileName);
        if (!is_null($this->break)) {
            $log              = $this->break . "\n" . $log;
            $splitFileContent = preg_split("/^{$this->break}/m", $originalContent, 2);
            if (count($splitFileContent) === 2) {
                $newContent = implode($log, $splitFileContent);
            } else {
                $newContent = $log . $originalContent;
            }
        } else {
            $newContent = $log . $originalContent;
        }

        return (boolean)file_put_contents($fileName, $newContent);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getFileContent($fileName)
    {
        $fileContent = '';
        if (file_exists($fileName)) {
            $fileContent = file_get_contents($fileName);
        }

        return $fileContent;
    }

    /**
     * Breakpoint setter.
     *
     * @param null|string $break
     *
     * @return $this
     */
    public function setBreak($break = null)
    {
        if (false === empty($break)) {
            $this->break = $break;
        }

        return $this;
    }
}