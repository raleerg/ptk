<?php
namespace App\Helper;

/**
 * Class Helper
 * @package App\Helper
 */
class Helper
{
    /**
     * Get string between two chars.
     *
     * @param $content
     * @param $start
     * @param $end
     * @return string
     */
    static public function getBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }

        return '';
    }
}