<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel\LinkProduct;

use Magento\Framework\DB\Sql\Expression;
use Weverson83\AddByLink\Api\Data\LinkInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialization
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(
            \Weverson83\AddByLink\Model\LinkProduct::class,
            \Weverson83\AddByLink\Model\ResourceModel\LinkProduct::class
        );
    }
}
