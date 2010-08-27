<?php

class Tx_ExtbaseWebservices_Dispatcher extends Tx_Extbase_Dispatcher {
	/**
	 * Creates a request an dispatches it to a controller.
	 *
	 * @param string $content The content
	 * @param array $configuration The TS configuration array
	 * @return string $content The processed content
	 */
	public function dispatch($content, $configuration) {
		// FIXME Remove the next lines. These are only there to generate the ext_autoload.php file
		//$extutil = new Tx_Extbase_Utility_Extension;
		//$extutil->createAutoloadRegistryForExtension('extbase', t3lib_extMgm::extPath('extbase'));
		//$extutil->createAutoloadRegistryForExtension('fluid', t3lib_extMgm::extPath('fluid'));

		$this->timeTrackPush('Extbase is called.','');
		$this->timeTrackPush('Extbase gets initialized.','');

		if (!is_array($configuration)) {
			t3lib_div::sysLog('Extbase was not able to dispatch the request. No configuration.', 'extbase', t3lib_div::SYSLOG_SEVERITY_ERROR);
			return $content;
		}

		$this->initializeConfigurationManagerAndFrameworkConfiguration($configuration);

		$requestBuilder = t3lib_div::makeInstance('Tx_ExtbaseWebservices_MVC_Web_WebserviceRequestBuilder');
		$request = $requestBuilder->initialize(self::$extbaseFrameworkConfiguration);
		$request = $requestBuilder->build();

//die();
		if($requestBuilder->getRequestFormatConfiguration()) {
			self::$extbaseFrameworkConfiguration = t3lib_div::array_merge_recursive_overrule(self::$extbaseFrameworkConfiguration, $requestBuilder->getRequestFormatConfiguration());
		}

		if (isset($this->cObj->data) && is_array($this->cObj->data)) {
			// we need to check the above conditions as cObj is not available in Backend.
			$request->setContentObjectData($this->cObj->data);
			$request->setIsCached($this->cObj->getUserObjectType() == tslib_cObj::OBJECTTYPE_USER);
		}
		$response = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Response');

		// Request hash service
		$requestHashService = t3lib_div::makeInstance('Tx_Extbase_Security_Channel_RequestHashService'); // singleton
		$requestHashService->verifyRequest($request);

		$persistenceManager = self::getPersistenceManager();

		$this->timeTrackPull();

		$this->timeTrackPush('Extbase dispatches request.','');
		$dispatchLoopCount = 0;
		while (!$request->isDispatched()) {
			if ($dispatchLoopCount++ > 99) throw new Tx_Extbase_MVC_Exception_InfiniteLoop('Could not ultimately dispatch the request after '  . $dispatchLoopCount . ' iterations.', 1217839467);
			$controller = $this->getPreparedController($request);
			try {
				$controller->processRequest($request, $response);
			} catch (Tx_Extbase_MVC_Exception_StopAction $ignoredException) {
			}
		}
		$this->timeTrackPull();

		$this->timeTrackPush('Extbase persists all changes.','');
		$flashMessages = t3lib_div::makeInstance('Tx_Extbase_MVC_Controller_FlashMessages'); // singleton
		$flashMessages->persist();
		$persistenceManager->persistAll();
		$this->timeTrackPull();

		self::$reflectionService->shutdown();

		if (count($response->getAdditionalHeaderData()) > 0) {
			$GLOBALS['TSFE']->additionalHeaderData[$request->getControllerExtensionName()] = implode("\n", $response->getAdditionalHeaderData());
		}
		$response->sendHeaders();
		$this->timeTrackPull();
		return $response->getContent();
	}

	/**
	 * Initializes the configuration manager and the Extbase settings
	 *
	 * @param $configuration The current incoming configuration
	 * @return void
	 */
	protected function initializeConfigurationManagerAndFrameworkConfiguration($configuration) {
		if (TYPO3_MODE === 'FE') {
			self::$configurationManager = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Configuration_WebserviceConfigurationManager');
			self::$configurationManager->setContentObject($this->cObj);
		}
		self::$extbaseFrameworkConfiguration = self::$configurationManager->getFrameworkConfiguration($configuration);
	}

}

?>