<?php

namespace Vich\UploaderBundle\Naming\File;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\AbstractConfigurableNamer;

/**
 * Namer which uses hash function from random string for generating names.
 *
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class HashNamer extends AbstractConfigurableNamer
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                // which hash algorithm to use
                'algorithm' => 'sha1',
                // limit file name length
                'length' => null,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);

        $name = hash($this->options['algorithm'], $this->getRandomString());
        if (null !== $this->options['length']) {
            $name = substr($name, 0, $this->options['length']);
        }

        if ($extension = $file->guessExtension()) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }

    protected function getRandomString()
    {
        return microtime(true).mt_rand(0, 9999999);
    }
}
