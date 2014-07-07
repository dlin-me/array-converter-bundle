<?php
/**
 * Created by David Lin
 * Project: snappy
 * Email: david.lin@estimateone.com
 * User: davidlin
 * Date: 7/07/2014
 * Time: 11:09 AM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\Metadata;


use Metadata\Cache\CacheInterface;
use Metadata\Driver\DriverInterface;

class MetadataFactory extends \Metadata\MetadataFactory {


    protected  $cache = null;

    /**
     * @param DriverInterface $driver
     * @param string          $hierarchyMetadataClass
     * @param boolean         $debug
     */
    public function __construct(DriverInterface $driver, $hierarchyMetadataClass = 'Metadata\ClassHierarchyMetadata', $debug = false)
    {
        parent::__construct($driver, $hierarchyMetadataClass, $debug);
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @return null
     */
    public function getCache()
    {
        return $this->cache;
    }



}