<?php

namespace common\models;

class Tools
{
    public static function show_size($f, $format = true)
    {
        if ($format) {
            $size = self::show_size($f, false);
            if ($size <= 1024) return $size . ' bytes';
            else if ($size <= 1024 * 1024) return round($size / (1024), 2) . ' Kb';
            else if ($size <= 1024 * 1024 * 1024) return round($size / (1024 * 1024), 2) . ' Mb';
            else if ($size <= 1024 * 1024 * 1024 * 1024) return round($size / (1024 * 1024 * 1024), 2) . ' Gb';
            else if ($size <= 1024 * 1024 * 1024 * 1024 * 1024) return round($size / (1024 * 1024 * 1024 * 1024), 2) . ' Tb'; //:)))
            else return round($size / (1024 * 1024 * 1024 * 1024 * 1024), 2) . ' Pb'; // ;-)
        } else {
            if (is_file($f)) return filesize($f);
            $size = 0;
            $dh = opendir($f);
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..') continue;
                if (is_file($f . '/' . $file)) $size += filesize($f . '/' . $file);
                else $size += self::show_size($f . '/' . $file, false);
            }
            closedir($dh);
            return $size + filesize($f); // +filesize($f) for *nix directories
        }
    }

    public static function removeTimeout()
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
    }

    public static function generateRandomNumbers($count, $max)
    {
        $array = [];
        $min = 1;
        $i = 0;
        while ($i < $count) {
            $num = mt_rand($min, $max);
            if (!in_array($num, $array)) {
                $array[$i] = $num;
                $i++;
            }
        }

        return $array;
    }

    public static function deleteDuplicateWords($string)
    {
        $string = mb_strtolower($string, 'utf-8');
        $parts = explode(' ', $string);
        $parts = array_unique($parts);
        $string = implode(' ', $parts);

        return $string;
    }

    public static function uppercaseFirstLetter($string)
    {
        $first = mb_substr($string, 0, 1, 'UTF-8');
        $last = mb_substr($string, 1);
        $first = mb_strtoupper($first, 'UTF-8');
        $last = mb_strtolower($last, 'UTF-8');
        $string = $first . $last;

        return $string;
    }

    public static function timeToSeconds($time)
    {
        $parts = explode(':', $time);
        if (count($parts) === 2) {
            return $parts[0] * 60 + $parts[1];
        }

        if (count($parts) === 3) {
            return $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
        }

        return 0;
    }
}