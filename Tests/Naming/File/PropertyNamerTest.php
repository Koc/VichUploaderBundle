<?php

namespace Vich\UploaderBundle\Tests\Naming\File;

use Vich\UploaderBundle\Naming\File\PropertyNamer;
use Vich\UploaderBundle\Tests\DummyEntity;
use Vich\UploaderBundle\Tests\TestCase;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class PropertyNamerTest extends TestCase
{
    public function fileDataProvider()
    {
        $entity = new DummyEntity();
        $entity->someProperty = 'foo';

        $weird_entity = new DummyEntity();
        $weird_entity->someProperty = 'Yéô';

        return [
            ['some-file-name.jpeg', 'foo.jpeg',                 $entity,       'someProperty',     false],
            ['some-file-name',      'foo',                      $entity,       'someProperty',     false],
            ['some-file-name.jpeg', 'generated-file-name.jpeg', $entity,       'generateFileName', false], // method call
            ['some-file-name.jpeg', 'Yeo.jpeg',                 $weird_entity, 'someProperty',     true],  // transliteration enabled
        ];
    }

    /**
     * @dataProvider fileDataProvider
     */
    public function testNameReturnsTheRightName($originalFileName, $expectedFileName, $entity, $propertyName, $transliterate)
    {
        $file = $this->getUploadedFileMock();
        $file
            ->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue($originalFileName));

        $mapping = $this->getPropertyMappingMock();
        $mapping->expects($this->once())
            ->method('getFile')
            ->with($entity)
            ->will($this->returnValue($file));

        $namer = new PropertyNamer();

        $this->assertSame(
            $expectedFileName,
            $namer->name($entity, $mapping, ['property' => $propertyName, 'transliterate' => $transliterate])
        );
    }

    /**
     * @expectedException \Vich\UploaderBundle\Exception\NameGenerationException
     */
    public function testNameFailsIfThePropertyDoesNotExist()
    {
        $entity = new DummyEntity();
        $mapping = $this->getPropertyMappingMock();

        $namer = new PropertyNamer();

        $namer->name($entity, $mapping, ['property' => 'nonExistentProperty']);
    }

    /**
     * @expectedException \Vich\UploaderBundle\Exception\NameGenerationException
     */
    public function testNameFailsIfThePropertyIsEmpty()
    {
        $mapping = $this->getPropertyMappingMock();
        $namer = new PropertyNamer();

        $namer->name(new DummyEntity(), $mapping, ['property' => 'someProperty']);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Option "property" is missing or empty.
     */
    public function testConfigurationFailsIfThePropertyIsntSpecified()
    {
        $namer = new PropertyNamer();

        $namer->configure(['incorrect' => 'options']);
    }
}
