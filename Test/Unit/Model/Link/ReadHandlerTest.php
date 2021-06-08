<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Unit\Model\Link;

use Magento\Bundle\Api\Data\LinkInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\Link\ReadHandler;

class ReadHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Weverson83\AddByLink\Model\Link\ReadHandler
     */
    protected $model;
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Weverson83\AddByLink\Api\LinkRepositoryInterface
     */
    protected $linkRepository;

    protected function setUp()
    {
        $this->linkRepository = $this->getMockBuilder(LinkRepositoryInterface::class)
            ->getMockForAbstractClass();
        $this->objectManager = new ObjectManager($this);
        $this->model = $this->objectManager->getObject(ReadHandler::class, [
            'linkRepository' => $this->linkRepository,
        ]);
    }

    public function testExecute()
    {
        $link1 = $this->getMockBuilder(LinkInterface::class)
            ->getMock();
        $link2 = $this->getMockBuilder(LinkInterface::class)
            ->getMock();

        $productExtensions = $this->getMockBuilder(\Magento\Catalog\Api\Data\ProductExtensionInterface::class)
            ->setMethods(['getAddByLinkList', 'setAddByLinkList'])
            ->getMockForAbstractClass();

        $productExtensions->expects($this->once())
            ->method('setAddByLinkList')
            ->with([$link1, $link2]);

        $product = $this->getMockBuilder(ProductInterface::class)
            ->setMethods(['setExtensionAttributes'])
            ->getMockForAbstractClass();

        $product->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($productExtensions);

        $this->linkRepository->expects($this->once())
            ->method('getByProduct')
            ->willReturn([$link1, $link2]);

        $this->linkRepository->expects($this->never())
            ->method('getActiveList');

        $this->linkRepository->expects($this->never())
            ->method('save');

        $this->linkRepository->expects($this->never())
            ->method('delete');

        $this->model->execute($product);
    }
}
