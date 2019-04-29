<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Delete template
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use \Infobeans\PushNotification\Model\Templates;

class Delete extends \Magento\Backend\App\Action
{
    //@codingStandardsIgnoreStart
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Infobeans_PushNotification::pushnotification_delete';

    /**
     * @var \Infobeans\PushNotification\Helper $notifyhelper
     */
    protected $notifyhelper;
    
    /**
     * @var \Infobeans\PushNotification\Model\Templates $template
     */
    protected $template;
    //@codingStandardsIgnoreEnd
    
    /**
     * @param Action\Context $context
     * @param \Infobeans\PushNotification\Helper\Data $notifyhelper
     */
    public function __construct(
        Action\Context $context,
        \Infobeans\PushNotification\Helper\Data $notifyhelper,
        \Infobeans\PushNotification\Model\Templates $template
    ) {
        $this->notifyhelper = $notifyhelper;
        $this->template = $template;
        parent::__construct($context);
    }
    
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->template->create();
                $model->load($id);
                
                $title = $model->getTitle();
                if ($model->delete()) {
                    $this->notifyhelper->removeLogo($model->getLogo());
                }
                
                // display success message
                $this->messageManager->addSuccessMessage(__('Notification template has been deleted.'));
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a notification template to delete.'));
        
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
