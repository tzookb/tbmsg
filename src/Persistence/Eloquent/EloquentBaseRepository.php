<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Illuminate\Database\DatabaseManager;

class EloquentBaseRepository
{
    /**
     * @var DatabaseManager
     */
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function commit()
    {
        $this->db->commit();
    }
}