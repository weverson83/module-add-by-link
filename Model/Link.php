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

/**
 * Class Link
 * @package Weverson83\AddByLink\Model
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @method setAddedProductIds(array $productIds)
 * @method getAddedProductIds()
 */
class Link extends AbstractModel implements LinkInterface
{

    /**
     * @var string
     */
    protected $_idFieldName = self::ID;

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
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
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

    public function afterSave(): Link
    {
        if (is_array($this->getAddedProductIds())) {
            $connection = $this->getResource()->getConnection();
            $where = sprintf(
                '%s = %s AND %s NOT IN (%s)',
                LinkProductInterface::LINK_ID,
                $connection->quote($this->getId()),
                LinkProductInterface::PRODUCT_ID,
                $connection->quote($this->getAddedProductIds())
            );
            $connection->delete('add_by_link_product', $where);
            $links = [];

            foreach ($this->getAddedProductIds() as $productId) {
                $links[] = [
                    LinkProductInterface::LINK_ID => $this->getId(),
                    LinkProductInterface::PRODUCT_ID => $productId,
                ];
            }

            $connection->insertOnDuplicate(
                $connection->getTableName('add_by_link_product'),
                $links,
                [LinkProductInterface::LINK_ID, LinkProductInterface::PRODUCT_ID]
            );
        }

        return parent::afterSave();
    }
}
