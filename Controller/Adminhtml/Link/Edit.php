<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Edit extends \Weverson83\AddByLink\Controller\Adminhtml\Link
{
    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Weverson83\AddByLink\Model\Link::class);

        if ($entityId) {
            $model->load($entityId);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Link no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->dataPersistor->set('add_by_link_link', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $entityId ? __('Edit Link') : __('New Link'),
            $entityId ? __('Edit Link') : __('New Link')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Links'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Link %1', $model->getId()) : __('New Link'));
        return $resultPage;
    }
}
