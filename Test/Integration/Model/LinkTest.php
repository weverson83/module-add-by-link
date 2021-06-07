<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Integration\Model;

use Magento\Framework\DataObject;
use Magento\TestFramework\Helper\Bootstrap;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Model\Link;

class LinkTest extends AbstractTest
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var Link
     */
    protected $model;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->model = $this->objectManager->get(LinkInterface::class);
    }

    protected function getModel(): DataObject
    {
        return $this->model;
    }

    public function testObjectManagerInstantiatesLinkModel()
    {
        $this->assertInstanceOf(
            Link::class,
            $this->objectManager->create(LinkInterface::class)
        );
    }

    /**
     * @return array[]
     */
    public function gettersAndSettersProvider(): array
    {
        return [
            [LinkInterface::ID, 1],
            [LinkInterface::TOKEN, 'TOKEN_VALUE'],
            [LinkInterface::TOKEN_CREATED_AT, (string) time()],
            [LinkInterface::EXPIRATION_PERIOD, 2],
        ];
    }
}
