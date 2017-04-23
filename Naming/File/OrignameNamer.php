<?php

namespace Vich\UploaderBundle\Naming\File;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\AbstractConfigurableNamer;
use Vich\UploaderBundle\Util\Transliterator;

/**
 * @author Ivan Borzenkov <ivan.borzenkov@gmail.com>
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class OrignameNamer extends AbstractConfigurableNamer
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['transliterate' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);
        $name = $file->getClientOriginalName();

        if ($this->options['transliterate']) {
            $name = Transliterator::transliterate($name);
        }

        return uniqid('', true).'_'.$name;
    }
}
