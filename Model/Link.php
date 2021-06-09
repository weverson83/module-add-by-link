<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Intl\DateTimeFactory;
use Magento\Framework\Math\Random;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Api\Data\LinkProductInterface;
use Weverson83\AddByLink\Model\ResourceModel\Link as LinkResource;

/**
 * Class Link
 * @package Weverson83\AddByLink\Model
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @method self setAddedProductIds(array $productIds)
 * @method array getAddedProductIds()
 */
class Link extends AbstractModel implements LinkInterface
{

    /**
     * @var string
     */
    protected $_idFieldName = self::ID;
    /**
     * @var LinkResource
     */
    private $linkResource;
    /**
     * @var Random
     */
    private $mathRandom;
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * Link constructor.
     * @param Context $context
     * @param Registry $registry
     * @param LinkResource $linkResource
     * @param Random $mathRandom
     * @param DateTimeFactory $dateTimeFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        LinkResource $linkResource,
        Random $mathRandom,
        DateTimeFactory $dateTimeFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->linkResource = $linkResource;
        $this->mathRandom = $mathRandom;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Set resource
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(LinkResource::class);
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
     * @return LinkInterface
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
     * @return LinkInterface
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
     * @return LinkInterface
     */
    public function setExpirationPeriod(int $expirationPeriod): LinkInterface
    {
        return $this->setData(self::EXPIRATION_PERIOD, $expirationPeriod);
    }

    /**
     * Retrieve product IDs related to link
     *
     * @return array
     */
    public function getProductIds(): array
    {
        if (!$this->getId()) {
            return [];
        }

        $connection = $this->getResource()->getConnection();
        $select = $connection->select()
            ->from(
                ['main_table' => $connection->getTableName('add_by_link_product')],
                ['product_id']
            )->where(LinkProductInterface::LINK_ID . ' = ' . $this->getId());

        return $connection->fetchCol($select);
    }

    /**
     * Generates token with Unique hash for the new object
     *
     * @return Link
     * @throws LocalizedException
     */
    public function beforeSave(): Link
    {
        if ($this->isObjectNew()) {
            $this->setToken($this->mathRandom->getUniqueHash());
        }

        return parent::beforeSave();
    }

    /**
     * @return Link
     */
    public function afterSave(): Link
    {
        if (is_array($this->getAddedProductIds()) && count($this->getAddedProductIds())) {
            $this->linkResource->updateProductRelations($this, $this->getAddedProductIds());
        }

        return parent::afterSave();
    }
}
