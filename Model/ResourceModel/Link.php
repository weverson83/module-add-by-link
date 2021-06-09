<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\ResourceModel;

use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\Data\LinkProductInterface;

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

    /**
     * @param LinkInterface $link
     * @param array $productIds
     */
    public function updateProductRelations(LinkInterface $link, array $productIds): void
    {
        $connection = $this->getConnection();

        //Delete removed relations
        $connection->delete($this->getTable('add_by_link_product'), sprintf(
            '%s = %s AND %s NOT IN (%s)',
            LinkProductInterface::LINK_ID,
            $connection->quote($link->getId()),
            LinkProductInterface::PRODUCT_ID,
            $connection->quote($productIds)
        ));

        $links = [];

        foreach ($productIds as $productId) {
            $links[] = [
                LinkProductInterface::LINK_ID => $link->getId(),
                LinkProductInterface::PRODUCT_ID => $productId,
            ];
        }

        $connection->insertOnDuplicate(
            $this->getTable('add_by_link_product'),
            $links,
            [LinkProductInterface::LINK_ID, LinkProductInterface::PRODUCT_ID]
        );
    }
}
