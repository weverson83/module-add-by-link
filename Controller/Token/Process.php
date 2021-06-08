<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Token;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\NotFoundException;
use Weverson83\AddByLink\Model\Checkout\AddToCart;

class Process extends Action implements HttpGetActionInterface
{

    /**
     * @var AddToCart
     */
    private $addToCart;

    public function __construct(
        Context $context,
        AddToCart $addToCart
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
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            if ($this->addToCart->execute($this->getRequest()->getParam('token'))) {
                $this->messageManager->addSuccessMessage(__('You added the product(s) to your shopping cart.'));
                return $this->_redirect('checkout/cart/index');
            }
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception);
        }

        $url = $this->_url->getUrl('home', ['_secure' => true]);
        return $resultRedirect->setUrl($this->_redirect->error($url));
    }
}
