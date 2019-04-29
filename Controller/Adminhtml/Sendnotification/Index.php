<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Registered users grid
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Sendnotification;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    //@codingStandardsIgnoreStart
    /**
     * @var ResultFactory
     */
    protected $resultPageFactory;
    // @codingStandardsIgnoreEnd
    
    /**
     * @param Context $context
     * @param ResultFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Infobeans_PushNotification::templates');
        $resultPage->getConfig()->getTitle()->prepend(__("Send Notification"));
        return $resultPage;
    }
}
