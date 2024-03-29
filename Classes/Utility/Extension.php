<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Thomas Maroschik <tmaroschik@dfau.de>
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
 * Utilities to manage plugins and  modules of an extension. Also useful to auto-generate the autoloader registry
 * file ext_autoload.php.
 *
 * @package ExtbaseWebservices
 * @subpackage Utility
 * @version $ID:$
 */
class Tx_ExtbaseWebservices_Utility_Extension extends Tx_Extbase_Utility_Extension {

	/**
	 * Add auto-generated TypoScript to configure the Extbase Dispatcher.
	 *
	 * When adding a frontend plugin you will have to add both an entry to the TCA definition
	 * of tt_content table AND to the TypoScript template which must initiate the rendering.
	 * Since the static template with uid 43 is the "content.default" and practically always
	 * used for rendering the content elements it's very useful to have this function automatically
	 * adding the necessary TypoScript for calling the appropriate controller and action of your plugin.
	 * It will also work for the extension "css_styled_content"
	 * FOR USE IN ext_localconf.php FILES
	 * Usage: 2
	 *
	 * @param string $extensionName The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	 * @param string $pluginName must be a unique id for your plugin in UpperCamelCase (the string length of the extension key added to the length of the plugin name should be less than 32!)
	 * @param string $controllerActions is an array of allowed combinations of controller and action stored in an array (controller name as key and a comma separated list of action names as value, the first controller and its first action is chosen as default)
	 * @param string $nonCachableControllerActions is an optional array of controller name and  action names which should not be cached (array as defined in $controllerActions)
	 * @param string $defaultControllerAction is an optional array controller name (as array key) and action name (as array value) that should be called as default
	 * @return void
	 */
	public static function configurePlugin($extensionName, $pluginName, array $controllerActions, array $nonCachableControllerActions = array()) {
		if (empty($pluginName)) {
			throw new InvalidArgumentException('The plugin name must not be empty', 1239891987);
		}
		if (empty($extensionName)) {
			throw new InvalidArgumentException('The extension name was invalid (must not be empty and must match /[A-Za-z][_A-Za-z0-9]/)', 1239891989);
		}
		$extensionName = str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionName)));
		$pluginSignature = strtolower($extensionName) . '_' . strtolower($pluginName);
		$controllerCounter = 1;
		$hasMultipleActionsCounter = 0;
		$controllers = '';
		foreach ($controllerActions as $controller => $actionsList) {
			$actionsList .= ',ping';
			$controllers .= '
		' . $controllerCounter . '.controller = ' . $controller . '
		' . $controllerCounter . '.actions = ' . $actionsList;
			$controllerCounter++;
			if (strpos($actionsList, ',') !== FALSE) {
				$hasMultipleActionsCounter++;
			}
		}

		$switchableControllerActions = '';
		if ($controllerCounter > 1 || $hasMultipleActionsCounter > 0) {
				$switchableControllerActions = '
	switchableControllerActions {' . $controllers . '
	}';
		}

		reset($controllerActions);
		$defaultController = key($controllerActions);
		$controller = '
	controller = ' . $defaultController;
		$defaultAction = array_shift(t3lib_div::trimExplode(',', current($controllerActions)));
		$action = '
	action = ' . $defaultAction;

		$nonCachableActions = array();
		if (!empty($nonCachableControllerActions[$defaultController])) {
			$nonCachableActions = t3lib_div::trimExplode(',', $nonCachableControllerActions[$defaultController]);
		}
		$cachableActions = array_diff(t3lib_div::trimExplode(',', $controllerActions[$defaultController]), $nonCachableActions);

		$contentObjectType = in_array($defaultAction, $nonCachableActions) ? 'USER_INT' : 'USER';

		$conditions = '';
		foreach ($controllerActions as $controllerName => $actionsList) {
			if (!empty($nonCachableControllerActions[$controllerName])) {
				$nonCachableActions = t3lib_div::trimExplode(',', $nonCachableControllerActions[$controllerName]);
				$cachableActions = array_diff(t3lib_div::trimExplode(',', $controllerActions[$controllerName]), $nonCachableActions);
				if (($contentObjectType == 'USER' && count($nonCachableActions) > 0)
					|| ($contentObjectType == 'USER_INT' && count($cachableActions) > 0)) {

					$conditions .= '
[globalString = GP:tx_' . $pluginSignature . '|controller = ' . $controllerName . '] && [globalString = GP:tx_' . $pluginSignature . '|action = /' . implode('|', $contentObjectType === 'USER' ? $nonCachableActions : $cachableActions) . '/]
tt_content.list.20.' . $pluginSignature . ' = ' . ($contentObjectType === 'USER' ? 'USER_INT' : 'USER') . '
[global]
';
				}
			}
		}

		$pluginTemplate = 'plugin.tx_' . strtolower($extensionName) . ' {
	settings {
	}
	persistence {
		storagePid =
		classes {
		}
	}
	view {
		templateRootPath =
		layoutRootPath =
		partialRootPath =
	}
}';
		t3lib_extMgm::addTypoScript($extensionName, 'setup', '
# Setting ' . $extensionName . ' plugin TypoScript
' . $pluginTemplate);

		$pluginContent = trim('
tt_content.list.20.' . $pluginSignature . ' = ' . $contentObjectType . '
tt_content.list.20.' . $pluginSignature . ' {
	userFunc = tx_extbasewebservices_dispatcher->dispatch
	pluginName = ' . $pluginName . '
	extensionName = ' . $extensionName . '
	' . $controller .
	$action .
	$switchableControllerActions . '

	settings =< plugin.tx_' . strtolower($extensionName) . '.settings
	persistence =< plugin.tx_' . strtolower($extensionName) . '.persistence
	view =< plugin.tx_' . strtolower($extensionName) . '.view
	webservice =< plugin.tx_' . strtolower($extensionName) . '.webservice
	_LOCAL_LANG =< plugin.tx_' . strtolower($extensionName) . '._LOCAL_LANG
}
' . $conditions);

		t3lib_extMgm::addTypoScript($extensionName, 'setup', '
# Setting ' . $extensionName . ' plugin TypoScript
' . $pluginContent, 43);
	}

}
?>