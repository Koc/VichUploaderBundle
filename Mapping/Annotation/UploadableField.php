<?php

namespace Vich\UploaderBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class UploadableField
{
    /**
     * @var string
     */
    protected $mapping;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $originalName;

    /**
     * Constructs a new instance of UploadableField.
     *
     * @param array $options The options
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options)
    {
        if (empty($options['mapping'])) {
            throw new \InvalidArgumentException('The "mapping" attribute of UploadableField is required.');
        }

        foreach ($options as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new \RuntimeException(sprintf('Unknown key "%s" for annotation "@%s".', $property, get_class($this)));
            }

            $this->$property = $value;
        }
    }

    public function getMapping() : string
    {
        return $this->mapping;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string|null
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string|null
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }
}
