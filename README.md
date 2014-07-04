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
        new Dlin\Bundle\ArrayConversionBuddle\DlinSArrayConversionBundle(),
        ...
    }


Configuration
--------------

You can specify the installation location of wkhtmltopdf

    #app/config/config.yml

    dlin_snappy:
        pdf_service:
            wkhtmltopdf: /Applications/wkhtmltopdf.app/Contents/MacOS/wkhtmltopdf


For most OS, this bundle will try to download and install the wkhtmltopdf binary itself. No configuration is required unless you want to use a different wkhtmltopdf binary. For Mac servers, one will have to download the DMG file and install it. The above configuration is required.


Usage
--------------

Geting the service in a controller

    $pdf =  $this->get('dlin.pdf_service');

Getting the service in a ContainerAwareService

    $pdf = $this->container->get('dlin.pdf_service');

Using the method "createPdfFromHtml"

    #Pdf will be created (replace if already exist) as file '/tmp/test.pdf'
    $pdf->createPdfFromHtml('<html><body><h1>hello</h1></body>', '/tmp/test.pdf');


Using the method "createPdfFromUrl"

    #Pdf will be created (replace if already exist) as file '/tmp/test.pdf'
    $pdf->createPdfFromUrl('google.com', '/tmp/test.pdf');


Download to browser (HTTP headers will be set and script terminates)

    $pdf->sendHtmlAsPdf('<html><body><h1>hello</h1></body>', 'downloadFileName.pdf');
    #or
    $pdf->sendUrlAsPdf('google.com', 'downloadFileName.pdf');


Show PDF inline in browser (HTTP headers will be set and script terminates)

    $pdf->sendHtmlAsPdf('<html><body><h1>hello</h1></body>', 'downloadFileName.pdf', true);
    #or
    $pdf->sendUrlAsPdf('google.com', 'downloadFileName.pdf', true);


Notes
--------------
* MAMP user couldl have problem using wkhtmltopdf. Please solve the problem [here](http://oneqonea.blogspot.in/2012/04/why-does-wkhtmltopdf-work-via-terminal.html)
* Mac OSX requires its own wkhtmltopdf binnary. You can download it [here](https://code.google.com/p/wkhtmltopdf/downloads/list).





License
-

MIT

*Free Software, Yeah!*


