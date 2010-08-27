<?php

// Replace by Fluid Templates

/**
 * WSDL document generator.
 *
 * @author Renan de Lima <renandelima@gmail.com>
 * @version 0.4
 */
class Tx_ExtbaseWebservices_Helper_Wsdl extends DOMDocument
{
	/**
	 * @var string
	 */
	const BINDING = "http://schemas.xmlsoap.org/soap/http";

	/**
	 * @var string
	 */
	const NS_SOAP_ENC = "http://schemas.xmlsoap.org/soap/encoding/";

	/**
	 * @var string
	 */
	const NS_SOAP_ENV = "http://schemas.xmlsoap.org/wsdl/soap/";

	/**
	 * @var string
	 */
	const NS_WSDL = "http://schemas.xmlsoap.org/wsdl/";

	/**
	 * @var string
	 */
	const NS_XML = "http://www.w3.org/2000/xmlns/";

	/**
	 * @var string
	 */
	const NS_XSD = "http://www.w3.org/2001/XMLSchema";

	/**
	 * @var DOMElement
	 */
	protected $oBinding = null;

	/**
	 * @var ReflectionClass
	 */
	protected $classReflection = "";

	/**
	 * @var DOMElement
	 */
	protected $oDefinitions = null;

	/**
	 * @var DOMElement
	 */
	protected $oPortType = null;

	/**
	 * @var DOMElement
	 */
	protected $oSchema = null;

	/**
	 * @var string
	 */
	protected $sTns = "";

	/**
	 * @var string
	 */
	protected $sUrl = "";

	/**
	 * @var Tx_ExtbaseWebservices_MVC_Web_WebserviceRequest
	 */
	protected $request;

	/**
	 * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct('1.0', 'UTF-8');
	}

	/**
	 * Injects the uri builder
	 *
	 * @param Tx_Extbase_Reflection_ClassReflection $classReflection
	 * @return void
	 */
	public function injectClassReflection($classReflection) {
		$this->classReflection = $classReflection;
	}

	/**
	 * Injects the uri builder
	 *
	 * @param Tx_ExtbaseWebservices_MVC_Web_WebserviceRequest $request
	 * @return void
	 */
	public function injectRequest(Tx_ExtbaseWebservices_MVC_Web_WebserviceRequest $request) {
		$this->request = $request;
	}

	/**
	 * Injects the uri builder
	 *
	 * @param Tx_Extbase_MVC_Web_Routing_UriBuilder $uriBuilder
	 * @return void
	 */
	public function injectUriBuilder(Tx_Extbase_MVC_Web_Routing_UriBuilder $uriBuilder) {
		$this->uriBuilder = $uriBuilder;
	}

	/**
	 * Create the WSDL definitions.
	 *
	 * @return void
	 */
	public function getContent()
	{
		$this->formatOutput = true;

		$this->sTns = htmlspecialchars($this->request->getRequestURI());
		$this->sUrl = htmlspecialchars($this->request->getRequestURI());
		# create root, schema type, port type and binding tags
		$this->startDocument();
		# walk operations
		foreach ( $this->classReflection->getMethods() as $oMethod )
		{
			# check if method is allowed
			if (
				$oMethod->isPublic() == true && // it must be public and...
				$oMethod->isStatic() == false && // non static
				$oMethod->isConstructor() == false && // non constructor
				($oMethod->isTaggedWith('binding.soap') || $oMethod->isTaggedWith('binding.rest') || $oMethod->isTaggedWith('binding.xmlrpc'))
			)
			{
				# port type operation
				$this->createPortTypeOperation( $oMethod );
				# binding operation
				$this->createBindingOperation( $oMethod );
				# message
				$this->createMessage( $oMethod );
			}
		}
		# append port type and binding
		$this->oDefinitions->appendChild( $this->oPortType );
		$this->oDefinitions->appendChild( $this->oBinding );
		# service
		$this->createService();
		return $this->saveXML();
	}

