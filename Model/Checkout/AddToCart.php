<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Checkout;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\Link\Validation;

class AddToCart
{
    /**
     * @var CartInterface|Quote
     */
    private $cart;
    /**
     * @var Validation
     */
    private $linkValidation;
    /**
     * @var LinkRepositoryInterface
     */
    private $linkRepository;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * AddToCart constructor.
     * @param CartInterface $cart
     * @param Validation $linkValidation
     * @param LinkRepositoryInterface $linkRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CartInterface $cart,
        Validation $linkValidation,
        LinkRepositoryInterface $linkRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->cart = $cart;
        $this->linkValidation = $linkValidation;
        $this->linkRepository = $linkRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $token
     * @return bool
     * @throws InputMismatchException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\ExpiredException
     */
    public function execute(string $token): bool
    {
        $link = $this->linkRepository->getByToken($token);
        if ($link === null) {
            throw new InputMismatchException(__('The token is invalid. Correct it and try again.'));
        }

        if (!$this->linkValidation->execute($link)) {
            return false;
        }

        foreach ($link->getProductIds() as $productId) {
            $product = $this->productRepository->getById($productId);
            $this->cart->addProduct($product);
        }

        $this->cart->save();

        return true;
    }
}
