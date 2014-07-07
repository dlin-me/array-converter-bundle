Dlin Array Conversion Bundle
=========
Dlin Symfony Array Conversions Bundle

Building a RESTFul API with Symfony2 ? You might have tried the FriendsOfSymfony/FOSRestBundle Bundle.

If you are like me, you will probably think that FriendsOfSymfony/FOSRestBundle is awesome but it is complicated and it does not support something you really want.

You want to change the way it works but you can't without modifying the bundle.

Let's think again what you really need for building a RESTful API:

* Content Negotiation ?
  Do you really need that ? All my recently built API supports only JSON, no negotiation.
  If you don't need content negotiation, the good news is that Symfony itself does the routing very well, you can simple use normal controller for API endpoints

* Serialization, i.e. convert to array and then JSON
  This is important. You really need this not only for Doctrine Entities but also any objects.
  You sometimes want to wrap your response with extra details, for metadata or error details.
  You sometimes want to also expose data through getter functions.
  You sometimes want to use a different field names.
  You also want to be able to process submitted data from User and update the Entity objects easily
  Symfony has a good JsonResponse class for returning JSON response.

* Permission control
  You want some fields of the resource be updatable/readable based on the Role of the current user.
  Symfony has built in support for Role based permission control.


It looks like the only part that Symfony lacks, is an easy way to convert an Entitie/Object to an array and 'hydrate' an Entity with an array.
Dlin/ArrayConverter does only the array conversion part, and it does it well. For the rest parts, Symfony itself is enough to rescue.



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

The "toArray" method converts an annotated object into an array. It accepts 3 parameters:
1. The object
2. Array of group names. properties with matching group name with go to the result array. You can prefix a group name with '-' to exclude fields with that group. E.g. ['user', '-adminuser'] will include properties
   marked as in the user group but not in the adminuser group. If a property is marked as in both groups, it will not go into the resulting array.
3. Array of property keys to include/exclude. You can override the group selection by passing this to the 'toArray' method. E.g. ['username', '-password'] will include the property 'username' and exclude 'password' regardless how group names match.



    $person = new PersonEntity();
    $person->setFirstName('Hello');
    $person->setLastName('Kitty');
    $person->setAge(12);

    $res = $converter->toArray($person, array('read')); #at least a group must given, otherwise empty array returns

    //$this->assertEquals($res['firstName'], $person->getFirstName());
    //$this->assertEquals($res['last'], $person->getLastName());
    //$this->assertEquals($res['fullName'], $person->getFullName());


Using the method "fromArray"

The "fromArray" method 'hydrate' an object using values of a given array. It accepts 2 parameters
1. The object
2. Array of group names. properties with matching group name with be hydrated if value is found from the given array. You can prefix a group name with '-' to exclude fields with that group. E.g. ['user', '-adminuser'] will update properties marked as in the user group but not in the adminuser group. If a property is marked as in both groups, it will not be updated

Unlike the "toArray" method, it does not accept the third parameter to override group selection.


    $person = new PersonEntity();
    $person->setFirstName('Hello');
    $person->setLastName('Kitty');
    $person->setAge(12);

    $array = array('firstName'=>'New Name', 'age'=>13);

    $converter->fromArray($person, $array, array('write')); #must specify a or more group

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


