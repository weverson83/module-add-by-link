<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Weverson83\AddByLink\Api\Data\LinkProductInterface;

class LinkProduct extends \Magento\Framework\DataObject implements LinkProductInterface
{

    /**
     * Retrieve product's ID
     *
     * @return int
     */
    public function getProductId(): int
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set product ID
     *
     * @param int $productId
     * @return \Weverson83\AddByLink\Api\Data\LinkProductInterface
     */
    public function setProductId(int $productId): LinkProductInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve link ID
     *
     * @return int
     */
    public function getLinkId(): int
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * Set link ID
     *
     * @param int $linkId
     * @return \Weverson83\AddByLink\Api\Data\LinkProductInterface
     */
    public function setLinkId(int $linkId): LinkProductInterface
    {
        return $this->setData(self::LINK_ID, $linkId);
    }
}
