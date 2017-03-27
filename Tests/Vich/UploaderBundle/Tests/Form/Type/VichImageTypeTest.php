<?php

namespace Tests\Vich\UploaderBundle\Tests\Form\Type;

use Vich\TestBundle\Entity\Product;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Tests\Form\Type\VichFileTypeTest;

class VichImageTypeTest extends VichFileTypeTest
{
    const TESTED_TYPE = VichImageType::class;

    public function buildViewDataProvider()
    {
        $object = new Product();

        return [
            [
                $object,
                [
                    'download_link' => true,
                    'download_label' => 'download',
                    'download_uri' => null,
                    'image_uri' => null,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => 'resolved-uri',
                    'show_download_link' => true,
                    'value' => null,
                    'attr' => [],
                ],
            ],
            [
                $object,
                [
                    'download_link' => false,
                    'download_label' => 'download',
                    'download_uri' => null,
                    'image_uri' => null,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => 'resolved-uri',
                    'show_download_link' => false,
                    'value' => null,
                    'attr' => [],
                ],
            ],
            [
                $object,
                [
                    'download_link' => true,
                    'download_label' => 'download',
                    'download_uri' => 'custom-uri',
                    'image_uri' => null,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => 'custom-uri',
                    'show_download_link' => true,
                    'value' => null,
                    'attr' => [],
                ],
            ],
        ];
    }
}
