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
        $entityId = isset($data['entity_id']) ? (int) $data['entity_id'] : 0;

        $link = $this->getLinkObject($entityId);

        if (!$link->getId() && $entityId) {
            throw new LocalizedException(__('This Link no longer exists.'));
        }
        unset($data['token']);
        unset($data['token_created_at']);

        $link->setData($data);

        if (isset($data['link_products'])) {
            $productIds = array_filter($this->json->unserialize($data['link_products']), function ($productId) {
                return is_integer($productId) && $productId > 0;
            }, ARRAY_FILTER_USE_KEY);

            $link->setAddedProductIds(array_keys($productIds));
        }

        return $this->linkRepository->save($link);
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
