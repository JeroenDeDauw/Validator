<?php

namespace ParamProcessor\Tests;

use ParamProcessor\MediaWikiTitleValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ParamProcessor\MediaWikiTitleValue
 *
 * @group Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiTitleValueTest extends TestCase {

	public function testGivenValidPage_getValueWorks(  ) {
		$titleValue = new MediaWikiTitleValue( \Title::newFromText( 'Foobar' ) );
		$this->assertSame( 'Foobar', $titleValue->getValue()->getFullText() );
	}

}
