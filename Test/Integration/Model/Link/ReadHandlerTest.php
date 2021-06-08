<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Model\Link;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Model\Link\ReadHandler;

class ReadHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var ReadHandler
     */
    protected $model;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->model = $this->objectManager->get(ReadHandler::class);
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/product_links.php
     */
    public function testExecuteWithThreeLinksAndOneExpiredShouldReturnTwo()
    {
        /** @var ProductInterface $product */
        $product = $this->objectManager->create(ProductInterface::class);
        $product->setId(1);

        $this->model->execute($product);

        $extensionAttributes = $product->getExtensionAttributes();
        $this->assertCount(2, $extensionAttributes->getAddByLinkList());
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture ../../../../app/code/Weverson83/AddByLink/Test/Integration/_fixtures/product_links.php
     */
    public function testExtensionAttributeIsLoadedAlongWithProduct()
    {
        /** @var ProductRepositoryInterface $productRepository */
        $productRepository = $this->objectManager->get(ProductRepositoryInterface::class);
        /** @var ProductInterface $product */
        $product = $productRepository->getById(1, true, null, true);

        $extensionAttributes = $product->getExtensionAttributes();

        $this->assertNotNull($extensionAttributes->getAddByLinkList());
        $this->assertCount(2, $extensionAttributes->getAddByLinkList());
        foreach ($extensionAttributes->getAddByLinkList() as $link) {
            $this->assertInstanceOf(LinkInterface::class, $link);
        }
    }
}
