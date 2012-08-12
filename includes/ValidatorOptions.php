<?php

/**
 * Object for holding options affecting the behaviour of a Validator object.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorOptions {

	protected $name;

	protected $unknownInvalid = true;

	protected $lowercaseNames = true;

	protected $rawStringInputs = true;

	protected $trimValues = false;

	protected $lowercaseValues = false;

	/**
	 * Constructor.
	 *
	 * @since 0.5
	 */
	public function __construct() {
		
	}

	/**
	 * @since 0.5
	 *
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $unknownInvalid
	 */
	public function setUnknownInvalid( $unknownInvalid ) {
		$this->unknownInvalid = $unknownInvalid;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $lowercase
	 */
	public function setLowercaseNames( $lowercase ) {
		$this->lowercaseNames = $lowercase;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $rawInputs
	 */
	public function setRawStringInputs( $rawInputs ) {
		$this->rawStringInputs = $rawInputs;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $trim
	 */
	public function setTrimValues( $trim ) {
		$this->trimValues = $trim;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $lowercase
	 */
	public function setLowercaseValues( $lowercase ) {
		$this->lowercaseValues = $lowercase;
	}

	/**
	 * @since 0.5
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function unknownIsInvalid() {
		return $this->unknownInvalid;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function lowercaseNames() {
		return $this->lowercaseNames;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getRawStringInputs() {
		return $this->rawStringInputs;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function trimValues() {
		return $this->trimValues;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function lowercaseValues() {
		return $this->lowercaseValues;
	}

}