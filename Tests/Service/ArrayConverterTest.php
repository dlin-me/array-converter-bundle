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
         print_r($res);

    }

}