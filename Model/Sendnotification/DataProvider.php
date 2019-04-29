<?php
namespace Infobeans\PushNotification\Model\Sendnotification;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [];
    }
    
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
}
