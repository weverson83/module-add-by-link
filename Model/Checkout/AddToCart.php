<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Checkout;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\ExpiredException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\Link\Validation;

class AddToCart
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
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
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * AddToCart constructor.
     * @param CheckoutSession $checkoutSession
     * @param Validation $linkValidation
     * @param LinkRepositoryInterface $linkRepository
     * @param ProductRepositoryInterface $productRepository
     * @param CartRepositoryInterface $cartRepository
     * @param MessageManager $messageManager
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Validation $linkValidation,
        LinkRepositoryInterface $linkRepository,
        ProductRepositoryInterface $productRepository,
        CartRepositoryInterface $cartRepository,
        MessageManager $messageManager
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->linkValidation = $linkValidation;
        $this->linkRepository = $linkRepository;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * @param string $token
     * @return bool
     * @throws InputMismatchException
     * @throws InputException
     * @throws NoSuchEntityException
     * @throws ExpiredException
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

        $addedProducts = 0;

        foreach ($link->getProductIds() as $productId) {
            $product = $this->productRepository->getById($productId);
            try {
                $result = $this->getQuote()->addProduct($product);
                if (!$result instanceof CartItemInterface) {
                    throw new LocalizedException(__($result));
                }
                ++$addedProducts;
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('%1 is not available right now', $product->getName())
                );
            }
        }

        if ($addedProducts > 0) {
            $this->cartRepository->save($this->getQuote());
            return true;
        }

        return false;
    }

    private function getQuote(): CartInterface
    {
        return $this->checkoutSession->getQuote();
    }
}
