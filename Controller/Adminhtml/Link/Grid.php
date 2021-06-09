<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Controller\Adminhtml\Link;

class Grid extends Link
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * Grid constructor.
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param LinkRepositoryInterface $linkRepository
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        LinkRepositoryInterface $linkRepository
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context, $dataPersistor, $resultForwardFactory, $resultPageFactory, $linkRepository);
    }

    /**
     * Grid Action
     * Display list of products related to current link
     *
     * @return Raw
     */
    public function execute(): Raw
    {
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                \Weverson83\AddByLink\Block\Adminhtml\Link\Tab\Product::class,
                'link.product.grid'
            )->toHtml()
        );
    }
}
