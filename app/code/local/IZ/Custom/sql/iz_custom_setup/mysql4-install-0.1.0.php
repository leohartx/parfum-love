<?php
try{
    $installer = $this;
    $installer->startSetup();

    // Create footer block
    $content =
<<<EOD
<ul>
<li><a href="{{store direct_url="about-magento-demo-store"}}">About Us</a></li>
<li><a href="{{store direct_url="customer-service"}}">Customer Service</a></li>
</ul>
EOD;

	$title = 'Footer Links';

	$identifier = 'footer_links';

    $staticBlock = array(
        'title' => $title,
        'identifier' => $identifier,
        'content' => $content,
        'is_active' => 1,
        'stores' => array(0)
    );

	if (Mage::getModel('cms/block')->load($identifier)->getBlockId())
	{
		Mage::getModel('cms/block')->load($identifier)->delete();
	}
	Mage::getModel('cms/block')->setData($staticBlock)->save();

    $installer->endSetup();

}catch(Excpetion $e){
    Mage::logException($e);
    Mage::log("ERROR IN SETUP ".$e->getMessage());
}

