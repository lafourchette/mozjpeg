<?php

namespace LaFourchette\Mozjpeg;

/**
 * Class Jpegtran
 * Micro-lib to wrap jpegtran command
 *
 * @package LaFourchette\Mozjpeg
 */
class Jpegtran
{
    /**
     * @var string
     */
    private $jpegtran;

    /**
     * constructor
     *
     * @params string $jpegtran
     *
     * @throw \RuntimeException if jpegtran is not found nor executable
     */
    public function __construct($jpegtran)
    {
        if (!file_exists($jpegtran)) {
            throw new \RuntimeException(sprintf('%s is not an existing file.', $jpegtran));
        }

        if (!is_executable($jpegtran)) {
            throw new \RuntimeException(sprintf('%s is not executable.', $jpegtran));
        }

        $this->jpegtran = $jpegtran;
    }

    /**
     * compress image with mozjpeg
     *
     * @param \SplFileInfo $file
     * @param string $newImagePath where to save new image (eg: "/tmp/image-mozjpeg.jpg")
     *
     * @throws \InvalidArgumentException
     */
    public function run(\SplFileInfo $file, $newImagePath)
    {
        $this->checkArguments($file, $newImagePath);

        shell_exec(sprintf(
            '%s %s > %s',
            $this->jpegtran,
            $file->getRealPath(),
            $newImagePath
        ));
    }

    /**
     * check that given arguments are valid
     *
     * @param \SplFileInfo $file
     * @param $newImagePath
     *
     * @throws \InvalidArgumentException
     */
    private function checkArguments(\SplFileInfo $file, $newImagePath)
    {
        // file must exist
        if (!$file->isFile()) {
            throw new \InvalidArgumentException(sprintf('File "%s" cannot be found', $file->getPathname()));
        }

        // file must be a jpg
        if (!in_array($file->getExtension(), $this->getAcceptedExtension())) {
            throw new \InvalidArgumentException(sprintf('File "%s" exists but is not a jpg', $file->getFilename()));
        }

        // new image path must exist
        $imageDir = str_replace(strrchr($newImagePath, '/'), '', $newImagePath);

        if (!is_dir($imageDir) || !is_writable($imageDir)) {
            throw new \InvalidArgumentException(sprintf('New image path "%s" doesn\'t exist or is not writable', $imageDir));
        }
    }

    /**
     * get accepted extensions
     * @return array
     */
    private function getAcceptedExtension()
    {
        return array('jpg', 'jpeg');
    }
}
