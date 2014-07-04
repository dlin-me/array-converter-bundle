<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 5/07/2014
 * Time: 1:49 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Annotation;



/**
 * @Annotation
 */
class ArrayConversion {


    /**
     * @var string This is used as a key/index of the input/output array
     */
    public $key;


    /**
     * @var array Array of group names
     */
    public $groups;

    /**
     * Constructor
     * @param array $data
     */
    public function __construct(array $data)
    {
        if(isset($data['value'])){
            $data['key'] = $data['value'];
        }

        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }
            $this->$key = $value;
        }
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }



    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }




}