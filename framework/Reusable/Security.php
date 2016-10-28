<?php
declare(strict_types=1);
namespace BarkaneArts\Framework\Reusable;

use BarkaneArts\Framework\FilterSet;
use ParagonIE\AntiCSRF\AntiCSRF;

/**
 * Trait Security
 * @package BarkaneArts\Framework\Reusable
 */
trait Security
{
    /**
     * @var AntiCSRF
     */
    protected $antiCSRF = null;

    /**
     * @var bool
     */
    public $traitSecurity = false;

    /**
     * Populate the properties in any class that uses this trait.
     * This acts like a constructor.
     */
    public function traitSecuritySetup()
    {
        $this->antiCSRF = new AntiCSRF();
        $this->traitSecurity = true;
    }

    /**
     * @param FilterSet|null $filterSet
     * @param bool $ignoreCSRF
     * @return array
     */
    public function getPostData(
        FilterSet $filterSet = null,
        bool $ignoreCSRF = false
    ): array {
        if (!$this->traitSecurity) {
            $this->traitSecuritySetup();
        }
        if ($ignoreCSRF) {
            return $this->getPostDataUnsafe($filterSet);
        } elseif ($this->antiCSRF->validateRequest()) {
            return $this->getPostDataUnsafe($filterSet);
        }
        return [];
    }

    /**
     * Gets the data from $_POST without CSRF checks.
     *
     * @return array
     */
    public function getPostDataUnsafe(FilterSet $filter = null): array
    {
        if (!$this->traitSecurity) {
            $this->traitSecuritySetup();
        }
        if ($filter) {
            return $filter($_POST);
        }
        return $_POST;
    }
}
