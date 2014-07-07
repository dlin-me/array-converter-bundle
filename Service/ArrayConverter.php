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
     * This is a helper method for testing if a filed should or should not be used
     *
     * @param array $candidates
     * @param array $criterion
     * @return bool
     */
    protected function matchTest(Array $candidates, Array $criterion){

        $good = array();
        $bad = array();
        foreach($criterion as $value){
            $value = trim($value);
            if(strpos($value, '-') === 0){
                $bad[] = ltrim($value, '-');
            }else{
                $good[] = $value;
            }
        }
        #get the ones that are good but not bad
        return array_unique(array_diff(array_intersect($candidates, $good), $bad));

    }



    /**
     * Converts an object into array
     *
     * @param $object
     * @param $groups array groups you want to include or exclude. e.g. array('read', '-write')
     * @param $keys array Extra keys you want to include or exclude. e.g. array('name' '-password')
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function toArray($object, $groups, $keys=array()){

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
            if ((is_array($propertyMetadata->groups) && $this->matchTest($propertyMetadata->groups, $groups) || in_array($propertyMetadata->key, $keys))
                && !in_array('-'.$propertyMetadata->key, $keys)) {
                $result[$propertyMetadata->key] = $propertyMetadata->getValue($object);
            }
        }

        /**
         * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MethodMetadata $methodMetadata
         */
        foreach ($classMetadata->methodMetadata as $methodMetadata) {
            if ((isset($methodMetadata->groups) && $this->matchTest($methodMetadata->groups, $groups) || in_array($methodMetadata->key, $keys) )
                    && !in_array('-'.$methodMetadata->key, $keys)) {
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
     * @param $groups array groups you want to include or exclude. e.g. array('read'=>true, 'write'=>false)
     * @param $keys array Extra keys you want to include or exclude. e.g. array('name'=>true, 'password'=>false')
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function fromArray($object, $array, $groups){


        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        $classMetadata->fileResources[] = $this->metadataFactory->getCache()->getCachePath(get_class($object)) ; //path to the cache


        foreach($array as $key=>$value){
            /**
             * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\PropertyMetadata $propertyMetadata
             */
            foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
                if (isset($propertyMetadata->groups) && $this->matchTest($propertyMetadata->groups, $groups)) {
                    if($key == $propertyMetadata->key){
                        $propertyMetadata->setValue($object, $value);
                    }
                }
            }

            /**
             * @var \Dlin\Bundle\ArrayConversionBundle\Metadata\MethodMetadata $methodMetadata
             */
            foreach ($classMetadata->methodMetadata as $methodMetadata) {
                if (isset($methodMetadata->groups) && $this->matchTest($methodMetadata->groups, $groups)) {
                    if($key == $methodMetadata->key){

                        $methodMetadata->invoke($object, array($value));
                    }
                }
            }

        }


        return $object;


    }




}