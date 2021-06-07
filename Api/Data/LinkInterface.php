<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Api\Data;

interface LinkInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const ID = 'entity_id';
    const TOKEN = 'token';
    const TOKEN_CREATED_AT = 'token_created_at';
    const EXPIRATION_PERIOD = 'expiration_period';
    /**#@-*/

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $entityId
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setId(int $entityId): LinkInterface;

    /**
     * Retrieve token hash string
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Set token hash string
     *
     * @param string $token
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setToken(string $token): LinkInterface;

    /**
     * Retrieve token's creation timestamp
     *
     * @return string
     */
    public function getTokenCreatedAt(): string;

    /**
     * Set token's creation timestamp
     *
     * @param string $tokenCreatedAt
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setTokenCreatedAt(string $tokenCreatedAt): LinkInterface;

    /**
     * Retrieve expiration period in hours
     *
     * @return int
     */
    public function getExpirationPeriod(): int;

    /**
     * Set expiration period in hours
     *
     * @param int $expirationPeriod
     * @return \Weverson83\AddByLink\Api\Data\LinkInterface
     */
    public function setExpirationPeriod(int $expirationPeriod): LinkInterface;
}
