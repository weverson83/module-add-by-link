<?php

namespace Weverson83\AddByLink\Block\Adminhtml\Link;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\BlockInterface;
use Weverson83\AddByLink\Model\Link;

class AssignProducts extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Weverson83_AddByLink::link/edit/assign_products.phtml';

    /**
     * @var \Weverson83\AddByLink\Block\Adminhtml\Link\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var Json
     */
    protected $json;

    /**
     * AssignProducts constructor.
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataPersistor = $dataPersistor;
        $this->json = $json;
    }

    /**
     * Retrieve instance of grid block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getBlockGrid(): BlockInterface
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                \Weverson83\AddByLink\Block\Adminhtml\Link\Tab\Product::class,
                'link.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     * @throws LocalizedException
     */
    public function getGridHtml(): string
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProductsJson(): string
    {
        $products = [];
        foreach ($this->getCurrentLink()->getProductIds() as $position => $productId) {
            $products[$productId] = $position+1;
        }

        if (!empty($products)) {
            return $this->json->serialize($products);
        }
        return $this->json->serialize([]);
    }

    /**
     * Retrieve current link instance
     *
     * @return Link|null
     */
    public function getCurrentLink(): Link
    {
        return $this->dataPersistor->get('add_by_link_link');
    }
}
