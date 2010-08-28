<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Thomas Maroschik <tmaroschik@dfau.de>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
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
 * Represents a web request.
 *
 * @package Extbase
 * @subpackage MVC\Webservice
 * @version $ID:$
 *
 * @scope prototype
 * @api
 */
class Tx_ExtbaseWebservices_MVC_Web_WebserviceRequest extends Tx_Extbase_MVC_Web_Request {

	/**
	 * @var string The requested representation format
	 */
	protected $format = 'wsdl';

	/**
	 * @var int The Requested typeNum
	 */
	protected $typeNum;

	/**
	 * @var string redirectUniqueId
	 */
	 protected $redirectUniqueId;

	/**
	 * @var string redirectStatus
	 */
	 protected $redirectStatus;

	/**
	 * @var string uniqueId
	 */
	 protected $uniqueId;

	/**
	 * @var string httpHost
	 */
	 protected $httpHost;

	/**
	 * @var string httpUserAgent
	 */
	 protected $httpUserAgent;

	/**
	 * @var string httpAccept
	 */
	 protected $httpAccept;

	/**
	 * @var string httpAcceptLanguage
	 */
	 protected $httpAcceptLanguage;

	/**
	 * @var string httpAcceptEncoding
	 */
	 protected $httpAcceptEncoding;

	/**
	 * @var string httpAcceptCharset
	 */
	 protected $httpAcceptCharset;

	/**
	 * @var string httpKeepAlive
	 */
	 protected $httpKeepAlive;

	/**
	 * @var string httpConnection
	 */
	 protected $httpConnection;

	 /**
	 * @var string contentType
	 */
	 protected $contentType;

	 /**
	 * @var string httpSoapaction
	 */
	 protected $httpSoapaction;

	 /**
	 * @var string contentLength
	 */
	 protected $contentLength;

	/**
	 * @var string httpCookie
	 */
	 protected $httpCookie;

	/**
	 * @var string httpPragma
	 */
	 protected $httpPragma;

	/**
	 * @var string httpCacheControl
	 */
	 protected $httpCacheControl;

	/**
	 * @var string path
	 */
	 protected $path;

	/**
	 * @var string serverSignature
	 */
	 protected $serverSignature;

	/**
	 * @var string serverSoftware
	 */
	 protected $serverSoftware;

	/**
	 * @var string serverName
	 */
	 protected $serverName;

	/**
	 * @var string serverAddr
	 */
	 protected $serverAddr;

	/**
	 * @var string serverPort
	 */
	 protected $serverPort;

	/**
	 * @var string remoteAddr
	 */
	 protected $remoteAddr;

	/**
	 * @var string documentRoot
	 */
	 protected $documentRoot;

	/**
	 * @var string serverAdmin
	 */
	 protected $serverAdmin;

	/**
	 * @var string scriptFilename
	 */
	 protected $scriptFilename;

	/**
	 * @var string remotePort
	 */
	 protected $remotePort;

	/**
	 * @var string redirectQueryString
	 */
	 protected $redirectQueryString;

	/**
	 * @var string redirectUrl
	 */
	 protected $redirectUrl;

	/**
	 * @var string gatewayInterface
	 */
	 protected $gatewayInterface;

	/**
	 * @var string serverProtocol
	 */
	 protected $serverProtocol;

	/**
	 * @var string requestMethod
	 */
	 protected $requestMethod;

	/**
	 * @var string queryString
	 */
	 protected $queryString;

	/**
	 * @var string requestUri
	 */
	 protected $requestUri;

	/**
	 * @var string scriptName
	 */
	 protected $scriptName;

	/**
	 * @var string phpSelf
	 */
	 protected $phpSelf;

	/**
	 * @var string requestTime
	 */
	 protected $requestTime;

	/**
	 * Sets typeNum
	 *
	 * @param integer $typeNum
	 * @return void
	 */
	public function setTypeNum($typeNum) {
		$this->typeNum = $typeNum;
	}

	/**
	 * Returns typeNum
	 *
	 * @return integer
	 */
	public function getTypeNum() {
		return $this->typeNum;
	}

