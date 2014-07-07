<?php
/**
 * Created by David Lin
 * Project: snappy
 * Email: david.lin@estimateone.com
 * User: davidlin
 * Date: 4/07/2014
 * Time: 3:40 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Tests\Entity;



use Dlin\Bundle\ArrayConversionBundle\Annotation\ArrayConversion as AC;

class UserEntity extends PersonEntity{

    /**
     * @AC(groups={"write"})
     */
    private $password;


    /**
     * @AC(key="username", groups={"read"})
     */
    private $email;



    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }



}