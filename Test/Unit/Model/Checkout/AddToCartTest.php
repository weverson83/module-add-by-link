<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Unit\Model\Checkout;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\MockObject\MockObject;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\Checkout\AddToCart;
use Weverson83\AddByLink\Model\Link\Validation;

class AddToCartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    /**
     * @var CartInterface|MockObject|Quote
     */
    protected $cartMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockBuilder
     */
    protected $linkValidationMock;
    /**
     * @var MockObject|LinkRepositoryInterface
     */
    protected $linkRepositoryMock;
    /**
     * @var ProductRepositoryInterface|MockObject
     */
    protected $productRepositoryMock;
    /**
     * @var AddToCart
     */
    protected $model;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->cartMock = $this->getMockBuilder(CartInterface::class)
            ->setMethods(['addProduct', 'save'])
            ->getMockForAbstractClass();

        $this->linkValidationMock = $this->getMockBuilder(Validation::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $this->linkRepositoryMock = $this->getMockBuilder(LinkRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->productRepositoryMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->model = $this->objectManager->getObject(AddToCart::class, [
            'cart' => $this->cartMock,
            'linkValidation' => $this->linkValidationMock,
            'linkRepository' => $this->linkRepositoryMock,
            'productRepository' => $this->productRepositoryMock,
        ]);
    }

    public function testExecuteAddProductSuccess()
    {
        $link = $this->getMockBuilder(LinkInterface::class)
            ->getMockForAbstractClass();

        $link->method('getToken')
            ->willReturn('RANDOMSTRING');

        $this->linkRepositoryMock->expects($this->once())
            ->method('getByToken')
            ->with($link->getToken())
            ->willReturn($link);

        $productMock = $this->getMockBuilder(ProductInterface::class)
            ->getMockForAbstractClass();
        $productMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(1);

        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->willReturn($productMock);

        $link->method('getProductIds')
            ->willReturn([$productMock->getId()]);

        $item = $this->getMockBuilder(CartItemInterface::class)
            ->getMockForAbstractClass();

        $this->linkValidationMock->expects($this->once())
            ->method('execute')
            ->with($link)
            ->willReturn(true);

        $this->cartMock->expects($this->once())
            ->method('addProduct')
            ->with($productMock)
            ->willReturn($item);

        $this->cartMock->expects($this->once())
            ->method('save');

        $this->model->execute('RANDOMSTRING');
    }
}
