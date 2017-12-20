<?php

declare(strict_types = 1);

namespace ASN1\Type;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Feature\ElementBase;

/**
 * Decorator class to wrap an element without already knowing the specific
 * underlying type.
 *
 * Provides accessor methods to test the underlying type and return a type
 * hinted instance of the concrete element.
 */
class UnspecifiedType implements ElementBase
{
    /**
     * The wrapped element.
     *
     * @var Element
     */
    private $_element;
    
    /**
     * Constructor.
     *
     * @param Element $el
     */
    public function __construct(Element $el)
    {
        $this->_element = $el;
    }
    
    /**
     * Initialize from DER data.
     *
     * @param string $data DER encoded data
     * @return self
     */
    public static function fromDER(string $data): self
    {
        return Element::fromDER($data)->asUnspecified();
    }
    
    /**
     * Initialize from ElementBase interface.
     *
     * @param ElementBase $el
     * @return self
     */
    public static function fromElementBase(ElementBase $el): self
    {
        // if element is already wrapped
        if ($el instanceof self) {
            return $el;
        }
        return new self($el->asElement());
    }
    
    /**
     * Compatibility method to dispatch calls to the wrapped element.
     *
     * @deprecated Use <code>as*</code> accessor methods to ensure strict type
     * @param string $mtd Method name
     * @param array $args Arguments
     * @return mixed
     */
    public function __call($mtd, array $args)
    {
        return call_user_func_array([$this->_element, $mtd], $args);
    }
    
