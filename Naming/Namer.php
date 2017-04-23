<?php

namespace Vich\UploaderBundle\Naming;

use Vich\UploaderBundle\Exception\NameGenerationException;
use Vich\UploaderBundle\Mapping\PropertyMapping;

/**
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface Namer
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param object          $object  The object the upload is attached to
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object
     *
     * @return string The file or directory name
     *
     * @throws NameGenerationException
     */
    public function name($object, PropertyMapping $mapping): string;
}
