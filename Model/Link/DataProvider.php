<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\UrlInterface;
use Weverson83\AddByLink\Api\Data\LinkInterface;
use Weverson83\AddByLink\Model\ResourceModel\Link\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var LinkInterface[]
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var UrlInterface
     */
    private $urlInterface;


    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param UrlInterface $urlInterface
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        UrlInterface $urlInterface,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->urlInterface = $urlInterface;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $link = $this->dataPersistor->get('add_by_link_link');

        if (!empty($link)) {
            $this->loadedData[$link->getId()] = $link->getData();
        }

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        foreach ($this->collection as $link) {
            $this->loadedData[$link->getId()] = $link->getData();
        }

        return $this->loadedData;
    }

    /**
     * Add the generated url (if any) to token notice
     *
     * @return array
     */
    public function getMeta(): array
    {
        $meta = parent::getMeta();

        $link = $this->dataPersistor->get('add_by_link_link');
        if ($link->getId()) {
            $config = [
                'general' => [
                    'children' => [
                        'token' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'notice' => __('You can copy the url: ') . $this->getGeneratedUrl($link),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            $meta = array_merge_recursive($meta, $config);
        }

        return $meta;
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
