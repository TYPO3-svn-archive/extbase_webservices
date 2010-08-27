<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Jochen Rau <jochen.rau@typoplanet.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * A general purpose configuration manager used in frontend mode.
 *
 * Should NOT be singleton, as a new configuration manager is needed per plugin.
 *
 * @package Extbase
 * @subpackage Configuration
 * @version $ID:$
 */
class Tx_ExtbaseWebservices_Configuration_WebserviceConfigurationManager extends Tx_Extbase_Configuration_FrontendConfigurationManager {
	/**
	 * Loads the Extbase Framework configuration.
	 *
	 * The Extbase framework configuration HAS TO be retrieved using this method, as they are come from different places than the normal settings.
	 * Framework configuration is, in contrast to normal settings, needed for the Extbase framework to operate correctly.
	 *
	 * @param array $pluginConfiguration The current incoming extbase configuration
	 * @return array the Extbase framework configuration
	 */
	public function getFrameworkConfiguration($pluginConfiguration) {
		$frameworkConfiguration = array();
		$frameworkConfiguration['persistence']['storagePid'] = self::DEFAULT_BACKEND_STORAGE_PID;

		$setup = $this->loadTypoScriptSetup();
		$extbaseConfiguration = $setup['config.']['tx_extbase.'];
		if(is_array($pageConfiguration = $GLOBALS['TSFE']->pSetup['config.']['tx_extbase.'])) {
			$pageConfiguration = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($pageConfiguration);
			$extbaseConfiguration = t3lib_div::array_merge_recursive_overrule($extbaseConfiguration, $pageConfiguration);
		}
		if (is_array($extbaseConfiguration)) {
			$extbaseConfiguration = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($extbaseConfiguration);
			$frameworkConfiguration = t3lib_div::array_merge_recursive_overrule($frameworkConfiguration, $extbaseConfiguration);
		}

		if (isset($pluginConfiguration['settings'])) {
			$pluginConfiguration = $this->resolveTyposcriptReference($pluginConfiguration, 'settings');
		}
		if (!is_array($pluginConfiguration['settings.'])) $pluginConfiguration['settings.'] = array(); // We expect that the settings are arrays on various places
		if (isset($pluginConfiguration['persistence'])) {
			$pluginConfiguration = $this->resolveTyposcriptReference($pluginConfiguration, 'persistence');
		}
		if (isset($pluginConfiguration['view'])) {
			$pluginConfiguration = $this->resolveTyposcriptReference($pluginConfiguration, 'view');
		}
		if (isset($pluginConfiguration['webservice'])) {
			$pluginConfiguration = $this->resolveTyposcriptReference($pluginConfiguration, 'webservice');
		}
		if (isset($pluginConfiguration['_LOCAL_LANG'])) {
			$pluginConfiguration = $this->resolveTyposcriptReference($pluginConfiguration, '_LOCAL_LANG');
		}
		$frameworkConfiguration = t3lib_div::array_merge_recursive_overrule($frameworkConfiguration, Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($pluginConfiguration));

		$frameworkConfiguration = $this->getContextSpecificFrameworkConfiguration($frameworkConfiguration);
		return $frameworkConfiguration;
	}
}
?>