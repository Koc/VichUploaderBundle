<?php

namespace Vich\UploaderBundle\Naming\File;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Vich\UploaderBundle\Exception\NameGenerationException;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\AbstractConfigurableNamer;
use Vich\UploaderBundle\Naming\Polyfill;
use Vich\UploaderBundle\Util\Transliterator;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class PropertyNamer extends AbstractConfigurableNamer
{
    use Polyfill\FileExtensionTrait;

    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            // path to the property used to name the file. Can be either an attribute or a method
            ->setRequired('property')
            // whether the filename should be transliterated or not
            ->setDefaults(['transliterate' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);

        try {
            $name = $this->propertyAccessor->getValue($object, $this->options['property_path']);
        } catch (NoSuchPropertyException $e) {
            throw new NameGenerationException(
                sprintf(
                    'File name could not be generated: property %s does not exist.',
                    $this->options['property_path']
                ), $e->getCode(), $e
            );
        }

        if (empty($name)) {
            throw new NameGenerationException(
                sprintf('File name could not be generated: property %s is empty.', $this->options['property_path'])
            );
        }

        if ($this->options['transliterate']) {
            $name = Transliterator::transliterate($name);
        }

        // append the file extension if there is one
        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }
}
