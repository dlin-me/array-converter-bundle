<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 5/07/2014
 * Time: 2:30 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Service;


use Dlin\Bundle\ArrayConversionBundle\Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;

include_once __DIR__.'/../Annotation/ArrayConversion.php';
class ArrayConverter {

    /**
     * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MetadataFactory $metadataFactory
     */
    private $metadataFactory;

    public function __construct(MetadataFactory $metadataFactory)
    {
        /**
         * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MetadataFactory $metadataFactory
         */
        $this->metadataFactory = $metadataFactory;

    }

    /**
     * Converts an object into array
     *
     * @param $object
     * @param $groups
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function toArray($object, $groups){

        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }

        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        $classMetadata->fileResources[] = $this->metadataFactory->getCache()->getCachePath(get_class($object)) ; //path to the cache


        $result = array();

        /**
         * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\PropertyMetadata $propertyMetadata
         */
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->groups) && count(array_intersect($propertyMetadata->groups, $groups)) > 0) {
                $result[$propertyMetadata->key] = $propertyMetadata->getValue($object);
            }
        }

        /**
         * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MethodMetadata $methodMetadata
         */
        foreach ($classMetadata->methodMetadata as $methodMetadata) {
            if (isset($methodMetadata->groups) && count(array_intersect($methodMetadata->groups, $groups)) > 0) {
                $result[$methodMetadata->key] = $methodMetadata->invoke($object);
            }
        }



        return $result;


    }

    /**
     * Sets object property values from an array
     *
     * @param $object
     * @param $array
     * @param $groups
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function fromArray($object, $array, $groups){


        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));


        foreach($array as $key=>$value){
            /**
             * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\PropertyMetadata $propertyMetadata
             */
            foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
                if (isset($propertyMetadata->groups) && count(array_intersect($propertyMetadata->groups, $groups)) > 0) {
                    if($key == $propertyMetadata->key){
                        $propertyMetadata->setValue($object, $value);
                    }
                }
            }

            /**
             * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MethodMetadata $methodMetadata
             */
            foreach ($classMetadata->methodMetadata as $methodMetadata) {
                if (isset($methodMetadata->groups) && count(array_intersect($methodMetadata->groups, $groups)) > 0) {
                    if($key == $methodMetadata->key){

                        $methodMetadata->invoke($object, array($value));
                    }
                }
            }

        }


        return $object;


    }




}