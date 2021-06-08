<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\ResourceModel\Link as LinkResource;
use Weverson83\AddByLink\Model\ResourceModel\Link\CollectionFactory;

class LinkRepository implements LinkRepositoryInterface
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var LinkResource
     */
    private $resourceModel;

    public function __construct(
        CollectionFactory $collectionFactory,
        LinkResource $resourceModel
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * List of links not expired
     *
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface[]|null
     */
    public function getActiveList(): ?array
    {
        $collection = $this->collectionFactory->create()
            ->addActiveFilter();

        $links = [];
        if ($collection->getSize()) {
            foreach ($collection as $item) {
                $links[] = $item;
            }

            return $links;
        }

        return null;
    }

    /**
     * Retrieve all links by product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface[]|null
     */
    public function getActiveByProduct(ProductInterface $product): ?array
    {
        $collection = $this->collectionFactory->create()
            ->addActiveFilter()
            ->addProductFilter($product);

        if ($collection->getSize()) {
            $items = [];
            foreach ($collection as $item) {
                $items[] = $item;
            }

            return $items;
        }

        return null;
    }

    /**
     * Update link of the given product
     *
     * @param \Weverson83\AddByLink\Api\Data\LinkInterface $link
     * @return int
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(LinkInterface $link): int
    {
        $this->resourceModel->save($link);
        return (int) $link->getId();
    }

    /**
     * Delete link
     *
     * @param \Weverson83\AddByLink\Api\Data\LinkInterface $link
     * @return bool
     * @throws \Exception
     */
    public function delete(LinkInterface $link): bool
    {
        $this->resourceModel->delete($link);
        return true;
    }

    /**
     * Retrieve one link by token string
     *
     * @param string $token
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface|null
     */
    public function getByToken(string $token): ?LinkInterface
    {
        $collection = $this->collectionFactory->create()
            ->addTokenFilter($token);

        foreach ($collection as $link) {
            return $link;
        }

        return null;
    }
}