	/**
	 * Sets redirectUniqueId
	 *
	 * @param string
	 * @return void
	 */
	public function setRedirectUniqueId($redirectUniqueId) {
		$this->redirectUniqueId = $redirectUniqueId;
	}

	/**
	 * Returns redirectUniqueId
	 *
	 * @return string
	 */
	public function getRedirectUniqueId() {
		return $this->redirectUniqueId;
	}

	/**
	 * Sets redirectStatus
	 *
	 * @param string
	 * @return void
	 */
	public function setRedirectStatus($redirectStatus) {
		$this->redirectStatus = $redirectStatus;
	}

	/**
	 * Returns redirectStatus
	 *
	 * @return string
	 */
	public function getRedirectStatus() {
		return $this->redirectStatus;
	}

	/**
	 * Sets uniqueId
	 *
	 * @param string
	 * @return void
	 */
	public function setUniqueId($uniqueId) {
		$this->uniqueId = $uniqueId;
	}

	/**
	 * Returns uniqueId
	 *
	 * @return string
	 */
	public function getUniqueId() {
		return $this->uniqueId;
	}

	/**
	 * Sets httpHost
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpHost($httpHost) {
		$this->httpHost = $httpHost;
	}

	/**
	 * Returns httpHost
	 *
	 * @return string
	 */
	public function getHttpHost() {
		return $this->httpHost;
	}

	/**
	 * Sets httpUserAgent
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpUserAgent($httpUserAgent) {
		$this->httpUserAgent = $httpUserAgent;
	}

	/**
	 * Returns httpUserAgent
	 *
	 * @return string
	 */
	public function getHttpUserAgent() {
		return $this->httpUserAgent;
	}

	/**
	 * Sets httpAccept
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpAccept($httpAccept) {
		$this->httpAccept = $httpAccept;
	}

	/**
	 * Returns httpAccept
	 *
	 * @return string
	 */
	public function getHttpAccept() {
		return $this->httpAccept;
	}

	/**
	 * Sets httpAcceptLanguage
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpAcceptLanguage($httpAcceptLanguage) {
		$this->httpAcceptLanguage = $httpAcceptLanguage;
	}

	/**
	 * Returns httpAcceptLanguage
	 *
	 * @return string
	 */
	public function getHttpAcceptLanguage() {
		return $this->httpAcceptLanguage;
	}

	/**
	 * Sets httpAcceptEncoding
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpAcceptEncoding($httpAcceptEncoding) {
		$this->httpAcceptEncoding = $httpAcceptEncoding;
	}

	/**
	 * Returns httpAcceptEncoding
	 *
	 * @return string
	 */
	public function getHttpAcceptEncoding() {
		return $this->httpAcceptEncoding;
	}

	/**
	 * Sets httpAcceptCharset
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpAcceptCharset($httpAcceptCharset) {
		$this->httpAcceptCharset = $httpAcceptCharset;
	}

	/**
	 * Returns httpAcceptCharset
	 *
	 * @return string
	 */
	public function getHttpAcceptCharset() {
		return $this->httpAcceptCharset;
	}

	/**
	 * Sets httpKeepAlive
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpKeepAlive($httpKeepAlive) {
		$this->httpKeepAlive = $httpKeepAlive;
	}

	/**
	 * Returns httpKeepAlive
	 *
	 * @return string
	 */
	public function getHttpKeepAlive() {
		return $this->httpKeepAlive;
	}

	/**
	 * Sets httpConnection
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpConnection($httpConnection) {
		$this->httpConnection = $httpConnection;
	}

	/**
	 * Returns httpConnection
	 *
	 * @return string
	 */
	public function getHttpConnection() {
		return $this->httpConnection;
	}

