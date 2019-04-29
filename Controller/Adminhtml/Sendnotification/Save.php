<?php

/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Save templates
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Sendnotification;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    //@codingStandardsIgnoreStart
    /**
     * @var \Infobeans\PushNotification\Helper $notifyhelper
     */
    protected $notifyhelper;

    /**
     * @var \Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory
     */
    protected $collectionFactory;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    protected  $timezone;
    // @codingStandardsIgnoreEnd
    
    /**
     * @param Action\Context $context
     * @param \Infobeans\PushNotification\Helper\Data $notifyhelper
     */
    public function __construct(
        Action\Context $context,
        \Infobeans\PushNotification\Helper\Data $notifyhelper,
        \Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->notifyhelper = $notifyhelper;
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $moduleStatus = $this->notifyhelper->getModuleStatus();

        if (!$moduleStatus) {
            $this->messageManager->addError(__("Please enable the module"));
            $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        $data = $this->getRequest()->getPostValue();
        $users = $this->collectionFactory->create();

        if (isset($data['type']) && $data['type'] == 1) {
            $fromDate = date("Y-m-d h:i:s", strtotime($data['from_date']));
            $toDate = date("Y-m-d h:i:s", strtotime($data['to_date']));
            $validateResult = $this->validateDate($data);
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addError($errorMessage);
                }
                $resultRedirect->setPath('*/*/index');
                return $resultRedirect;
            }
            $users->addFieldToFilter('created_at', ['gteq' => $fromDate]);
            $users->addFieldToFilter('created_at', ['lteq' => $toDate]);
        }
        
        $templateId = $data['template_id'];

        if ($users->getSize() == 0) {
            $this->messageManager->addError(__('No users are subscribed for the selected date range.'));
            $resultRedirect->setPath('*/*/index');
        }

        $notificationSent = 0;
        foreach ($users->getAllIds() as $user) {
            $response = $this->notifyhelper->processNotification($user, $templateId);
            $notificationSent++;
        }

        if ($notificationSent) {
            $this->messageManager->addSuccess(__('A total of %1 notification(s) were send.', $notificationSent));
        }

        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    private function validateDate($formData = [])
    {
        $result = [];
        $fromDate = $formData['from_date'];
        $toDate = $formData['to_date'];
        if ($fromDate && $toDate) {
            $fromDate = $this->timezone->date($fromDate);
            $toDate = $this->timezone->date($toDate);

            if ($fromDate > $toDate) {
                $result[] = __('End Date must follow Start Date.');
            }
        }
        return !empty($result) ? $result : true;
    }
}
