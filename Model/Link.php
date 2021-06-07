<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model;

use Weverson83\AddByLink\Api\Data\LinkInterface;

class Link extends \Magento\Framework\DataObject implements LinkInterface
{

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(self::ID);
    }

    /**
     * @param int $entityId
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setId(int $entityId): LinkInterface
    {
        return $this->setData(self::ID, $entityId);
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
        return $this->getData(self::EXPIRATION_PERIOD);
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
}
