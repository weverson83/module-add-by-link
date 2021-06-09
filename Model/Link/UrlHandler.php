<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\UrlInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;

class UrlHandler
{
    /**
     * @var UrlInterface
     */
    private $urlInterface;

    /**
     * UrlHandler constructor.
     * @param UrlInterface $urlInterface
     */
    public function __construct(UrlInterface $urlInterface)
    {
        $this->urlInterface = $urlInterface;
    }

    /**
     * @param int|null $linkId
     * @return string
     */
    public function getGeneratedUrl(LinkInterface $link): string
    {
        return $this->urlInterface->getBaseUrl() . 'add_by_link/token/process/token/' . $link->getToken();
    }
}
