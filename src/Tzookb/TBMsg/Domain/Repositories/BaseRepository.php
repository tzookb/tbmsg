<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:17
 */

namespace Tzookb\TBMsg\Domain\Repositories;


interface BaseRepository
{
    public function beginTransaction();
    public function rollBack();
    public function commit();
}