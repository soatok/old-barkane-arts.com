<?php
declare(strict_types=1);
namespace BarkaneArts\Framework;
use BarkaneArts\Framework\Reusable\Security;
use \ParagonIE\EasyDB\EasyDB;

/**
 * Class Model
 * @package BarkaneArts\Framework
 */
abstract class Model
{
    use Security;

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
        $this->traitSecuritySetup();
    }

    /**
     * All models should specify an install methoid that creates the
     * tables necessary.
     */
    abstract public function install();
}
