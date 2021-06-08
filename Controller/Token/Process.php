<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Token;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Process extends Action implements HttpGetActionInterface
{

    /**
     * @var \Weverson83\AddByLink\Model\Checkout\AddToCart
     */
    private $addToCart;

    public function __construct(
        Context $context,
        \Weverson83\AddByLink\Model\Checkout\AddToCart $addToCart
    ) {
        parent::__construct($context);
        $this->addToCart = $addToCart;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            if ($this->addToCart->execute($this->getRequest()->getParam('token'))) {
                return $this->_redirect('checkout/cart/index');
            }
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception);
        }

        $url = $this->_url->getUrl('*/*/*', ['_secure' => true]);
        return $resultRedirect->setUrl($this->_redirect->error($url));
    }
}
