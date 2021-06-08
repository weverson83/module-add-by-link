<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;

class ReadHandler implements ExtensionInterface
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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = []): \Magento\Catalog\Api\Data\ProductInterface
    {
        $entityExtension = $entity->getExtensionAttributes();
        $links = $this->linkRepository->getActiveByProduct($entity);

        if ($links) {
            $entityExtension->setAddByLinkList($links);
        }

        $entity->setExtensionAttributes($entityExtension);

        return $entity;
    }
}