	/**
	 * Sets contentType
	 *
	 * @param string $contentType
	 * @return void
	 */
	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}

	/**
	 * Returns contentType
	 *
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}

	/**
	 * Sets httpSoapaction
	 *
	 * @param string $httpSoapaction
	 * @return void
	 */
	public function setHttpSoapaction($httpSoapaction) {
		$this->httpSoapaction = $httpSoapaction;
	}

	/**
	 * Returns httpSoapaction
	 *
	 * @return string
	 */
	public function getHttpSoapaction() {
		return $this->httpSoapaction;
	}

	/**
	 * Sets contentLength
	 *
	 * @param string $contentLength
	 * @return void
	 */
	public function setContentLength($contentLength) {
		$this->contentLength = $contentLength;
	}

	/**
	 * Returns contentLength
	 *
	 * @return string
	 */
	public function getContentLength() {
		return $this->contentLength;
	}

	/**
	 * Sets httpCookie
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpCookie($httpCookie) {
		$this->httpCookie = $httpCookie;
	}

	/**
	 * Returns httpCookie
	 *
	 * @return string
	 */
	public function getHttpCookie() {
		return $this->httpCookie;
	}

	/**
	 * Sets httpPragma
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpPragma($httpPragma) {
		$this->httpPragma = $httpPragma;
	}

	/**
	 * Returns httpPragma
	 *
	 * @return string
	 */
	public function getHttpPragma() {
		return $this->httpPragma;
	}

	/**
	 * Sets httpCacheControl
	 *
	 * @param string
	 * @return void
	 */
	public function setHttpCacheControl($httpCacheControl) {
		$this->httpCacheControl = $httpCacheControl;
	}

	/**
	 * Returns httpCacheControl
	 *
	 * @return string
	 */
	public function getHttpCacheControl() {
		return $this->httpCacheControl;
	}

	/**
	 * Sets path
	 *
	 * @param string
	 * @return void
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Returns path
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Sets serverSignature
	 *
	 * @param string
	 * @return void
	 */
	public function setServerSignature($serverSignature) {
		$this->serverSignature = $serverSignature;
	}

	/**
	 * Returns serverSignature
	 *
	 * @return string
	 */
	public function getServerSignature() {
		return $this->serverSignature;
	}

	/**
	 * Sets serverSoftware
	 *
	 * @param string
	 * @return void
	 */
	public function setServerSoftware($serverSoftware) {
		$this->serverSoftware = $serverSoftware;
	}

	/**
	 * Returns serverSoftware
	 *
	 * @return string
	 */
	public function getServerSoftware() {
		return $this->serverSoftware;
	}

	/**
	 * Sets serverName
	 *
	 * @param string
	 * @return void
	 */
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}

	/**
	 * Returns serverName
	 *
	 * @return string
	 */
	public function getServerName() {
		return $this->serverName;
	}

	/**
	 * Sets serverAddr
	 *
	 * @param string
	 * @return void
	 */
	public function setServerAddr($serverAddr) {
		$this->serverAddr = $serverAddr;
	}

	/**
	 * Returns serverAddr
	 *
	 * @return string
	 */
	public function getServerAddr() {
		return $this->serverAddr;
	}

	/**
	 * Sets serverPort
	 *
	 * @param string
	 * @return void
	 */
	public function setServerPort($serverPort) {
		$this->serverPort = $serverPort;
	}

	/**
	 * Returns serverPort
	 *
	 * @return string
	 */
	public function getServerPort() {
		return $this->serverPort;
	}

	/**
	 * Sets remoteAddr
	 *
	 * @param string
	 * @return void
	 */
	public function setRemoteAddr($remoteAddr) {
		$this->remoteAddr = $remoteAddr;
	}

	/**
	 * Returns remoteAddr
	 *
	 * @return string
	 */
	public function getRemoteAddr() {
		return $this->remoteAddr;
	}

	/**
	 * Sets documentRoot
	 *
	 * @param string
	 * @return void
	 */
	public function setDocumentRoot($documentRoot) {
		$this->documentRoot = $documentRoot;
	}

	/**
	 * Returns documentRoot
	 *
	 * @return string
	 */
	public function getDocumentRoot() {
		return $this->documentRoot;
	}

	/**
	 * Sets serverAdmin
	 *
	 * @param string
	 * @return void
	 */
	public function setServerAdmin($serverAdmin) {
		$this->serverAdmin = $serverAdmin;
	}

	/**
	 * Returns serverAdmin
	 *
	 * @return string
	 */
	public function getServerAdmin() {
		return $this->serverAdmin;
	}

	/**
	 * Sets scriptFilename
	 *
	 * @param string
	 * @return void
	 */
	public function setScriptFilename($scriptFilename) {
		$this->scriptFilename = $scriptFilename;
	}

	/**
	 * Returns scriptFilename
	 *
	 * @return string
	 */
	public function getScriptFilename() {
		return $this->scriptFilename;
	}

	/**
	 * Sets remotePort
	 *
	 * @param string
	 * @return void
	 */
	public function setRemotePort($remotePort) {
		$this->remotePort = $remotePort;
	}

	/**
	 * Returns remotePort
	 *
	 * @return string
	 */
	public function getRemotePort() {
		return $this->remotePort;
	}

	/**
	 * Sets redirectQueryString
	 *
	 * @param string
	 * @return void
	 */
	public function setRedirectQueryString($redirectQueryString) {
		$this->redirectQueryString = $redirectQueryString;
	}

	/**
	 * Returns redirectQueryString
	 *
	 * @return string
	 */
	public function getRedirectQueryString() {
		return $this->redirectQueryString;
	}

	/**
	 * Sets redirectUrl
	 *
	 * @param string
	 * @return void
	 */
	public function setRedirectUrl($redirectUrl) {
		$this->redirectUrl = $redirectUrl;
	}

	/**
	 * Returns redirectUrl
	 *
	 * @return string
	 */
	public function getRedirectUrl() {
		return $this->redirectUrl;
	}

	/**
	 * Sets gatewayInterface
	 *
	 * @param string
	 * @return void
	 */
	public function setGatewayInterface($gatewayInterface) {
		$this->gatewayInterface = $gatewayInterface;
	}

	/**
	 * Returns gatewayInterface
	 *
	 * @return string
	 */
	public function getGatewayInterface() {
		return $this->gatewayInterface;
	}

	/**
	 * Sets serverProtocol
	 *
	 * @param string
	 * @return void
	 */
	public function setServerProtocol($serverProtocol) {
		$this->serverProtocol = $serverProtocol;
	}

	/**
	 * Returns serverProtocol
	 *
	 * @return string
	 */
	public function getServerProtocol() {
		return $this->serverProtocol;
	}

	/**
	 * Sets requestMethod
	 *
	 * @param string
	 * @return void
	 */
	public function setRequestMethod($requestMethod) {
		$this->requestMethod = $requestMethod;
	}

	/**
	 * Returns requestMethod
	 *
	 * @return string
	 */
	public function getRequestMethod() {
		return $this->requestMethod;
	}

	/**
	 * Sets queryString
	 *
	 * @param string
	 * @return void
	 */
	public function setQueryString($queryString) {
		$this->queryString = $queryString;
	}

	/**
	 * Returns queryString
	 *
	 * @return string
	 */
	public function getQueryString() {
		return $this->queryString;
	}

	/**
	 * Sets requestUri
	 *
	 * @param string
	 * @return void
	 */
	public function setRequestUri($requestUri) {
		$this->requestUri = $requestUri;
	}

	/**
	 * Returns requestUri
	 *
	 * @return string
	 */
	public function getRequestUri() {
		return $this->requestUri;
	}

	/**
	 * Sets scriptName
	 *
	 * @param string
	 * @return void
	 */
	public function setScriptName($scriptName) {
		$this->scriptName = $scriptName;
	}

	/**
	 * Returns scriptName
	 *
	 * @return string
	 */
	public function getScriptName() {
		return $this->scriptName;
	}

	/**
	 * Sets phpSelf
	 *
	 * @param string
	 * @return void
	 */
	public function setPhpSelf($phpSelf) {
		$this->phpSelf = $phpSelf;
	}

	/**
	 * Returns phpSelf
	 *
	 * @return string
	 */
	public function getPhpSelf() {
		return $this->phpSelf;
	}

	/**
	 * Sets requestTime
	 *
	 * @param string
	 * @return void
	 */
	public function setRequestTime($requestTime) {
		$this->requestTime = $requestTime;
	}

	/**
	 * Returns requestTime
	 *
	 * @return string
	 */
	public function getRequestTime() {
		return $this->requestTime;
	}

}
?>