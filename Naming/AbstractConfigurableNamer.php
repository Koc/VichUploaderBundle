<?php

namespace Vich\UploaderBundle\Naming;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractConfigurableNamer implements ConfigurableNamer
{
    protected $options = [];

    abstract public function configureOptions(OptionsResolver $resolver): void;

    public function setOptions(array $options): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);
        $this->options = $options;
    }
}
