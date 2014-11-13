<?php

class LimeSoda_EnvironmentConfiguration_Block_Adminhtml_Overview extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_headerText = Mage::helper('limesoda_environmentconfiguration')->__('Environment Configuration');
        parent::_construct();
    }

}
