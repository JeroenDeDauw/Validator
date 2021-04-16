class ValidatorHooks {
	public static function onRegistration() {
		if ( !class_exists( ParamProcessor\Processor::class ) ) {
			throw new Exception( 'Validator depends on the ParamProcessor library.' );
		}

		define( 'Validator_VERSION', '2.2.5' );
		define( 'ParamProcessor_VERSION', Validator_VERSION ); // @deprecated since 1.0

		global $wgDataValues, $wgParamDefinitions;

		$wgDataValues['mediawikititle'] = ParamProcessor\MediaWikiTitleValue::class;

		$wgParamDefinitions['title'] = [
			'string-parser' => ParamProcessor\TitleParser::class,
			'validator' => ValueValidators\TitleValidator::class,
		];
	}

	/**
	 * Hook to add PHPUnit test cases.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
	 *
	 * @since 1.0
	 *
	 * @param array $files
	 * @return bool
	 */
	public static function onUnitTestsList( array &$files ) {
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
	}
}
