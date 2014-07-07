<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 27/06/13
 * Time: 3:39 PM
 *
 */
namespace Dlin\Bundle\ArrayConversionBundle\Tests\Service;


use Dlin\Bundle\ArrayConversionBundle\Tests\Entity\PersonEntity;
use Dlin\Bundle\ArrayConversionBundle\Tests\Entity\UserEntity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Dlin\Bundle\ArrayConversionBundle\Tests\AppKernel;



class ArrayConverterTest extends WebTestCase {

    /**
     * @var \Dlin\Bundle\ArrayConversionBundle\Service\ArrayConverter
     */
    protected $converter;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        static::$kernel = new  AppKernel( 'default.yml' );
        static::$kernel->boot();
        $this->converter = static::$kernel->getContainer()
                ->get('dlin.array_converter');

    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
       parent::tearDown();
       $this->converter = null;

    }

    /**
     * Simple test
     */
    public function testPersonToArray(){

        $person = new PersonEntity();
        $person->setFirstName('Hello');
        $person->setLastName('Kitty');
        $person->setAge(12);

        $res = $this->converter->toArray($person, array('read'));
        $this->assertEquals($res['firstName'], $person->getFirstName());
        $this->assertEquals($res['last'], $person->getLastName());
        $this->assertEquals($res['fullName'], $person->getFullName());

    }


    public function testUserToArray(){
        $person = new UserEntity();
        $person->setFirstName('Hello');
        $person->setLastName('Kitty');
        $person->setAge(12);
        $person->setEmail('test@test.com');

        $res = $this->converter->toArray($person, array('read'));
        $this->assertEquals($res['firstName'], $person->getFirstName());
        $this->assertEquals($res['last'], $person->getLastName());
        $this->assertEquals($res['fullName'], $person->getFullName());
        $this->assertEquals($res['username'], $person->getEmail());
        $this->assertArrayNotHasKey('email', $res );
        $this->assertArrayNotHasKey('password', $res);
    }


    public function testPersionFromArray(){
        $person = new PersonEntity();
        $person->setFirstName('Hello');
        $person->setLastName('Kitty');
        $person->setAge(12);

        $array = array('firstName'=>'New Name', 'age'=>13);

        $this->assertEquals(12, $person->getAge());
        $this->assertEquals('Hello', $person->getFirstName());

        $this->converter->fromArray($person, $array, array('write'));

        $this->assertEquals("New Name", $person->getFirstName());
        $this->assertEquals(13, $person->getAge());
    }


    public function testUserFromArray(){
        $person = new UserEntity();
        $person->setFirstName('Hello');
        $person->setLastName('Kitty');
        $person->setAge(12);
        $person->setEmail('test@test.com');
        $person->setPassword('oldpass');

        $array = array('firstName'=>'New Name', 'username'=>'new@test.com', 'password'=>'newpass');

        $this->converter->fromArray($person, $array, array('write'));

        $this->assertEquals('New Name', $person->getFirstName());
        $this->assertEquals('test@test.com', $person->getEmail());
        $this->assertEquals('newpass', $person->getPassword());
    }



}