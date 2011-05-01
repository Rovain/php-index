<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Defines the class SplitCounter
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.
 *
 * @category  Structures
 * @package   index
 * @author    Markus Malkusch <markus@malkusch.de>
 * @copyright 2011 Markus Malkusch
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://php-index.malkusch.de/en/
 */

/**
 * Namespace
 */
namespace de\malkusch\index\test;
use de\malkusch\index as index;

/**
 * Counts the splits in a binary search
 *
 * @category Structures
 * @package  index
 * @author   Markus Malkusch <markus@malkusch.de>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: 1.0
 * @link     http://php-index.malkusch.de/en/
 */
class SplitCounter implements \Countable
{

    const
    /**
     * Splitting context
     */
    CONTEXT_SPLITTING = "splitting",
    /**
     * Searching context
     */
    CONTEXT_SEARCHING = "searching";

    private
    /**
     * @var string
     */
    $_context = self::CONTEXT_SEARCHING,
    /**
     * @var int
     */
    $_count = 0;

    /**
     * Starts the counting for the splits
     */
    public function __construct()
    {
        \register_tick_function(array($this, "countSplit"));
        declare(ticks=1);
    }

    /**
     * Tick handler for counting splits
     *
     * @return void
     */
    public function countSplit()
    {
        $backtrace = \debug_backtrace(false);
        if (\strpos($backtrace[1]["function"], "split") !== false) {
            if ($this->_context == self::CONTEXT_SEARCHING) {
                $this->_context = self::CONTEXT_SPLITTING;
                $this->_count++;

            }
        } elseif (\strpos($backtrace[1]["function"], "search") !== false) {
            $this->_context = self::CONTEXT_SEARCHING;

        }
    }

    /**
     * Returns the counted splits
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Stops counting
     *
     * @return void
     */
    public function stopCounting()
    {
        \unregister_tick_function(array($this, "countSplit"));
    }

}