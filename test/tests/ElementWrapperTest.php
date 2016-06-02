<?php

use ASN1\DERData;
use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Feature\ElementBase;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Tagged\ImplicitlyTaggedType;


class ElementWrapperTest extends PHPUnit_Framework_TestCase
{
	public function testAsElement() {
		$wrap = new ElementWrapper(new NullType());
		$this->assertInstanceOf(ElementBase::class, $wrap->asElement());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsTaggedFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asTagged();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsStringFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asString();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsTimeFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asTime();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testPrivateTypeFail() {
		$el = new DERData("\xdf\x7f\x0");
		$wrap = new ElementWrapper($el);
		$wrap->asNull();
	}
	
	public function testToDER() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertEquals($el->toDER(), $wrap->toDER());
	}
	
	public function testTypeClass() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertEquals($el->typeClass(), $wrap->typeClass());
	}
	
	public function testIsConstructed() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertEquals($el->isConstructed(), $wrap->isConstructed());
	}
	
	public function testTag() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertEquals($el->tag(), $wrap->tag());
	}
	
	public function testIsType() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertTrue($wrap->isType(Element::TYPE_NULL));
	}
	
	public function testExpectType() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertInstanceOf(ElementBase::class, 
			$wrap->expectType(Element::TYPE_NULL));
	}
	
	public function testIsTagged() {
		$el = new NullType();
		$wrap = new ElementWrapper($el);
		$this->assertEquals($el->isTagged(), $wrap->isTagged());
	}
	
	public function testExpectTagged() {
		$el = new ImplicitlyTaggedType(0, new NullType());
		$wrap = new ElementWrapper($el);
		$this->assertInstanceOf(ElementBase::class, $wrap->expectTagged(0));
	}
}
