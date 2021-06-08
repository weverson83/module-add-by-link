<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Framework\Controller\ResultInterface;

class NewAction extends \Weverson83\AddByLink\Controller\Adminhtml\Link
{
    /**
     * New action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
