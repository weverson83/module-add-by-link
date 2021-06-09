<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Test\Unit\Model\Checkout;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
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
    protected $productRepoMock;

    /**
     * @var CartRepositoryInterface|MockObject
     */
    protected $cartRepositoryMock;
    /**
     * @var SessionManagerInterface|MockObject
     */
    protected $checkoutSessMock;
    /**
     * @var CartInterface|MockObject
     */
    protected $quoteMock;
    /**
     * @var AddToCart
     */
    protected $model;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->linkValidationMock = $this->getMockBuilder(Validation::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $this->linkRepositoryMock = $this->getMockBuilder(LinkRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->productRepoMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->cartRepositoryMock = $this->getMockBuilder(CartRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->checkoutSessMock = $this->getMockBuilder(\Magento\Checkout\Model\Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMockForAbstractClass();

        $this->quoteMock = $this->getMockBuilder(CartInterface::class)
            ->setMethods(['addProduct'])
            ->getMockForAbstractClass();

        $this->checkoutSessMock->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->model = $this->objectManager->getObject(AddToCart::class, [
            'checkoutSession' => $this->checkoutSessMock,
            'linkValidation' => $this->linkValidationMock,
            'linkRepository' => $this->linkRepositoryMock,
            'productRepository' => $this->productRepoMock,
            'cartRepository' => $this->cartRepositoryMock,
        ]);
    }

    public function testExecuteAddProductSuccess()
    {
        $link = $this->getMockBuilder(LinkInterface::class)
            ->getMockForAbstractClass();

        $token = 'RANDOMSTRING';
        $link->method('getToken')
            ->willReturn($token);

        $this->linkRepositoryMock->expects($this->once())
            ->method('getByToken')
            ->with($link->getToken())
            ->willReturn($link);

        $productMock = $this->getMockBuilder(ProductInterface::class)
            ->getMockForAbstractClass();
        $productMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(1);

        $this->productRepoMock->expects($this->once())
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


        $this->quoteMock->expects($this->once())
            ->method('addProduct')
            ->with($productMock)
            ->willReturn($item);

        $this->cartRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock);

        $this->model->execute($token);
    }

    public function testExecuteWithNonExistingToken()
    {
        $this->expectException(\Magento\Framework\Exception\State\InputMismatchException::class);
        $this->expectExceptionMessage('The token is invalid. Correct it and try again.');
        $this->model->execute('INVALID_TOKEN');
    }
}
