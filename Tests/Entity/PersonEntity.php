<?php
/**
 * Created by David Lin
 * Project: snappy
 * Email: david.lin@estimateone.com
 * User: davidlin
 * Date: 4/07/2014
 * Time: 3:42 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Tests\Entity;

use \Dlin\Bundle\ArrayConversionBundle\Annotation\ArrayConversion;

class PersonEntity {

    /**
     * @ArrayConversion( groups={"read", "write"})
     */
    private $firstName;

    /**
     * @ArrayConversion( key="last", groups={"read", "write"})
     */
    private $lastName;

    /**
     * @ArrayConversion( key="age", groups={ "write"})
     */
    private $age;


    /**
     * @ArrayConversion(key="fullName", groups={"read"})
     */
    public function getFullName(){

        return trim($this->firstName.' '.$this->lastName);
    }

    /**
     * @ArrayConversion(key="fullName", groups={"write"})
     */
    public function setFullName($fullName){
        list($this->firstName, $this->lastName) = explode(' ', $fullName, 2);
    }


    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }



}