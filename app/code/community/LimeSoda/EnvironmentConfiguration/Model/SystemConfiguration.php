<?php

class LimeSoda_EnvironmentConfiguration_Model_SystemConfiguration
{

    /**
     * Sets a cookie containing the current configured environment name
     *
     */
    public function initCookie() 
    {
        $environment = Mage::helper('limesoda_environmentconfiguration/current')->getEnvironmentName();
        Mage::getModel('core/cookie')->set('limesoda_environment', $environment, 60*60*24*30, '/', null, null, false);
    }

    private function _getDefaultConfiguration(Mage_Core_Model_Config_Element $configuration, array $result)
    {
        $default = $configuration->descend('default');
        if ($default === false) {
            return $result;
        }

        foreach ($default->children() as $first => $firstLevel) {
            foreach ($firstLevel->children() as $second => $secondLevel) {
                foreach($secondLevel->children() as $third => $thirdLevel) {
                    $third = $thirdLevel->getName();

                    $key = 'system_configuration_default_' . $first . '_' . $second . '_' . $third;
                    $path = $first . '/' . $second . '/' . $third;
                    $result[$key] = 'config:set "' . $path . '" "' . strval($thirdLevel) . '"';
                }
            }
        }

        return $result;
    }

    /**
     * Creates n98-magerun commands "config:set" with store view scope from the configuration XML.
     *
     * Store view can be specified either using the store code or an numeric ID greater 0.
     *
     * @param Mage_Core_Model_Config_Element $configuration
     * @param array $result
     * @return array
     */
    private function _getStoresConfiguration(Mage_Core_Model_Config_Element $configuration, array $result)
    {
        $stores = $configuration->descend('stores');
        if ($stores === false) {
            return $result;
        }

        $storeModels = Mage::app()->getStores(false, true);

        foreach ($stores->children() as $storeName => $store) {

            if (array_key_exists($storeName, $storeModels)) {
                $storeModel = $storeModels[$storeName];
                $storeId = $storeModel->getId();
            } elseif (intval($storeName) > 0) {
                $storeId = intval($storeName);
            } else {
                continue;
            }

            foreach ($store->children() as $first => $firstLevel) {
                foreach ($firstLevel->children() as $second => $secondLevel) {
                    foreach($secondLevel->children() as $third => $thirdLevel) {
                        $third = $thirdLevel->getName();

                        $key = 'system_configuration_stores_' . $storeName . '_' . $first . '_' . $second . '_' . $third;
                        $path = $first . '/' . $second . '/' . $third;
                        $result[$key] = 'config:set --scope="stores" --scope-id="' . $storeId . '" "' . $path . '" "' . strval($thirdLevel) . '"';
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Creates n98-magerun commands "config:set" with website scope from the configuration XML.
     *
     * websites can be specified either using the website code or an numeric ID greater 0.
     *
     * @param Mage_Core_Model_Config_Element $configuration
     * @param array $result
     * @return array
     */
    private function _getWebsitesConfiguration(Mage_Core_Model_Config_Element $configuration, array $result)
    {
        $websites = $configuration->descend('websites');
        if ($websites === false) {
            return $result;
        }

        $websiteModels = Mage::app()->getWebsites(false, true);

        foreach ($websites->children() as $websiteName => $website) {

            if (array_key_exists($websiteName, $websiteModels)) {
                $websiteModel = $websiteModels[$websiteName];
                $websiteId = $websiteModel->getId();
            } elseif (intval($websiteName) > 0) {
                $websiteId = intval($websiteName);
            } else {
                continue;
            }

            foreach ($website->children() as $first => $firstLevel) {
                foreach ($firstLevel->children() as $second => $secondLevel) {
                    foreach($secondLevel->children() as $third => $thirdLevel) {
                        $third = $thirdLevel->getName();

                        $key = 'system_configuration_websites_' . $websiteName . '_' . $first . '_' . $second . '_' . $third;
                        $path = $first . '/' . $second . '/' . $third;
                        $result[$key] = 'config:set --scope="websites" --scope-id="' . $websiteId . '" "' . $path . '" "' . strval($thirdLevel) . '"';
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Processes the node 'system_configuration'.
     *
     * @param Mage_Core_Model_Config_Element $config
     * @param array $result
     * @return array
     */
    public function getCommands(Mage_Core_Model_Config_Element $config, array $result)
    {
        $systemConfiguration = $config->descend('system_configuration');
        if ($systemConfiguration === false) {
            return $result;
        }

        $result = $this->_getDefaultConfiguration($systemConfiguration, $result);
        $result = $this->_getWebsitesConfiguration($systemConfiguration, $result);
        $result = $this->_getStoresConfiguration($systemConfiguration, $result);

        return $result;
    }
}
