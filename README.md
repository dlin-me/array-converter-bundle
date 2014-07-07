Dlin Array Conversion Bundle
=========


Dlin Symfony Array Conversions Bundle



Version
-

0.1


***
Installation
--------------


Installation using [Composer](http://getcomposer.org/)

Add to your `composer.json`:

    {
        "require" :  {
            "dlin/array-conversion-bundle": "dev-master"
        }
    }


Enable the bundle in you AppKernel.php


    public function registerBundles()
    {
        $bundles = array(
        ...
        new Dlin\Bundle\ArrayConversionBuddle\Dlin\ArrayConversionBundle(),
        ...
    }


Annotation
--------------

There is only two annotation options 'groups' and 'key'.
When dlin.array_converter converts an object into an associated array,
the 'key' specified will be used as the key of the resulting. If ignored, the name of the property is used

You also have the option to specify the set of keys you want to exported to
the resulting array using a group. Example:

    use \Dlin\Bundle\ArrayConversionBundle\Annotation\ArrayConversion;

    class PersonEntity {

        /**
         * If 'key' not given, it use 'firstName' by default
         * @ArrayConversion( groups={"read", "write"})
         */
        private $firstName;

        /**
         * You can use a different array key by specifying a different key value
         * In this example, 'last' will be used as key instead of 'lastName' in the resulting array
         * @ArrayConversion( key="last", groups={"read", "write"})
         */
        private $lastName;

        /**
         * @ArrayConversion( key="age", groups={ "write"})
         */
        private $age;


        /**
         * You can also convert the result of a getter function into the resulting array,
         * If the 'key' is not specified, the function name will be used (i.e 'getFullName') as the key in the resulting array
         * @ArrayConversion(key="fullName", groups={"read"})
         */
        public function getFullName(){

            return trim($this->firstName.' '.$this->lastName);
        }

        /**
         * You can also do this for setter function.
         * This function is called when the 'fromArray' service method is called. This is useful when you want to assign values to object
         * using setter functions instead of setting values for the private properties directly.
         * @ArrayConversion( key="age", groups={ "write"})
         */
        public function setFullName($fullname){
            ...
        }



        ...
    }





Usage
--------------

Geting the service in a controller

    $converter =  $this->get('dlin.array_converter');

Getting the service in a ContainerAwareService

    $converter = $this->container->get('dlin.array_converter');

Using the method "toArray"



    $person = new PersonEntity();
    $person->setFirstName('Hello');
    $person->setLastName('Kitty');
    $person->setAge(12);

    $res = $this->converter->toArray($person, array('read')); #at least a group must given, otherwise empty array returns

    //$this->assertEquals($res['firstName'], $person->getFirstName());
    //$this->assertEquals($res['last'], $person->getLastName());
    //$this->assertEquals($res['fullName'], $person->getFullName());


Using the method "fromArray"

    $person = new PersonEntity();
    $person->setFirstName('Hello');
    $person->setLastName('Kitty');
    $person->setAge(12);

    $array = array('firstName'=>'New Name', 'age'=>13);

    $this->converter->fromArray($person, $array, array('write')); #must specify a or more group

    //$this->assertEquals("New Name", $person->getFirstName());
    //$this->assertEquals(13, $person->getAge());


Notes
--------------
* The 'fromArray' will set the property of the target object directly, even for private properties. To use setter/getter function, put the annotations
  to the getter/setter functions instead.

* You can specify multiple groups for a property, when calling 'toArray' or 'fromArray', that property is involved if any of the groups specified match any of the groups in the 'groups' parameter



License
-

MIT

*Free Software, Yeah!*


