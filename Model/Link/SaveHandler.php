<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Model\LinkFactory;

class SaveHandler
{
    /**
     * @var LinkFactory
     */
    private $linkFactory;
    /**
     * @var LinkRepositoryInterface
     */
    private $linkRepository;
    /**
     * @var Json
     */
    private $json;

    /**
     * SaveHandler constructor.
     * @param LinkFactory $linkFactory
     * @param LinkRepositoryInterface $linkRepository
     * @param Json $json
     */
    public function __construct(
        LinkFactory $linkFactory,
        LinkRepositoryInterface $linkRepository,
        Json $json
    ) {
        $this->linkFactory = $linkFactory;
        $this->linkRepository = $linkRepository;
        $this->json = $json;
    }

    /**
     * @param array $data
     * @return LinkInterface
     * @throws LocalizedException
     */
    public function execute(array $data): LinkInterface
    {
        $entityId = (int) isset($data['id']) ?? 0;

        $link = $this->getLinkObject($entityId);

        if (!$link->getId() && $entityId) {
            throw new LocalizedException(__('This Link no longer exists.'));
        }

        $link->setData($data);

        if (isset($data['link_products'])) {
            $productIds = $this->json->unserialize($data['link_products']);
            $link->setAddedProductIds(array_keys($productIds));
        }

        $this->linkRepository->save($link);

        return $link;
    }

    /**
     * @param int|null $entityId
     * @return LinkInterface
     */
    protected function getLinkObject(int $entityId): LinkInterface
    {
        if (!empty($entityId)) {
            $link = $this->linkRepository->getById($entityId);
            if ($link !== null) {
                return $link;
            }
        }

        return $this->linkFactory->create();
    }
}
