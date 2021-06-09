<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Model\Link;

use Magento\Framework\App\Request\DataPersistorInterface;
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
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        foreach ($this->collection as $link) {
            $this->loadedData[$link->getId()] = $link->getData();
        }

        $link = $this->dataPersistor->get('add_by_link_link');

        if (!empty($link)) {
            $this->loadedData[$link->getId()] = $link->getData();
        }

        return $this->loadedData;
    }
}
