<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration;

use Magento\Catalog\Api\Data\ProductInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;

class LinkRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var LinkRepositoryInterface
     */
    protected $repository;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->repository = $this->objectManager->create(LinkRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/links.php
     */
    public function testGetActiveList()
    {
        $links = $this->repository->getActiveList();
        $this->assertNotNull($links);
        $this->assertCount(1, $links);
        $this->assertResultsInstances($links);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/product_with_link.php
     */
    public function testGetByProduct()
    {
        $product = $this->objectManager->create(ProductInterface::class);
        $product->setId(1);
        $links = $this->repository->getByProduct($product);

        $this->assertNotNull($links);
        $this->assertCount(1, $links);
        $this->assertResultsInstances($links);
    }

    /**
     * @param array $links
     */
    protected function assertResultsInstances(array $links): void
    {
        if (!count($links)) {
            $this->fail('Link list is empty');
        }

        foreach ($links as $link) {
            $this->assertInstanceOf(LinkInterface::class, $link);
        }
    }
}
