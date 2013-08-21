<?php
try{
    $installer = $this;
    $installer->startSetup();

    // Create home news block
    $content =
<<<EOD
<div class="home-news">
	<div class="news-left">
		<div class="news-title">Latest news and announcements</div>
		<div class="container">
			<div class="news-block">
				<div class="date-time">
					6/01/2013
				</div>
				<div class="title">
					Lorem ipsum dolor sit amet
				</div>
				<div class="excerpt">
					 Consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation.
				</div>
				<div class='more'>
					<a href="#">
						more
					</a>
				</div>
			</div>
			<div class="news-block">
				<div class="date-time">
					21/01/2013
				</div>
				<div class="title">
					Lorem ipsum dolor sit amet
				</div>
				<div class="excerpt">
					 Consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation.
				</div>
				<div class='more'>
					<a href="#">
						more
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="news-right">
		<div class="news-title">Top sellers</div>
		<div class="container">
			<div class="product-image"><a href="#"><img class="first" src="{{skin url="images/product-image.jpg"}}"/></a></div>
			<div class="product-image"><a href="#"><img class="" src="{{skin url="images/product-image.jpg"}}"/></a></div>
			<div class="product-image"><a href="#"><img class="last" src="{{skin url="images/product-image.jpg"}}"/></a></div>
		</div>
	</div>
</div>
EOD;

	$title = 'Home news';

	$identifier = 'home-news';

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