	/**
	 * Create array type once. It doesn't create a type, you have to call
	 * {@link Wsdl::_createType()} to this. Returns the array type name.
	 *
	 * @param string
	 * @return string
	 */
	protected function createArrayType( $sType )
	{
		# check if it was created
		static $aCache = array();
		$sName = $sType . "Array";
		if ( array_key_exists( $sType, $aCache ) == false )
		{
			# mark it as created to avoid twice creation
			$aCache[$sType] = true;
			# create tags
			$oType = $this->createElementNS( self::NS_XSD, "complexType" );
			$this->oSchema->appendChild( $oType );
			$oContent = $this->createElementNS( self::NS_XSD, "complexContent" );
			$oType->appendChild( $oContent );
			$oRestriction = $this->createElementNS( self::NS_XSD, "restriction" );
			$oContent->appendChild( $oRestriction );
			$oAttribute = $this->createElementNS( self::NS_XSD, "attribute" );
			$oRestriction->appendChild( $oAttribute );
			# configure tags
			$oType->setAttribute( "name", $sName );
			$oRestriction->setAttribute( "base", "soap-enc:Array" );
			$oAttribute->setAttribute( "ref", "soap-enc:arrayType" );
			$oAttribute->setAttributeNS( self::NS_WSDL, "arrayType", "tns:" . $sType . "[]" );
		}
		return $sName;
	}

	/**
	 * Create a binding operation tag.
	 *
	 * @param ReflectionMethod
	 * @return void
	 */
	protected function createBindingOperation( ReflectionMethod $oMethod )
	{
		if(substr($oMethod->name,-6,6) == 'Action') {
			$methodName = substr($oMethod->name,0,-6);
		} else {
			$methodName = $oMethod->name;
		}
		$oOperation = $this->createElementNS( self::NS_WSDL, "operation" );
		$oOperation->setAttribute( "name", $methodName );
		$this->oBinding->appendChild( $oOperation );
		# binding operation soap
		$oOperationSoap = $this->createElementNS( self::NS_SOAP_ENV, "operation" );
		$this->uriBuilder->setAddQueryString(true);
		$sActionUrl = $this->uriBuilder->setFormat('soap')->setCreateAbsoluteUri(true)->uriFor($methodName);
		$oOperationSoap->setAttribute( "soapAction", $sActionUrl );
		$oOperationSoap->setAttribute( "style", "rpc" );
		$oOperation->appendChild( $oOperationSoap );
		# binding input and output
		foreach ( array( "input", "output" ) as $sTag )
		{
			$oBindingTag = $this->createElementNS( self::NS_WSDL, $sTag );
			$oOperation->appendChild( $oBindingTag );
			$oBody = $this->createElementNS( self::NS_SOAP_ENV, "body" );
			$oBody->setAttribute( "use", "encoded" );
			$oBody->setAttribute( "encodingStyle", self::NS_SOAP_ENC );
			$oBindingTag->appendChild( $oBody );
		}
	}

	/**
	 * Create a complex type once. It doesn't create a type, you have to call
	 * {@link Wsdl::_createType()} to this.
	 *
	 * @return void
	 */
	protected function createComplexType( $sClass )
	{
		# check if it was created
		static $aCache = array();
		if ( array_key_exists( $sClass, $aCache ) == false )
		{
			# mark it as created to avoid twice creation and recursion between complex types
			$aCache[$sClass] = true;
			# start type creation
			$oComplex = $this->createElementNS( self::NS_XSD, "complexType" );
			$this->oSchema->appendChild( $oComplex );
			$oComplex->setAttribute( "name", $sClass );
			$oAll = $this->createElementNS( self::NS_XSD, "all" );
			$oComplex->appendChild( $oAll );
			# create attributes
			$oReflection = new ReflectionClass( $sClass );
			$aProperty = $oReflection->getProperties( ReflectionProperty::IS_PUBLIC );
			foreach ( $oReflection->getProperties() as $oProperty )
			{
				# check if property is allowed
				if (
					$oProperty->isPublic() == true && // it must be public and...
					$oProperty->isStatic() == false // non static
				)
				{
					# create type for each element
					$sComment = $oProperty->getDocComment();
					$sType = reset( $this->getTagComment( $sComment, "var" ) );
					$sPropertyTypeId = $this->createType( $sType );
					# create element of property
					$oElement = $this->createElementNS( self::NS_XSD, "element" );
					$oAll->appendChild( $oElement );
					$oElement->setAttribute( "name", $oProperty->name );
					$oElement->setAttribute( "type", $sPropertyTypeId );
					$oElement->setAttribute( "minOccurs", 0 );
					$oElement->setAttribute( "maxOccurs", 1 );
				}
			}
		}
	}

