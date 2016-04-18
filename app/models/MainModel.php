<?php

namespace App\Models;

use Base;
use Cache;
use DB\SQL\Mapper as Mapper;
use Registry;

/**
 * Class MainModel
 * Model principal
 * @package App\Models
 */
class MainModel extends Mapper
{
    protected $f3, $table, $cache, $debugbar;

    /**
     * MainModel constructor.
     */
    public function __construct()
    {
        $this->f3 = Base::instance();
        $this->cache = Cache::instance();

        parent::__construct(Registry::get('db'), $this->table);
    }
}
