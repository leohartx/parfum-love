<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    J2t_Rewardsocial
 * @copyright  Copyright (c) 2012 J2T DESIGN. (http://www.j2t-design.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class J2t_Rewardsocial_Helper_Data extends Mage_Core_Helper_Abstract {
    public function createTinyUrl($strURL) {
        $ctx = stream_context_create(array( 
            'http' => array( 
                'timeout' => 20
                ) 
            ) 
        ); 
        $tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$strURL, 0, $ctx); 
        if (strpos($tinyurl, "http") === false){
            $tinyurl = $strURL;
        }
        
        //$tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$strURL);
        return $tinyurl;
    }
    
    protected function make_bitly_url($url, $login, $appkey, $format = 'xml', $version = '2.0.1')
    {
        //create the URL
        $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

        //get the url
        //could also use cURL here
        $response = file_get_contents($bitly);

        //parse depending on desired format
        if(strtolower($format) == 'json')
        {
            $json = @json_decode($response,true);
            return $json['results'][$url]['shortUrl'];
        }
        else //xml
        {
            $xml = simplexml_load_string($response);
            return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
        }
    }
    
    public function createShortUrl($strURL) {
        $short = $strURL;
        //$short = $this->make_bitly_url($strURL, 'o_4deddemids', 'R_4289e18d843f59c6daa0ca213adc2fa6', 'json');
        if (Mage::getStoreConfig('rewardpoints/social/use_url_shortener', Mage::app()->getStore()->getId())){
            $short = $this->createTinyUrl($strURL);
        }
        return $short; 
    }
    
    public function checkDailySocial($customer_id) {
        $now = Mage::getModel('core/date')->date('Y-m-d');
        if ($delay = Mage::getStoreConfig('rewardpoints/default/points_delay', Mage::app()->getStore()->getId())){
            if (is_numeric($delay)){
                $now = Mage::getModel("rewardpoints/stats")->getResource()->formatDate(mktime(0, 0, 0, date("m"), date("d")+$delay, date("Y")));
            }
        }
        
        $collection = Mage::getModel("rewardpoints/stats")->getCollection();
        $collection->getSelect()->columns(array("item_qty" => "COUNT(main_table.rewardpoints_account_id)"));
        $collection->getSelect()->where("main_table.customer_id = ?", $customer_id);
        $collection->getSelect()->where('"'.$now.'" = main_table.date_start');
        $collection->getSelect()->where("main_table.order_id in (?)", array(Rewardpoints_Model_Stats::TYPE_POINTS_FB, Rewardpoints_Model_Stats::TYPE_POINTS_GP, Rewardpoints_Model_Stats::TYPE_POINTS_PIN, Rewardpoints_Model_Stats::TYPE_POINTS_TT));
        
        //echo $collection->getSelect()->__toString();
        //die;
        
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $result = $db->query($collection->getSelect()->__toString());
        
        if(!$result) {
            return 0;
        }
        $rows = $result->fetch(PDO::FETCH_ASSOC);

        if(!$rows) {
            return 0;
        }
        return $rows['item_qty'];
    }
}
