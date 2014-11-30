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

    /**
     * @copyright Tatu Ulmanen (http://stackoverflow.com/a/2915920)
     * @param array|null $tree
     * @return string
     */
    public function getEnvironmentTreeHtml(array $tree = null)
    {
        $result = '';
        if(!is_null($tree) && count($tree) > 0) {
            $result .= '<ul class="environment-list">';
            foreach($tree as $node) {
                $url = $this->getUrl('*/*/*', array('env' => $node['name']));
                $name = $this->escapeHtml($node['name']);
                $result .= '<li class="environment-list-node">';
                if ($this->hasEnvironment() && $this->getEnvironment() == $node['name']) {
                    $result .= '<span class="environment-list-selected">' . $name . '</span>' .
                                ' [ <a href="' . $this->getUrl('*/*/*') . '">' . $this->__('Unselect') . '</a> ]';
                } else {
                    $result .= '<a href="' . $url . '">' . $name . '</a>';
                }
                if (Mage::helper('limesoda_environmentconfiguration/current')->getEnvironmentName() == $node['name']) {
                    $result .= ' [ ' . $this->__('Current environment') . ' ]';
                }
                $result .= $this->getEnvironmentTreeHtml($node['children']);
                $result .= '</li>';
            }
            $result .= '</ul>';
        }
        return $result;
    }

    /**
     * @param string $command
     * @return string
     */
    public function renderCommand($command)
    {
        $commandEscaped = $this->escapeHtml($command);
        $helper = Mage::helper('limesoda_environmentconfiguration');

        $pattern = $helper->getVariableRegex();
        $replacement = '<span class="variable-missing" title="' .
                       $helper->__('Variable ${1} was not defined for this environment.') .
                       '">${1}</span>';
        return preg_replace($pattern, $replacement, $commandEscaped);
    }

}
