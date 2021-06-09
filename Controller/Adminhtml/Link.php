<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\PageFactory;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;

abstract class Link extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Weverson83_AddByLink::add_by_link';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var LinkRepositoryInterface
     */
    protected $linkRepository;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        LinkRepositoryInterface $linkRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->linkRepository = $linkRepository;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Add By Link'), __('Add By Link'))
            ->addBreadcrumb(__('Link'), __('Link'));

        return $resultPage;
    }
}