	/**
	 * Create a message with operation arguments and return.
	 *
	 * @param ReflectionMethod
	 * @return void
	 */
	protected function createMessage( ReflectionMethod $oMethod )
	{
		if(substr($oMethod->name,-6,6) == 'Action') {
			$methodName = substr($oMethod->name,0,-6);
		} else {
			$methodName = $oMethod->name;
		}
		$sComment = $oMethod->getDocComment();
		# message input
		$oInput = $this->createElementNS( self::NS_WSDL, "message" );
		$oInput->setAttribute( "name", $methodName . "Request" );
		$this->oDefinitions->appendChild( $oInput );
		# input part
		$aType = $this->getTagComment( $sComment, "param" );
		$aParameter = $oMethod->getParameters();
		if ( count( $aType ) != count( $aParameter ) )
		{
			throw new Exception( "Declared and documented arguments do not match in {$this->classReflection->getName()}::{$oMethod->getName()}()." );
		}
		foreach ( $aType as $iKey => $sType )
		{
			$oPart = $this->createElementNS( self::NS_WSDL, "part" );
			$oPart->setAttribute( "name", $aParameter[$iKey]->name );
			$oPart->setAttribute( "type", $this->createType( $sType ) );
			$oInput->appendChild( $oPart );
		}
		# message output
		$oOutput = $this->createElementNS( self::NS_WSDL, "message" );
		$oOutput->setAttribute( "name", $methodName . "Response" );
		$this->oDefinitions->appendChild( $oOutput );
		# output part
		$sType = (string) reset( $this->getTagComment( $sComment, "return" ) );
		if ( $sType != "void" && $sType != "" )
		{
			$oPart = $this->createElementNS( self::NS_WSDL, "part" );
			$oPart->setAttribute( "name", $methodName . "Return" );
			$oPart->setAttribute( "type", $this->createType( $sType ) );
			$oOutput->appendChild( $oPart );
		}
	}

	/**
	 * Create a port type operation tag.
	 *
	 * @param ReflectionMethod
	 * @return void
	 */
	protected function createPortTypeOperation( ReflectionMethod $oMethod )
	{
		if(substr($oMethod->name,-6,6) == 'Action') {
			$methodName = substr($oMethod->name,0,-6);
		} else {
			$methodName = $oMethod->name;
		}
		$oOperation = $this->createElementNS( self::NS_WSDL, "operation" );
		$oOperation->setAttribute( "name", $methodName );
		$this->oPortType->appendChild( $oOperation );
		# documentation
		$sDoc = $this->getDocComment( $oMethod->getDocComment() );
		$oDoc = $this->createElementNS( self::NS_WSDL, "documentation", $sDoc );
		$oOperation->appendChild( $oDoc );
		# port type operation input
		$oInput = $this->createElementNS( self::NS_WSDL, "input" );
		$oInput->setAttribute( "message", "tns:" . $methodName . "Request" );
		$oOperation->appendChild( $oInput );
		# port type operation output
		$oOutput = $this->createElementNS( self::NS_WSDL, "output" );
		$oOutput->setAttribute( "message", "tns:" . $methodName . "Response" );
		$oOperation->appendChild( $oOutput );
	}

	/**
	 * Create service tag.
	 *
	 * @return void
	 */
	protected function createService()
	{
		$oService = $this->createElementNS( self::NS_WSDL, "service" );
		$oService->setAttribute( "name", $this->classReflection->name );
		$this->oDefinitions->appendChild( $oService );
		# documentation
		$sDoc = $this->getDocComment( $this->classReflection->getDocComment() );
		$oDoc = $this->createElementNS( self::NS_WSDL, "documentation", $sDoc );
		$oService->appendChild( $oDoc );
		# port
		$oPort = $this->createElementNS( self::NS_WSDL, "port" );
		$oPort->setAttribute( "name", $this->classReflection->name . "Port" );
		$oPort->setAttribute( "binding", "tns:" . $this->classReflection->name . "Binding" );
		$oService->appendChild( $oPort );
		# address
		$oAddress = $this->createElementNS( self::NS_SOAP_ENV, "address" );
		$oAddress->setAttribute( "location", $this->sUrl );
		$oPort->appendChild( $oAddress );
	}

	/**
	 * Create a type in document. Receive the raw type name as it was on
	 * programmer's documentation. It returns wsdl name type.
	 *
	 * @param string
	 * @return string
	 */
	protected function createType( $sType )
	{
		# check if is array
		$sType = trim( (string) $sType );
		if ( $sType == "" )
		{
			throw new Exception( "Invalid type." );
		}
		# check if it's array and its depth
		$iArrayDepth = 0;
		while ( substr( $sType, -2 ) == "[]" )
		{
			$iArrayDepth++;
			$sType = substr( $sType, 0, -2 );
		}
		# the real type name is here
		$sRawType = $sType;
		# create the arrays concerned depth
		if ( $iArrayDepth > 0 )
		{
			$sNameSpace = "tns";
			for ( ; $iArrayDepth > 0; $iArrayDepth-- )
			{
				$sType = $this->createArrayType( $sType );
			}
		}
		else
		{
			# translate and check if it's complex type
			list( $sNameSpace, $sType ) = $this->translateType( $sRawType );
			# create complex type
			if ( $sNameSpace == "tns" )
			{
				$this->createComplexType( $sRawType );
			}
		}
		# wsdl type name
		return $sNameSpace . ":" . $sType;
	}

