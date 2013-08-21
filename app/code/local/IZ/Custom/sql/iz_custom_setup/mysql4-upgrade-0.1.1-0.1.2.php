<?php
try{
    $installer = $this;
    $installer->startSetup();

    $content = <<<EOD
<div style="display: none;">&nbsp;</div>
{{block type="cms/block" block_id="home-news"}}
EOD;

	$layout_update = <<<EOD
<!--
<reference name="head">
	<action method="addCss"><stylesheet>css/landingpage.css</stylesheet></action>
</reference>
<reference name="root">
    <action method="unsetChild"><alias>breadcrumbs</alias></action>
    <block type="page/html_breadcrumbs" name="breadcrumbs" as="breadcrumbs">
        <action method="addCrumb">
            <crumbName>Home</crumbName>
            <crumbInfo><label>Home</label><title>Home</title><link>/</link></crumbInfo>
        </action>
        <action method="addCrumb">
            <crumbName>Parent CMS Page</crumbName>
            <crumbInfo><label>About Us</label><title>About Us</title><link>/about-us</link></crumbInfo>
        </action>
        <action method="addCrumb">
            <crumbName>Child CMS Page</crumbName>
            <crumbInfo><label>Water Filters for Charity</label><title>Water Filters for Charity</title></crumbInfo>
        </action>
    </block>
</reference>
-->
EOD;

	$meta_keywords = <<<EOD
EOD;

	$meta_description = <<<EOD
EOD;

	$title = 'Home page';

	$identifier = 'home';

    $cmspage = array(
        'title' => $title,
        'identifier' => $identifier,
        'content' => $content,
        'sort_order' => 0,
		'stores' => array(0),
		'root_template' => 'one_column',
		'layout_update_xml' => $layout_update,
		'meta_keywords' => $meta_keywords,
		'meta_description' => $meta_description
    );
	if (Mage::getModel('cms/page')->load($identifier)->getPageId())
	{
		Mage::getModel('cms/page')->load($identifier)->delete();
	}

	Mage::getModel('cms/page')->setData($cmspage)->save();
    $installer->endSetup();

}catch(Excpetion $e){
    Mage::logException($e);
    Mage::log("ERROR IN SETUP ".$e->getMessage());
}


