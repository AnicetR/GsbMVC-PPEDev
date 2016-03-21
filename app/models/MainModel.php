<?php
namespace App\Models;

use DB\SQL\Mapper as Mapper;
use Base;
use Registry;
use Cache;

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