	/**
	 * Fetch documentation in a comment.
	 *
	 * @param string
	 * @return string
	 */
	protected function getDocComment( $sComment )
	{
		$sValue = "";
		foreach ( explode( "\n",  $sComment ) as $sLine )
		{
			$sLine = trim( $sLine, " *\t\r/" );
			if ( strlen( $sLine ) > 0 && $sLine{0} == "@" )
			{
				break;
			}
			$sValue .= " " . $sLine;
		}
		return trim( $sValue );
	}

	/**
	 * Fetch tag's values from a comment.
	 *
	 * @param string
	 * @param string
	 * @return string[]
	 */
	protected function getTagComment( $sComment, $sTagName )
	{
		$aValue = array();
		foreach ( explode( "\n",  $sComment ) as $sLine )
		{
			$sPattern = "/^\*\s+@(.[^\s]+)\s+(.[^\s]+)/";
			$sText = trim( $sLine );
			$aMatch = array();
			preg_match( $sPattern, $sText, $aMatch );
			if ( count( $aMatch ) > 2 && $aMatch[1] == $sTagName )
			{
				array_push( $aValue, $aMatch[2] );
			}
		}
		return $aValue;
	}

	/**
	 * Create basics tags. Definitions (root), schema (contain types), port type
	 * and binding.
	 *
	 * @return void
	 */
	protected function startDocument()
	{
		# root tag
		$this->oDefinitions = $this->createElementNS( self::NS_WSDL, "wsdl:definitions" );
//		$this->oDefinitions->setAttributeNS( self::NS_XML, "xmlns:soap-enc", self::NS_SOAP_ENC );
		$this->oDefinitions->setAttributeNS( self::NS_XML, "xmlns:soap", self::NS_SOAP_ENV );
		$this->oDefinitions->setAttributeNS( self::NS_XML, "xmlns:tns", $this->sTns );
		$this->oDefinitions->setAttributeNS( self::NS_XML, "xmlns:wsdl", self::NS_WSDL );
		$this->oDefinitions->setAttributeNS( self::NS_XML, "xmlns:xsd", self::NS_XSD );
		$this->oDefinitions->setAttribute( "targetNamespace", $this->sTns );
		$this->appendChild( $this->oDefinitions );
		# types
		$oTypes = $this->createElementNS( self::NS_WSDL, "types" );
		$this->oDefinitions->appendChild( $oTypes );
		# schema
		$this->oSchema = $this->createElementNS( self::NS_XSD, "schema" );
		$this->oSchema->setAttribute( "targetNamespace", $this->sTns );
		$oTypes->appendChild( $this->oSchema );
		# port type
		# it must be append in root after methods walking
		# see WSDLDocument::discovery()
		$this->oPortType = $this->createElementNS( self::NS_WSDL, "portType" );
		$this->oPortType->setAttribute( "name", $this->classReflection->name . "PortType" );
		# binding
		# it must be append in root after methods walking
		# see WSDLDocument::discovery()
		$this->oBinding = $this->createElementNS( self::NS_WSDL, "binding" );
		$this->oBinding->setAttribute( "name", $this->classReflection->name . "Binding" );
		$this->oBinding->setAttribute( "type", "tns:" . $this->classReflection->name . "PortType" );
		# soap binding
		$oBindingSoap = $this->createElementNS( self::NS_SOAP_ENV, "binding" );
		$oBindingSoap->setAttribute( "style", "rpc" );
		$oBindingSoap->setAttribute( "transport", self::BINDING );
		$this->oBinding->appendChild( $oBindingSoap );
	}

	/**
	 * Get the namespace (from where) and the real name of the type.
	 *
	 * @param string
	 * @return string[]
	 */
	protected function translateType( $sType )
	{
		switch ( $sType )
		{
			case "array":
			case "struct":
				return array( "soap-enc", "Array" );
			case "boolean":
			case "bool":
				return array( "xsd", "boolean" );
			case "float":
			case "real":
				return array( "xsd", "float" );
			case "integer":
			case "int":
				return array( "xsd", "int" );
			case "string":
			case "str":
				return array( "xsd", "string" );
			default:
				return array( "tns", $sType );
		}
	}
}

?>