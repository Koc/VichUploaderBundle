<?php

namespace Vich\UploaderBundle\Mapping;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * @author Dustin Dobervich <ddobervich@gmail.com>
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class PropertyMapping
{
    protected $namer;

    protected $directoryNamer;

    protected $mapping;

    protected $mappingName;

    protected $uploadableField;

    protected $accessor;

    public function __construct(UploadableField $uploadableField, NamerInterface $namer, DirectoryNamerInterface $directoryNamer, array $mapping, string $mappingName, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->uploadableField = $uploadableField;
        $this->namer = $namer;
        $this->directoryNamer = $directoryNamer;
        $this->mapping = $mapping;
        $this->mappingName = $mappingName;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function getUploadableField(): UploadableField
    {
        return $this->uploadableField;
    }

    /**
     * Gets the file property value for the given object.
     *
     * @param object $obj The object
     *
     * @return UploadedFile|null The file
     */
    public function getFile($obj)
    {
        return $this->readProperty($obj, 'file');
    }

    /**
     * Modifies the file property value for the given object.
     *
     * @param object       $obj  The object
     * @param UploadedFile $file The new file
     */
    public function setFile($obj, UploadedFile $file)
    {
        $this->writeProperty($obj, 'file', $file);
    }

    /**
     * Gets the fileName property of the given object.
     *
     * @param object $obj The object
     *
     * @return string The filename
     */
    public function getFileName($obj)
    {
        return $this->readProperty($obj, 'name');
    }

    /**
     * Modifies the fileName property of the given object.
     *
     * @param object $obj   The object
     * @param string $value
     */
    public function setFileName($obj, $value)
    {
        $this->writeProperty($obj, 'name', $value);
    }

    /**
     * Reads property of the given object.
     *
     * @internal
     *
     * @param object $obj      The object from which read
     * @param string $property The property to read
     *
     * @return mixed
     */
    public function readProperty($obj, $property)
    {
        $propertyPath = $this->uploadableField->getPropertyPath($property);

        if (!$propertyPath) {
            // not configured
            return null;
        }

        $propertyPath = $this->fixPropertyPath($obj, $propertyPath);

        return $this->propertyAccessor->getValue($obj, $propertyPath);
    }

    /**
     * Modifies property of the given object.
     *
     * @internal
     *
     * @param object $obj      The object to which write
     * @param string $property The property to write
     * @param mixed  $value    The value which should be written
     */
    public function writeProperty($obj, $property, $value)
    {
        $propertyPath = $this->uploadableField->getPropertyPath($property);

        if (!$propertyPath) {
            // not configured
            return null;
        }

        $propertyPath = $this->fixPropertyPath($obj, $propertyPath);
        $this->propertyAccessor->setValue($obj, $propertyPath, $value);
    }

    public function getNamer() : NamerInterface
    {
        return $this->namer;
    }

    public function getDirectoryNamer() : DirectoryNamerInterface
    {
        return $this->directoryNamer;
    }

    /**
     * Gets the configured configuration mapping name.
     *
     * @return string The mapping name
     */
    public function getMappingName() : string
    {
        return $this->mappingName;
    }

    /**
     * Gets the upload name for a given file (uses The file namers).
     *
     * @param object $obj
     *
     * @return string The upload name
     */
    public function getUploadName($obj) : string
    {
        return $this->namer->name($obj, $this);
    }

    /**
     * Gets the upload directory for a given file (uses the directory namers).
     *
     * @param object $obj
     *
     * @return string The upload directory
     */
    public function getUploadDir($obj) : string
    {
        $dir = $this->directoryNamer->directoryName($obj, $this);

        // strip the trailing directory separator if needed
        return rtrim($dir, '/\\');
    }

    /**
     * Gets the base upload directory.
     *
     * @return string The configured upload directory
     */
    public function getUploadDestination() : string
    {
        return $this->mapping['upload_destination'];
    }

    /**
     * Get uri prefix.
     *
     * @return string
     */
    public function getUriPrefix()
    {
        return $this->mapping['uri_prefix'];
    }

    /**
     * Fixes a given propertyPath to make it usable both with arrays and
     * objects.
     * Ie: if the given object is in fact an array, the property path must
     * look like [myPath].
     *
     * @param object|array $object       The object to inspect
     * @param string       $propertyPath The property path to fix
     *
     * @return string The fixed property path
     */
    protected function fixPropertyPath($object, $propertyPath)
    {
        if (!is_array($object)) {
            return $propertyPath;
        }

        return $propertyPath[0] === '[' ? $propertyPath : sprintf('[%s]', $propertyPath);
    }
}
