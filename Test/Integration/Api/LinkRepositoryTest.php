<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\Link;

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
    }

    /**
     * @magentoAppArea adminhtml
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/product_with_link.php
     */
    public function testGetByProduct()
    {
        $product = $this->objectManager->create(ProductInterface::class);
        $product->setId(1);
        $links = $this->repository->getByProduct($product);

        $this->assertNotNull($links);
        $this->assertCount(1, $links);
    }
}
