<?php

namespace Tests\Vich\UploaderBundle\Tests\Form\Type;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Vich\TestBundle\Entity\Product;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Handler\UploadHandler;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Vich\UploaderBundle\Storage\StorageInterface;
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
                    'download_uri' => true,
                    'download_label' => 'download',
                    'image_uri' => false,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => 'resolved-uri',
                    'download_label' => 'download',
                    'image_uri' => null,
                    'show_download_link' => true,
                    'value' => null,
                    'attr' => [],
                ],
            ],
            [
                $object,
                [
                    'download_uri' => false,
                    'download_label' => 'download',
                    'image_uri' => true,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => false,
                    'download_label' => 'download',
                    'image_uri' => 'resolved-uri',
                    'show_download_link' => false,
                    'value' => null,
                    'attr' => [],
                ],
            ],
            [
                $object,
                [
                    'download_label' => 'download',
                    'download_uri' => 'custom-uri',
                    'image_uri' => true,
                    'imagine_pattern' => null,
                ],
                [
                    'object' => $object,
                    'download_uri' => 'custom-uri',
                    'download_label' => 'download',
                    'show_download_link' => true,
                    'image_uri' => 'resolved-uri',
                    'value' => null,
                    'attr' => [],
                ],
            ],
            // test configure options
            // test image uri: callable
        ];
    }

    public function testLiipImagineBundleIntegration()
    {
        if (!class_exists(CacheManager::class)) {
            $this->markTestSkipped('LiipImagineBundle is not installed.');
        }

        //FIXME: write test

        $this->assertTrue(true);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage LiipImagineBundle must be installed and configured for using "imagine_pattern" option.
     */
    public function testLiipImagineBundleIntegrationThrownExceptionIfNotAvailable()
    {
        $object = new Product();

        $testedType = static::TESTED_TYPE;

        $storage = $this->createMock(StorageInterface::class);
        $uploadHandler = $this->createMock(UploadHandler::class);
        $propertyMappingFactory = $this->createMock(PropertyMappingFactory::class);
        $propertyAccessor = $this->createMock(PropertyAccessor::class);

        $parentForm = $this->createMock(FormInterface::class);
        $parentForm
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($object));

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects($this->any())
            ->method('getParent')
            ->will($this->returnValue($parentForm));

        $view = new FormView();
        $type = new $testedType($storage, $uploadHandler, $propertyMappingFactory, $propertyAccessor);
        $type->buildView($view, $form, ['imagine_pattern' => 'product_sq200']);
    }
}
