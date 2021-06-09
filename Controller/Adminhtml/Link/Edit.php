<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\LinkFactory;

class Edit extends \Weverson83\AddByLink\Controller\Adminhtml\Link
{
    /**
     * @var LinkFactory
     */
    private $linkFactory;

    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        LinkRepositoryInterface $linkRepository,
        LinkFactory $linkFactory
    ) {
        parent::__construct($context, $dataPersistor, $resultForwardFactory, $resultPageFactory, $linkRepository);
        $this->linkFactory = $linkFactory;
    }

    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = (int)$this->getRequest()->getParam('id');
        $link = $this->linkRepository->getById($entityId);

        if ($entityId && $link === null) {
            $this->messageManager->addErrorMessage(__('This Link no longer exists.'));
            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        if ($link === null) {
            $link = $this->linkFactory->create();
        }

        $this->dataPersistor->set('add_by_link_link', $link);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $link->getId() ? __('Edit Link') : __('New Link'),
            $link->getId() ? __('Edit Link') : __('New Link')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Links'));
        $resultPage->getConfig()
            ->getTitle()
            ->prepend($link->getId() ? __('Edit Link', $link->getId()) : __('New Link'));

        return $resultPage;
    }
}
