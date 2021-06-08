<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\State\ExpiredException;
use Magento\Framework\Intl\DateTimeFactory;
use Weverson83\AddByLink\Api\Data\LinkInterface;

class Validation
{
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * Validation constructor.
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @param LinkInterface $link
     * @return bool
     * @throws ExpiredException
     * @throws InputException
     */
    public function execute(LinkInterface $link): bool
    {
        if (!is_string($link->getToken()) || empty($link->getToken())) {
            $params = ['fieldName' => 'LinkToken'];
            throw new InputException(__('"%fieldName" is required. Enter and try again.', $params));
        }

        if ($this->isTokenExpired($link)) {
            throw new ExpiredException(__('The token is expired. Reset and try again.'));
        }

        return true;
    }

    /**
     * Verify token and check if the link is expired
     *
     * @param LinkInterface $link
     * @return bool
     */
    private function isTokenExpired(LinkInterface $link): bool
    {
        if (empty($link->getToken()) || empty($link->getTokenCreatedAt())) {
            return true;
        }

        $timestamp = $this->dateTimeFactory->create()->getTimestamp();
        $tokenTimestamp = $this->dateTimeFactory->create($link->getTokenCreatedAt())->getTimestamp();
        $hourDifference = floor(($timestamp - $tokenTimestamp) / (60 * 60));

        return ($tokenTimestamp > $timestamp || $hourDifference >= $link->getExpirationPeriod());
    }
}
