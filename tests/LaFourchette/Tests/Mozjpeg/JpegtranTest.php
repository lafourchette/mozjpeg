<?php

namespace LaFourchette\Mozjpeg {
    /**
     * Override file_exists() in current namespace for testing
     *
     * @param string $filename
     *
     * @return bool
     */
    function file_exists ($filename)
    {
        $res = true;

        if ($filename === '/foo/bar/test-file-exists') {
            $res = false;
        }

        return $res;
    }

    /**
     * Override file_exists() in current namespace for testing
     *
     * @param string $filename
     *
     * @return bool
     */
    function is_executable ($filename)
    {
        $res = true;

        if ($filename === '/foo/bar/test-is-executable') {
            $res = false;
        }

        return $res;
    }

    /**
     * Override is_dir() in current namespace for testing
     *
     * @param string $filename
     *
     * @return bool
     */
    function is_dir ($filename)
    {
        $res = true;

        if ($filename === '/foo/baz') {
            $res = false;
        }

        return $res;
    }
}

namespace LaFourchette\Tests\Mozjpeg {

    use LaFourchette\Mozjpeg\Jpegtran;
    use LaFourchette\Tests\Mozjpeg;

    /**
     * Class JpegtranTest
     *
     * @package LaFourchette\Tests\Mozjpeg
     */
    class JpegtranTest extends \PHPUnit_Framework_TestCase
    {
        public static $file;

        public function testMozjpegNotInstalled()
        {
            $this->setExpectedException('\RuntimeException', '/foo/bar/test-file-exists is not an existing file.');

            new Jpegtran('/foo/bar/test-file-exists');
        }

        public function testMozjpegNotExcecutable()
        {
            $this->setExpectedException('\RuntimeException', '/foo/bar/test-is-executable is not executable.');

            new Jpegtran('/foo/bar/test-is-executable');
        }

        public function testRunFileNotFound()
        {
            $this->setExpectedException(
                '\InvalidArgumentException',
                'File "/foo/bar/image.jpg" cannot be found'
            );

            $fileMock = $this->prophesize('\SplFileInfo');

            $fileMock->getPathname()->willReturn('/foo/bar/image.jpg');
            $fileMock->isFile()->willReturn(false);

            $jpegtran = new Jpegtran('/foo/bar/jpegtran');
            $jpegtran->run($fileMock->reveal(), '');
        }

        public function testRunWrongExtension()
        {
            $this->setExpectedException(
                '\InvalidArgumentException',
                'File "image.png" exists but is not a jpg'
            );

            $fileMock = $this->prophesize('\SplFileInfo');

            $fileMock->getExtension()->willReturn('png');
            $fileMock->getFilename()->willReturn('image.png');
            $fileMock->isFile()->willReturn(true);

            $jpegtran = new Jpegtran('/foo/bar/jpegtran');
            $jpegtran->run($fileMock->reveal(), '');
        }

        public function testRunWrongNewPath()
        {
            $this->setExpectedException(
                '\InvalidArgumentException',
                'New image path "/foo/baz" doesn\'t exist or is not writable'
            );

            $fileMock = $this->prophesize('\SplFileInfo');
            $fileMock->getExtension()->willReturn('jpg');
            $fileMock->getFilename()->willReturn('image.jpg');
            $fileMock->isFile()->willReturn(true);

            $jpegtran = new Jpegtran('/foo/bar/jpegtran');
            $jpegtran->run($fileMock->reveal(), '/foo/baz/image.jpg');
        }
    }
}
