<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;

interface LinkRepositoryInterface
{
    /**
     * List of links not expired
     *
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface[]|null
     */
    public function getActiveList(): ?array;

    /**
     * Retrieve all links by product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface[]|null
     */
    public function getActiveByProduct(ProductInterface $product): ?array;

    /**
     * Retrieve link by token string
     *
     * @param string $token
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface|null
     */
    public function getByToken(string $token): ?LinkInterface;

    /**
     * Retrieve one link by id
     *
     * @param int $entityId
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface|null
     */
    public function getById(int $entityId): ?LinkInterface;

    /**
     * Update link of the given product
     *
     * @param \Weverson83\AddByLink\Api\Data\LinkInterface $link
     * @return int
     */
    public function save(LinkInterface $link): int;

    /**
     * Delete link
     *
     * @param \Weverson83\AddByLink\Api\Data\LinkInterface $link
     * @return bool
     */
    public function delete(LinkInterface $link): bool;
}
