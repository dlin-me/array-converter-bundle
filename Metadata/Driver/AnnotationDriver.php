<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 5/07/2014
 * Time: 2:13 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Metadata\Driver;


use Dlin\Bundle\ArrayConversionBundle\Metadata\MethodMetadata;
use Dlin\Bundle\ArrayConversionBundle\Metadata\PropertyMetadata;
use Metadata\Driver\DriverInterface;
use Doctrine\Common\Annotations\Reader;
use Metadata\MergeableClassMetadata;

class AnnotationDriver implements DriverInterface {

    /**
     * @var \Doctrine\Common\Annotations\Reader $reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }


    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getProperties() as $reflectionProperty) {
            $propertyMetadata = new PropertyMetadata($class->getName(), $reflectionProperty->getName());

            $annotation = $this->reader->getPropertyAnnotation(
                    $reflectionProperty,
                    'Dlin\\Bundle\\ArrayConversionBundle\\Annotation\\ArrayConversion'
            );

            if (null !== $annotation) {
                // a "@ArrayConversion" annotation was found
                $propertyMetadata->groups = $annotation->getGroups();
                $propertyMetadata->key = $annotation->getKey();
                if(!$propertyMetadata->key){
                    $propertyMetadata->key = $propertyMetadata->name;
                }
            }

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }
        /**
         * @var \ReflectionMethod $reflectionMethod
         */
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod){
            $methodMetadata = new MethodMetadata($class->getName(), $reflectionMethod->getName());

            $annotation = $this->reader->getMethodAnnotation(
                    $reflectionMethod,
                    'Dlin\\Bundle\\ArrayConversionBundle\\Annotation\\ArrayConversion'
            );

            if (null !== $annotation) {
                // a "@ArrayConversion" annotation was found
                $methodMetadata->groups = $annotation->getGroups();
                $methodMetadata->key = $annotation->getKey();
                if(!$methodMetadata->key){
                    $methodMetadata->key = $methodMetadata->name;
                }
            }

            $classMetadata->addMethodMetadata($methodMetadata);


        }



        return $classMetadata;
    }
}