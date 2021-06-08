<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\Data\LinkProductInterface;
use Weverson83\AddByLink\Model\ResourceModel\LinkProduct\CollectionFactory as LinkProductCollectionFactory;

/**
 * Class Link
 * @package Weverson83\AddByLink\Model
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Link extends AbstractModel implements LinkInterface
{

    /**
     * @var string
     */
    protected $_idFieldName = self::ID;

    /**
     * @var LinkProductCollectionFactory
     */
    private $linkProdColFactory;

    /**
     * Link constructor.
     * @param Context $context
     * @param Registry $registry
     * @param LinkProductCollectionFactory $linkProdColFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        LinkProductCollectionFactory $linkProdColFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->linkProdColFactory = $linkProdColFactory;
    }

    /**
     * Set resource
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(\Weverson83\AddByLink\Model\ResourceModel\Link::class);
    }

    /**
     * Retrieve token hash string
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->getData(self::TOKEN);
    }

    /**
     * Set token hash string
     *
     * @param string $token
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setToken(string $token): LinkInterface
    {
        return $this->setData(self::TOKEN, $token);
    }

    /**
     * Retrieve token's creation timestamp
     *
     * @return string
     */
    public function getTokenCreatedAt(): string
    {
        return $this->getData(self::TOKEN_CREATED_AT);
    }

    /**
     * Set token's creation timestamp
     *
     * @param string $tokenCreatedAt
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setTokenCreatedAt(string $tokenCreatedAt): LinkInterface
    {
        return $this->setData(self::TOKEN_CREATED_AT, $tokenCreatedAt);
    }

    /**
     * Retrieve expiration period in hours
     *
     * @return int
     */
    public function getExpirationPeriod(): int
    {
        return (int) $this->getData(self::EXPIRATION_PERIOD);
    }

    /**
     * Set expiration period in hours
     *
     * @param int $expirationPeriod
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setExpirationPeriod(int $expirationPeriod): LinkInterface
    {
        return $this->setData(self::EXPIRATION_PERIOD, $expirationPeriod);
    }

    /**
     * @return array
     */
    public function getProductIds(): array
    {
        /** @var \Weverson83\AddByLink\Model\ResourceModel\LinkProduct\Collection $collection */
        $collection = $this->linkProdColFactory->create();
        $collection->addFilter(LinkProductInterface::LINK_ID, $this->getId());

        return $collection->getConnection()->fetchPairs($collection->getSelect());
    }
}
