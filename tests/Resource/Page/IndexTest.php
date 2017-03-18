<?php
namespace BEAR\Skeleton\Resource\Page;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;

class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \BEAR\Resource\ResourceInterface
     */
    private $resource;

    protected function setUp()
    {
        parent::setUp();
        $this->resource = (new AppInjector('BEAR\Skeleton', 'app'))->getInstance(ResourceInterface::class);
    }

    public function testOnGet()
    {
        $page = $this->resource->get->uri('page://self/index')(['name' => 'BEAR.Sunday']);
        $this->assertSame(200, $page->code);
        $this->assertSame('Hello BEAR.Sunday', $page['greeting']);

        return $page;
    }

    /**
     * @depends testOnGet
     */
    public function testView(ResourceObject $page)
    {
        $json = json_decode((string) $page);
        $this->assertSame('Hello BEAR.Sunday', $json->greeting);
    }
}
