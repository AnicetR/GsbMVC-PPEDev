<?php

namespace App\Models;

use Base;
use Cache;
use DB\SQL\Mapper as Mapper;
use Registry;

class MainModel extends Mapper
{
    protected $f3, $table, $cache, $debugbar;

    public function __construct()
    {
        $this->f3 = Base::instance();
        $this->cache = Cache::instance();

        parent::__construct(Registry::get('db'), $this->table);
    }
}
