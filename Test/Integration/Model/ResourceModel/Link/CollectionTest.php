<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Model\ResourceModel\Link;

use Magento\TestFramework\Helper\Bootstrap;
use Weverson83\AddByLink\Model\ResourceModel\Link\Collection;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        parent::setUp();
    }

    /**
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/links.php
     */
    public function testGetAllItems()
    {
        /** @var Collection $collection */
        $collection = $this->objectManager->create(Collection::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(3, $collection->getSize());
    }
}
