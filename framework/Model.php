<?php
declare(strict_types=1);
namespace BarkaneArts\Framework;
use \ParagonIE\EasyDB\EasyDB;

/**
 * Class Model
 * @package BarkaneArts\Framework
 */
class Model
{
    /**
     * @var EasyDB
     */
    protected $db = null;

    /**
     * Model constructor.
     * @param EasyDB $db
     */
    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }
}
