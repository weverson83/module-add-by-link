<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Api\Data;

interface LinkProductInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const PRODUCT_ID = 'product_id';
    const LINK_ID = 'link_id';
    /**#@-*/

    /**
     * Retrieve product's ID
     *
     * @return int
     */
    public function getProductId(): int;

    /**
     * Set product ID
     *
     * @param int $productId
     * @return \Weverson83\AddByLink\Api\Data\LinkProductInterface
     */
    public function setProductId(int $productId): LinkProductInterface;

    /**
     * Retrieve link ID
     *
     * @return int
     */
    public function getLinkId(): int;

    /**
     * Set link ID
     *
     * @param int $linkId
     * @return \Weverson83\AddByLink\Api\Data\LinkProductInterface
     */
    public function setLinkId(int $linkId): LinkProductInterface;
}
