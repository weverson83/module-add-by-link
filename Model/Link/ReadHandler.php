<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;

class CreateHandler implements ExtensionInterface
{

    /**
     * @var \Weverson83\AddByLink\Api\LinkRepositoryInterface
     */
    protected $linkRepository;

    public function __construct(LinkRepositoryInterface $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $entity
     * @param array $arguments
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function execute($entity, $arguments = [])
    {
        /** @var \Weverson83\AddByLink\Api\Data\LinkInterface[] $links */
        $links = $entity->getExtensionAttributes()->getAddByLinkList() ?? [];

        foreach ($links as $link) {
            $link->setId(null);
            $this->linkRepository->save($entity->getSku(), $link, !(bool)$entity->getStoreId());
        }
    }
}
