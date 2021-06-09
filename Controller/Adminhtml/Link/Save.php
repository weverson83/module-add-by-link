<?php
declare(strict_types=1);

namespace Weverson83\AddByLink\Controller\Adminhtml\Link;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Weverson83\AddByLink\Api\LinkRepositoryInterface;
use Weverson83\AddByLink\Controller\Adminhtml\Link;
use Weverson83\AddByLink\Model\Link\SaveHandler;
use Weverson83\AddByLink\Model\Link\UrlHandler;

class Save extends Link
{
    /**
     * @var SaveHandler
     */
    private $saveHandler;
    /**
     * @var UrlHandler
     */
    private $urlHandler;

    /**
     * Save constructor.
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param SaveHandler $saveHandler
     * @param LinkRepositoryInterface $linkRepository
     * @param UrlHandler $urlHandler
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        SaveHandler $saveHandler,
        LinkRepositoryInterface $linkRepository,
        UrlHandler $urlHandler
    ) {
        parent::__construct($context, $dataPersistor, $resultForwardFactory, $resultPageFactory, $linkRepository);
        $this->saveHandler = $saveHandler;
        $this->urlHandler = $urlHandler;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $link = $this->saveHandler->execute($data);
                $this->messageManager->addSuccessMessage(
                    __('The link was generated with the url: %1', $this->urlHandler->getGeneratedUrl($link))
                );
                $this->dataPersistor->clear('add_by_link_link');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $link->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage($exception, __('Something went wrong while saving the Link.'));
            }

            $this->dataPersistor->set('add_by_link_link', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
