<?php
/**
 * Tournament Extension for phpBB 3.3.5
 * Compatibility layer for Relax Arcade 1.0.28
 */

namespace kikileharani\tournament;

class ext extends \phpbb\extension\base
{
    /**
     * Checks if the extension can be enabled.
     */
    public function is_enableable()
    {
        return true;
    }
}
