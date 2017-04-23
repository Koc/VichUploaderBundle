<?php

namespace Vich\UploaderBundle\Naming;

/**
 * Allows namers to receive configuration options.
 *
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
interface ConfigurableNamer extends Namer
{
    /**
     * Sets the options for this namer.
     *
     * @param array $options The resolver for the options
     */
    public function setOptions(array $options): void;
}
