<?php

namespace ParamProcessor\Tests\Definitions;

use ParamProcessor\IParamDefinition;
use ParamProcessor\Options;
use ParamProcessor\PackagePrivate\Param;
use ParamProcessor\ParamDefinition;
use ParamProcessor\ParamDefinitionFactory;
use PHPUnit\Framework\TestCase;

/**
 * @group Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TitleParamTest extends TestCase {

	public function definitionProvider() {
		$definitions = $this->getDefinitions();

		foreach ( $definitions as &$definition ) {
			$definition['type'] = $this->getType();
		}

		return $definitions;
	}

	public function getEmptyInstance() {
		return ParamDefinitionFactory::singleton()->newDefinitionFromArray( [
			'name' => 'empty',
			'message' => 'test-empty',
			'type' => $this->getType(),
		] );
	}

	public function instanceProvider() {
		$definitions = [];

		foreach ( $this->definitionProvider() as $name => $definition ) {
			if ( !array_key_exists( 'message', $definition ) ) {
				$definition['message'] = 'test-' . $name;
			}

			$definition['name'] = $name;
			$definitions[] = [ ParamDefinitionFactory::singleton()->newDefinitionFromArray( $definition ) ];
		}

		return $definitions;
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetType( IParamDefinition $definition ) {
		$this->assertEquals( $this->getType(), $definition->getType() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testValidate( IParamDefinition $definition ) {
		foreach ( [ true, false ] as $stringlyTyped ) {
			$values = $this->valueProvider( $stringlyTyped );
			$options = new Options();
			$options->setRawStringInputs( $stringlyTyped );

			foreach ( $values[$definition->getName()] as $data ) {
				[ $input, $valid, ] = $data;

				$param = new Param( $definition );
				$param->setUserValue( $definition->getName(), $input, $options );
				$definitions = [];
				$param->process( $definitions, [], $options );

				$this->assertEquals(
					$valid,
					$param->getErrors() === [],
					'The validation process should ' . ( $valid ? '' : 'not ' ) . 'pass'
				);
			}
		}

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testFormat( IParamDefinition $sourceDefinition ) {
		$values = $this->valueProvider();
		$options = new Options();

		foreach ( $values[$sourceDefinition->getName()] as $data ) {
			$definition = clone $sourceDefinition;

			[ $input, $valid, ] = $data;

			$param = new Param( $definition );
			$param->setUserValue( $definition->getName(), $input, $options );

			if ( $valid && array_key_exists( 2, $data ) ) {
				$defs = [];
				$param->process( $defs, [], $options );

				$this->assertEquals(
					$data[2],
					$param->getValue()
				);
			}
		}

		$this->assertTrue( true );
	}

	protected function validate( ParamDefinition $definition, $testValue, $validity, Options $options = null ) {
		$def = clone $definition;
		$options = $options === null ? new Options() : $options;

		$param = new Param( $def );
		$param->setUserValue( $def->getName(), $testValue, $options );

		$defs = [];
		$param->process( $defs, [], $options );

		$this->assertEquals( $validity, $param->getErrors() === [] );
	}

	public function testConstructingWithoutMessageLeadsToDefaultMessage() {
		$this->assertSame(
			'validator-message-nodesc',
			( new ParamDefinition( 'type', 'name' ) )->getMessage()
		);
	}

	public function getDefinitions() {
		$params = [];

		$params['empty'] = [];

		$params['values'] = [
			'values' => [ 'foo', '1', '0.1', 'yes', 1, 0.1 ]
		];

		$params['empty-empty'] = $params['empty'];
		$params['empty-empty']['hastoexist'] = false;

		$params['values-empty'] = $params['values'];
		$params['values-empty']['hastoexist'] = false;
		$params['values-empty']['values'][] = \Title::newFromText( 'foo' );

		return $params;
	}

	/**
	 * @see ParamDefinitionTest::valueProvider
	 *
	 * @param boolean $stringlyTyped
	 *
	 * @return array
	 */
	public function valueProvider( $stringlyTyped = true ) {
		$values = [
			'empty-empty' => [
				[ 'foo bar page', true, \Title::newFromText( 'foo bar page' ) ],
				[ '|', false ],
				[ '', false ],
			],
			'empty' => [
				[ 'foo bar page', false ],
				[ '|', false ],
				[ '', false ],
			],
			'values-empty' => [
				[ 'foo', true, \Title::newFromText( 'foo' ) ],
				[ 'foo bar page', false ],
			],
			'values' => [
				[ 'foo', false ],
				[ 'foo bar page', false ],
			],
		];

		if ( !$stringlyTyped ) {
			foreach ( $values as &$set ) {
				foreach ( $set as &$value ) {
					$value[0] = \Title::newFromText( $value[0] );
				}
			}
		}

		return $values;
	}

	/**
	 * @see ParamDefinitionTest::getType
	 * @return string
	 */
	public function getType() {
		return 'title';
	}

}
