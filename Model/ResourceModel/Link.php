<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel;

use Weverson83\AddByLink\Api\Data\LinkInterface;

class Link extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('add_by_link', LinkInterface::ID);
    }
}
