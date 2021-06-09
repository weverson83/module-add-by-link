<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Framework\Controller\ResultInterface;

class Delete extends \Weverson83\AddByLink\Controller\Adminhtml\Link
{
    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = (int)$this->getRequest()->getParam('id');
        $link = $this->linkRepository->getById($entityId);
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($link) {
            try {
                $this->linkRepository->delete($link);
                $this->messageManager->addSuccessMessage(__('You deleted the Link.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Link to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
