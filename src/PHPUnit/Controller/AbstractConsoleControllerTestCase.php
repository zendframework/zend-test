<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Zend\Test\PHPUnit\Controller;

use PHPUnit\Framework\ExpectationFailedException;

abstract class AbstractConsoleControllerTestCase extends AbstractControllerTestCase
{
    /**
     * HTTP controller must use the console request
     * @var bool
     */
    protected $useConsoleRequest = true;

    /**
     * Assert console output contain content (insensible case)
     *
     * @param  string $match content that should be contained in matched nodes
     * @return void
     */
    public function assertConsoleOutputContains($match)
    {
        $response = $this->getResponse();
        if (false === stripos($response->getContent(), $match)) {
            throw new ExpectationFailedException($this->createFailureMessage(
                sprintf(
                    'Failed asserting output CONTAINS content "%s", actual content is "%s"',
                    $match,
                    $response->getContent()
                )
            ));
        }
        $this->assertNotSame(false, stripos($response->getContent(), $match));
    }

    /**
     * Assert console output not contain content
     *
     * @param  string $match content that should be contained in matched nodes
     * @return void
     */
    public function assertNotConsoleOutputContains($match)
    {
        $response = $this->getResponse();
        if (false !== stripos($response->getContent(), $match)) {
            throw new ExpectationFailedException($this->createFailureMessage(sprintf(
                'Failed asserting output DOES NOT CONTAIN content "%s"',
                $match
            )));
        }
        $this->assertSame(false, stripos($response->getContent(), $match));
    }
}
