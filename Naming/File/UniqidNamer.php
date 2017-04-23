<?php

namespace Vich\UploaderBundle\Naming\File;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\Namer;
use Vich\UploaderBundle\Naming\Polyfill;

/**
 * @author Emmanuel Vella <vella.emmanuel@gmail.com>
 */
class UniqidNamer implements Namer
{
    use Polyfill\FileExtensionTrait;

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping) : string
    {
        $file = $mapping->getFile($object);
        $name = str_replace('.', '', uniqid('', true));

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }
}
