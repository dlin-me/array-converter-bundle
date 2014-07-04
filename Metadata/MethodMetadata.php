<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 5/07/2014
 * Time: 2:07 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Metadata;



class MethodMetadata extends \Metadata\MethodMetadata{


    public $key;
    public $groups;


    public function serialize()
    {
        return serialize(array(
                        $this->class,
                        $this->name,
                        $this->key,
                        $this->groups,
                ));
    }

    public function unserialize($str)
    {
        list($this->class, $this->name, $this->key, $this->groups) = unserialize($str);
        $this->reflection = new \ReflectionMethod($this->class, $this->name);

        $this->reflection->setAccessible(true);
    }

}