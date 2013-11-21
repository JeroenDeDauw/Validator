<?php

/**
 * Initialization file for the Validator MediaWiki extension.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'ParamProcessor_VERSION' ) ) {
	// Do not initialize more then once.
	return 1;
}

define( 'Validator_VERSION', '1.0.0.1' );
define( 'ParamProcessor_VERSION', Validator_VERSION ); // @deprecated since 1.0

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $GLOBALS['wgVersion'], '1.16c', '<' ) ) {
	die( '<b>Error:</b> This version of Validator requires MediaWiki 1.16 or above.' );
}

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

if ( !class_exists( 'ParamProcessor\Processor' ) ) {
	throw new Exception( 'Validator depends on the ParamProcessor library.' );
}

global $wgExtensionMessagesFiles, $wgExtensionCredits, $wgAutoloadClasses, $wgHooks, $wgDataValues;

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = __DIR__ . '/Validator.i18n.php';
$wgExtensionMessagesFiles['ValidatorMagic'] = __DIR__ . '/Validator.i18n.magic.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator',
	'version' => Validator_VERSION,
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]'
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Validator',
	'descriptionmsg' => 'validator-desc',
);

spl_autoload_register( function ( $className ) {
	$className = ltrim( $className, '\\' );
	$fileName = '';
	$namespace = '';

	if ( $lastNsPos = strripos( $className, '\\' ) ) {
		$namespace = substr( $className, 0, $lastNsPos );
		$className = substr( $className, $lastNsPos + 1 );
		$fileName  = str_replace( '\\', '/', $namespace ) . '/';
	}

	$fileName .= str_replace( '_', '/', $className ) . '.php';

	$namespaceSegments = explode( '\\', $namespace );

	if ( $namespaceSegments[0] === 'ParamProcessor' ) {
		$inTestNamespace = count( $namespaceSegments ) > 1 && $namespaceSegments[1] === 'Tests';

		if ( !$inTestNamespace ) {
			$pathParts = explode( '/', $fileName );
			array_shift( $pathParts );
			$fileName = implode( '/', $pathParts );

			if ( is_readable( __DIR__ . '/src/ParamProcessor/' . $fileName ) ) {
				require_once __DIR__ . '/src/ParamProcessor/' . $fileName;
			}
		}
	}
} );

class_alias( 'ParamProcessor\ParamDefinitionFactory', 'ParamDefinitionFactory' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\ParamDefinition', 'ParamDefinition' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\StringParam', 'StringParam' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\StringParam', 'ParamProcessor\StringParam' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\IParamDefinition', 'IParamDefinition' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\DimensionParam', 'DimensionParam' ); // Softly deprecated since 1.0, removal in 1.5

class_alias( 'ParamProcessor\ProcessingError', 'ProcessingError' ); // Deprecated since 1.0, removal in 1.2
class_alias( 'ParamProcessor\Options', 'ValidatorOptions' ); // Deprecated since 1.0, removal in 1.2
class_alias( 'ParamProcessor\IParam', 'IParam' ); // Deprecated since 1.0, removal in 1.2

/**
 * @deprecated since 1.0, removal in 1.3
 */
class Validator extends ParamProcessor\Processor {

	public function __construct() {
		parent::__construct( new ParamProcessor\Options() );
	}

}

// utils
$wgAutoloadClasses['ParserHook']				 	= __DIR__ . '/src/legacy/ParserHook.php';
$wgAutoloadClasses['ValidatorDescribe']		  		= __DIR__ . '/src/legacy/Describe.php';
$wgAutoloadClasses['ValidatorListErrors']			= __DIR__ . '/src/legacy/ListErrors.php';

// Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';

// Registration of the describe parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorDescribe::staticInit';

/**
 * Hook to add PHPUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
 *
 * @since 1.0
 *
 * @param array $files
 *
 * @return boolean
 */
$wgHooks['UnitTestsList'][]	= function( array &$files ) {
	// @codeCoverageIgnoreStart
	$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/tests/phpunit/' );

	/**
	 * @var SplFileInfo $fileInfo
	 */
	foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
		if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
			$files[] = $fileInfo->getPathname();
		}
	}

	return true;
	// @codeCoverageIgnoreEnd
};

$wgDataValues['mediawikititle'] = 'ParamProcessor\MediaWikiTitleValue';

$GLOBALS['wgParamDefinitions']['title'] = array(
	'string-parser' => '\ParamProcessor\TitleParser',
	'validator' => '\ValueValidators\TitleValidator',
);