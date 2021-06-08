<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;

class LinkRepository implements LinkRepositoryInterface
{

    /**
     * @var \Weverson83\AddByLink\Model\ResourceModel\Link\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Weverson83\AddByLink\Model\ResourceModel\Link
     */
    private $resourceModel;

    public function __construct(
        \Weverson83\AddByLink\Model\ResourceModel\Link\CollectionFactory $collectionFactory,
        \Weverson83\AddByLink\Model\ResourceModel\Link $resourceModel
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
        /** @var \Weverson83\AddByLink\Model\ResourceModel\Link\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addActiveFilter();

        $links = [];
        if ($collection->getSize()) {
            foreach ($collection as $item) {
                $links[] = $item;
            }

            return $links;
        }

        return null;
    }

    public function getByProduct(ProductInterface $product): ?array
    {

    }

    public function save(LinkInterface $link): int
    {
        $this->resourceModel->save($link);
        return (int) $link->getId();
    }

    public function delete(LinkInterface $link): bool
    {
        // TODO: Implement delete() method.
    }
}
