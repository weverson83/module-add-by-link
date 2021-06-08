<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel\Link;

use Magento\Catalog\Api\Data\ProductInterface;
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

    /**
     * Retrieve all filters not yet expired
     *
     * @return $this
     */
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

    /**
     * Adds filter by product (ID)
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return $this
     */
    public function addProductFilter(ProductInterface $product): Collection
    {
        $this->getSelect()
            ->joinInner(
                ['link_product' => $this->getTable('add_by_link_product')],
                $this->getConnection()->quoteInto(
                    'link_product.link_id = main_table.entity_id AND link_product.product_id = ?',
                    $product->getId()
                ),
                null
            );

        return $this;
    }
}
