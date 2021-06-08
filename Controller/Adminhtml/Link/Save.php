<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Weverson83\AddByLink\Controller\Adminhtml\Link;

class Save extends Link
{
    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $entityId = $this->getRequest()->getParam('id');

            $model = $this->_objectManager->create(\Weverson83\AddByLink\Model\Link::class)->load($entityId);
            if (!$model->getId() && $entityId) {
                $this->messageManager->addErrorMessage(__('This Link no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Link.'));
                $this->dataPersistor->clear('add_by_link_link');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Link.'));
            }

            $this->dataPersistor->set('add_by_link_link', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