    /**
     * Get the wrapped element as a context specific tagged type.
     *
     * @throws \UnexpectedValueException If the element is not tagged
     * @return TaggedType
     */
    public function asTagged(): TaggedType
    {
        if (!$this->_element instanceof TaggedType) {
            throw new \UnexpectedValueException(
                "Tagged element expected, got " . $this->_typeDescriptorString());
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an application specific type.
     *
     * @throws \UnexpectedValueException If element is not application specific
     * @return \ASN1\Type\Tagged\ApplicationType
     */
    public function asApplication(): Tagged\ApplicationType
    {
        if (!$this->_element instanceof Tagged\ApplicationType) {
            throw new \UnexpectedValueException(
                "Application type expected, got " .
                     $this->_typeDescriptorString());
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a boolean type.
     *
     * @throws \UnexpectedValueException If the element is not a boolean
     * @return \ASN1\Type\Primitive\Boolean
     */
    public function asBoolean(): Primitive\Boolean
    {
        if (!$this->_element instanceof Primitive\Boolean) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_BOOLEAN));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an integer type.
     *
     * @throws \UnexpectedValueException If the element is not an integer
     * @return \ASN1\Type\Primitive\Integer
     */
    public function asInteger(): Primitive\Integer
    {
        if (!$this->_element instanceof Primitive\Integer) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_INTEGER));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a bit string type.
     *
     * @throws \UnexpectedValueException If the element is not a bit string
     * @return \ASN1\Type\Primitive\BitString
     */
    public function asBitString(): Primitive\BitString
    {
        if (!$this->_element instanceof Primitive\BitString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_BIT_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an octet string type.
     *
     * @throws \UnexpectedValueException If the element is not an octet string
     * @return \ASN1\Type\Primitive\OctetString
     */
    public function asOctetString(): Primitive\OctetString
    {
        if (!$this->_element instanceof Primitive\OctetString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_OCTET_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a null type.
     *
     * @throws \UnexpectedValueException If the element is not a null
     * @return \ASN1\Type\Primitive\NullType
     */
    public function asNull(): Primitive\NullType
    {
        if (!$this->_element instanceof Primitive\NullType) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_NULL));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an object identifier type.
     *
     * @throws \UnexpectedValueException If the element is not an object
     *         identifier
     * @return \ASN1\Type\Primitive\ObjectIdentifier
     */
    public function asObjectIdentifier(): Primitive\ObjectIdentifier
    {
        if (!$this->_element instanceof Primitive\ObjectIdentifier) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(
                    Element::TYPE_OBJECT_IDENTIFIER));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an object descriptor type.
     *
     * @throws \UnexpectedValueException If the element is not an object
     *         descriptor
     * @return \ASN1\Type\Primitive\ObjectDescriptor
     */
    public function asObjectDescriptor(): Primitive\ObjectDescriptor
    {
        if (!$this->_element instanceof Primitive\ObjectDescriptor) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(
                    Element::TYPE_OBJECT_DESCRIPTOR));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a real type.
     *
     * @throws \UnexpectedValueException If the element is not a real
     * @return \ASN1\Type\Primitive\Real
     */
    public function asReal(): Primitive\Real
    {
        if (!$this->_element instanceof Primitive\Real) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_REAL));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an enumerated type.
     *
     * @throws \UnexpectedValueException If the element is not an enumerated
     * @return \ASN1\Type\Primitive\Enumerated
     */
    public function asEnumerated(): Primitive\Enumerated
    {
        if (!$this->_element instanceof Primitive\Enumerated) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_ENUMERATED));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a UTF8 string type.
     *
     * @throws \UnexpectedValueException If the element is not a UTF8 string
     * @return \ASN1\Type\Primitive\UTF8String
     */
    public function asUTF8String(): Primitive\UTF8String
    {
        if (!$this->_element instanceof Primitive\UTF8String) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_UTF8_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a relative OID type.
     *
     * @throws \UnexpectedValueException If the element is not a relative OID
     * @return \ASN1\Type\Primitive\RelativeOID
     */
    public function asRelativeOID(): Primitive\RelativeOID
    {
        if (!$this->_element instanceof Primitive\RelativeOID) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_RELATIVE_OID));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a sequence type.
     *
     * @throws \UnexpectedValueException If the element is not a sequence
     * @return \ASN1\Type\Constructed\Sequence
     */
    public function asSequence(): Constructed\Sequence
    {
        if (!$this->_element instanceof Constructed\Sequence) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_SEQUENCE));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a set type.
     *
     * @throws \UnexpectedValueException If the element is not a set
     * @return \ASN1\Type\Constructed\Set
     */
    public function asSet(): Constructed\Set
    {
        if (!$this->_element instanceof Constructed\Set) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_SET));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a numeric string type.
     *
     * @throws \UnexpectedValueException If the element is not a numeric string
     * @return \ASN1\Type\Primitive\NumericString
     */
    public function asNumericString(): Primitive\NumericString
    {
        if (!$this->_element instanceof Primitive\NumericString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_NUMERIC_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a printable string type.
     *
     * @throws \UnexpectedValueException If the element is not a printable
     *         string
     * @return \ASN1\Type\Primitive\PrintableString
     */
    public function asPrintableString(): Primitive\PrintableString
    {
        if (!$this->_element instanceof Primitive\PrintableString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_PRINTABLE_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a T61 string type.
     *
     * @throws \UnexpectedValueException If the element is not a T61 string
     * @return \ASN1\Type\Primitive\T61String
     */
    public function asT61String(): Primitive\T61String
    {
        if (!$this->_element instanceof Primitive\T61String) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_T61_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a videotex string type.
     *
     * @throws \UnexpectedValueException If the element is not a videotex string
     * @return \ASN1\Type\Primitive\VideotexString
     */
    public function asVideotexString(): Primitive\VideotexString
    {
        if (!$this->_element instanceof Primitive\VideotexString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_VIDEOTEX_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a IA5 string type.
     *
     * @throws \UnexpectedValueException If the element is not a IA5 string
     * @return \ASN1\Type\Primitive\IA5String
     */
    public function asIA5String(): Primitive\IA5String
    {
        if (!$this->_element instanceof Primitive\IA5String) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_IA5_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as an UTC time type.
     *
     * @throws \UnexpectedValueException If the element is not a UTC time
     * @return \ASN1\Type\Primitive\UTCTime
     */
    public function asUTCTime(): Primitive\UTCTime
    {
        if (!$this->_element instanceof Primitive\UTCTime) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_UTC_TIME));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a generalized time type.
     *
     * @throws \UnexpectedValueException If the element is not a generalized
     *         time
     * @return \ASN1\Type\Primitive\GeneralizedTime
     */
    public function asGeneralizedTime(): Primitive\GeneralizedTime
    {
        if (!$this->_element instanceof Primitive\GeneralizedTime) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_GENERALIZED_TIME));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a graphic string type.
     *
     * @throws \UnexpectedValueException If the element is not a graphic string
     * @return \ASN1\Type\Primitive\GraphicString
     */
    public function asGraphicString(): Primitive\GraphicString
    {
        if (!$this->_element instanceof Primitive\GraphicString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_GRAPHIC_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a visible string type.
     *
     * @throws \UnexpectedValueException If the element is not a visible string
     * @return \ASN1\Type\Primitive\VisibleString
     */
    public function asVisibleString(): Primitive\VisibleString
    {
        if (!$this->_element instanceof Primitive\VisibleString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_VISIBLE_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a general string type.
     *
     * @throws \UnexpectedValueException If the element is not general string
     * @return \ASN1\Type\Primitive\GeneralString
     */
    public function asGeneralString(): Primitive\GeneralString
    {
        if (!$this->_element instanceof Primitive\GeneralString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_GENERAL_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a universal string type.
     *
     * @throws \UnexpectedValueException If the element is not a universal
     *         string
     * @return \ASN1\Type\Primitive\UniversalString
     */
    public function asUniversalString(): Primitive\UniversalString
    {
        if (!$this->_element instanceof Primitive\UniversalString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_UNIVERSAL_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a character string type.
     *
     * @throws \UnexpectedValueException If the element is not a character
     *         string
     * @return \ASN1\Type\Primitive\CharacterString
     */
    public function asCharacterString(): Primitive\CharacterString
    {
        if (!$this->_element instanceof Primitive\CharacterString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_CHARACTER_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as a BMP string type.
     *
     * @throws \UnexpectedValueException If the element is not a bmp string
     * @return \ASN1\Type\Primitive\BMPString
     */
    public function asBMPString(): Primitive\BMPString
    {
        if (!$this->_element instanceof Primitive\BMPString) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_BMP_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as any string type.
     *
     * @throws \UnexpectedValueException If the element is not a string
     * @return StringType
     */
    public function asString(): StringType
    {
        if (!$this->_element instanceof StringType) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_STRING));
        }
        return $this->_element;
    }
    
    /**
     * Get the wrapped element as any time type.
     *
     * @throws \UnexpectedValueException If the element is not a time
     * @return TimeType
     */
    public function asTime(): TimeType
    {
        if (!$this->_element instanceof TimeType) {
            throw new \UnexpectedValueException(
                $this->_generateExceptionMessage(Element::TYPE_TIME));
        }
        return $this->_element;
    }
    
    /**
     * Generate message for exceptions thrown by <code>as*</code> methods.
     *
     * @param int $tag Type tag of the expected element
     * @return string
     */
    private function _generateExceptionMessage(int $tag): string
    {
        return sprintf("%s expected, got %s.", Element::tagToName($tag),
            $this->_typeDescriptorString());
    }
    
    /**
     * Get textual description of the wrapped element for debugging purposes.
     *
     * @return string
     */
    private function _typeDescriptorString(): string
    {
        $type_cls = $this->_element->typeClass();
        $tag = $this->_element->tag();
        if ($type_cls == Identifier::CLASS_UNIVERSAL) {
            return Element::tagToName($tag);
        }
        return Identifier::classToName($type_cls) . " TAG $tag";
    }
    
    /**
     *
     * @see \ASN1\Feature\Encodable::toDER()
     * @return string
     */
    public function toDER(): string
    {
        return $this->_element->toDER();
    }
    
    /**
     *
     * @see \ASN1\Feature\ElementBase::typeClass()
     * @return int
     */
    public function typeClass(): int
    {
        return $this->_element->typeClass();
    }
    
    /**
     *
     * @see \ASN1\Feature\ElementBase::isConstructed()
     * @return bool
     */
    public function isConstructed(): bool
    {
        return $this->_element->isConstructed();
    }
    
    /**
     *
     * @see \ASN1\Feature\ElementBase::tag()
     * @return int
     */
    public function tag(): int
    {
        return $this->_element->tag();
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \ASN1\Feature\ElementBase::isType()
     * @return bool
     */
    public function isType(int $tag): bool
    {
        return $this->_element->isType($tag);
    }
    
    /**
     *
     * @deprecated Use any <code>as*</code> accessor method first to ensure
     *             type strictness.
     * @see \ASN1\Feature\ElementBase::expectType()
     * @return ElementBase
     */
    public function expectType(int $tag): ElementBase
    {
        return $this->_element->expectType($tag);
    }
    
    /**
     *
     * @see \ASN1\Feature\ElementBase::isTagged()
     * @return bool
     */
    public function isTagged(): bool
    {
        return $this->_element->isTagged();
    }
    
    /**
     *
     * @deprecated Use any <code>as*</code> accessor method first to ensure
     *             type strictness.
     * @see \ASN1\Feature\ElementBase::expectTagged()
     * @return TaggedType
     */
    public function expectTagged($tag = null): TaggedType
    {
        return $this->_element->expectTagged($tag);
    }
    
    /**
     *
     * @see \ASN1\Feature\ElementBase::asElement()
     * @return Element
     */
    public function asElement(): Element
    {
        return $this->_element;
    }
    
    /**
     *
     * {@inheritdoc}
     * @return UnspecifiedType
     */
    public function asUnspecified(): UnspecifiedType
    {
        return $this;
    }
}
