<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel\Link;

use Magento\Framework\DB\Sql\Expression;
use Weverson83\AddByLink\Api\Data\LinkInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = LinkInterface::ID;

    /**
     * Initialization
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(
            \Weverson83\AddByLink\Model\Link::class,
            \Weverson83\AddByLink\Model\ResourceModel\Link::class
        );
    }

    public function addActiveFilter(): Collection
    {
        $nowExpr = new Expression('NOW()');

        $this->getSelect()
            ->where(
                LinkInterface::TOKEN_CREATED_AT . '<= ?',
                [
                    $nowExpr
                ]
            )->where(
                new Expression(
                    sprintf(
                        'DATE_ADD(%s, INTERVAL %s HOUR) > NOW()',
                        LinkInterface::TOKEN_CREATED_AT,
                        LinkInterface::EXPIRATION_PERIOD)
                )
            );

        return $this;
    }
}
