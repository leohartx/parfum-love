<?php
/**
 * J2t_Rewardsocial
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
 * @copyright  Copyright (c) 2012 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class J2t_Rewardsocial_Block_Socialpoints extends Mage_Catalog_Block_Product_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('j2trewardsocial/product_view.phtml');
    }
}
