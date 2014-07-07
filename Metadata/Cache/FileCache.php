<?php
/**
 * Created by David Lin
 * Project: snappy
 * Email: david.lin@estimateone.com
 * User: davidlin
 * Date: 7/07/2014
 * Time: 11:00 AM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Metadata\Cache;


class FileCache extends \Metadata\Cache\FileCache{


    protected $dir;

    public function __construct($dir){
        $this->dir = $dir;
        parent::__construct($dir);
    }


    public function getCachePath( $className)
    {
        return $this->dir.'/'.strtr($className, '\\', '-').'.cache.php';
    }

}