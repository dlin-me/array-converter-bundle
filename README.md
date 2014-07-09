Dlin Array Conversion Bundle
=========
Dlin Symfony Array Conversion Bundle

Building a RESTFul API with Symfony2 ? You might have tried the **FriendsOfSymfony/FOSRestBundle** Bundle.

If you are like me, you will probably think that FriendsOfSymfony/FOSRestBundle is awesome but it is complicated and it does not support the flexibility you really want.


Let's think again what you really need for building a RESTful API:

* **Content Negotiation**

  Do you really need that ? All my recently built APIs support only JSON, no negotiation.

* **Resource URL Routing**
  If you don't need content negotiation, the good news is that Symfony itself does the routing very well, you can simple use normal controller for API endpoints.

* **Serialization**, i.e. convert to array and then JSON

  This is important. You really need this not only for Doctrine Entities but also any objects.

  You sometimes want to wrap your response with extra details, e.g. metadata or error details.

  You sometimes want to also expose data through getter functions.

  You sometimes want to use a different name as the key

  Symfony2 comes with the **JsonResponse** class that automatically converts  an associate array into JSON object response body. What is missing, is the part that converts your entity/object into associate array. Dlin/Converter can help.

* **Accepting Request to Updata Resource**

  You also want to be able to process submitted data from User and update the Entity objects easily

  You want to 'hydrate' an entity/object easily using data submitted from browser. However, you normally need to manually and repeatly assign values to Entities using the many setter functions, after validating the user inputs.

  Symfony2 has built-in support for validating Entities, what is missing, is an easy way to 'hydrate' entities.  Dlin/Converter can help.


* **Permission Control**

  Some fields of a given resource can only be accessible by users in specific roles. For example, only Admin users can update Company's name.

  Sometimes you want to control what is readable/writable based on some conditions.

  Like the FOSRestBundle, Dlin/ArrayConverter support assigning '**groups**' to fields. When converting an Entity to an array or 'hydrating' an Entity from an array, you have the options to specify what fields are readable/writable by norminating what groups to include and/or exclude. You can even get more controls  by specifying what fields to be included and/or exlcuded.




Version
-

0.2


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
        new Dlin\Bundle\ArrayConversionBuddle\DlinArrayConversionBundle(),
        ...
    }


Annotation
--------------

There aer only two annotation options: '**groups**' and '**key**'.

The annotation is applicable to all properties and methods, **public or not**.

If a method is annotated it will be called with no parameter when converting to an array. The returned value is used as the field value of the resulting array, while the method name by default will be used as the array index. When 'hydrating' an Entity/object, the method is called with one parameter.
It is normal to add a getter function to a 'read' group, and a setter functio to a 'write' group. However, the definition of groups are entirely up to you, the developer.

If an property is annotated, it will be get/set when converting between the Entity and array. Please note that private properties will also be updated.

One can use the **key** option to specify a diffent key name for the property or method, and the **group** option to group multiple properties together to be refered later for permission control.

If multiple property or method share the same key. The last one will overwrite others when converting **toArray**. However, all fields and methods will be set/called when 'hydrating' **fromArray**.

Example:

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

###Using the method "toArray"

The "**toArray**" method converts an annotated object into an array. It accepts 3 parameters:

1. The object
2. Array of group names. properties with matching group name with go to the result array. You can prefix a group name with '-' to exclude fields with that group. E.g. ['user', '-adminuser'] will include properties
   marked as in the **user** group but not in the **adminuser** group. If a property is marked as in both groups, it will **NOT** go into the resulting array.
3. Array of property keys to include/exclude. You can override the group selection by passing this to the 'toArray' method. E.g. ['username', '-password'] will include the property 'username' and exclude 'password' regardless how group names match.

4. A boolean value. Skip properties with null/false/empty values. Default is true.

Examples

    $person = new PersonEntity();
    $person->setFirstName('Hello');
    $person->setLastName('Kitty');
    $person->setAge(12);

	#at least one group must given, otherwise empty array returns
    $res = $converter->toArray($person, array('read'));

    //$this->assertEquals($res['firstName'], $person->getFirstName());
    //$this->assertEquals($res['last'], $person->getLastName());
    //$this->assertEquals($res['fullName'], $person->getFullName());


###Using the method "fromArray"

The "**fromArray**" method 'hydrate' an object using values of a given array. It accepts 2 parameters

1. The object/Entity
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





License
-

MIT

*Free Software, Yeah!*


