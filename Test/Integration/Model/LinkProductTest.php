<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Model;

use Magento\Framework\DataObject;
use Magento\TestFramework\Helper\Bootstrap;
use Weverson83\AddByLink\Api\Data\LinkProductInterface;
use Weverson83\AddByLink\Model\LinkProduct;

class LinkProductTest extends AbstractTest
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var LinkProduct
     */
    protected $model;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->model = $this->objectManager->get(LinkProductInterface::class);
    }

    protected function getModel(): DataObject
    {
        return $this->model;
    }

    public function testObjectManagerInstantiatesLinkProductModel()
    {
        $this->assertInstanceOf(
            LinkProduct::class,
            $this->objectManager->create(LinkProductInterface::class)
        );
    }

    /**
     * @return array[]
     */
    public function gettersAndSettersProvider(): array
    {
        return [
            [LinkProduct::PRODUCT_ID, 1],
            [LinkProduct::LINK_ID, 2],
        ];
    }
}
