<?php

namespace Vich\UploaderBundle\Naming\Directory;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\AbstractConfigurableNamer;

/**
 * Directory namer which can create subfolder depends on generated filename.
 *
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class SubdirDirectoryNamer extends AbstractConfigurableNamer
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                // how many chars use for each dir
                'chars_per_dir' => 2,
                // how many dirs create
                'dirs' => 1,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping): string
    {
        $fileName = $mapping->getFileName($object);

        $parts = [];
        for ($i = 0, $start = 0; $i < $this->options['dirs']; $i++, $start += $this->options['chars_per_dir']) {
            $parts[] = substr($fileName, $start, $this->options['chars_per_dir']);
        }

        return implode('/', $parts);
    }
}
