<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel;

use Weverson83\AddByLink\Api\Data\LinkProductInterface;

class LinkProduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_isPkAutoIncrement = false;

    /**
     * Resource initialization
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('add_by_link_product', LinkProductInterface::LINK_ID);
    }
}
