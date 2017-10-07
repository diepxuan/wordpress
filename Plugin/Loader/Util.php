<?php

namespace Diepxuan\WordPress\Plugin\Loader;

class Util
{

    public function rel_path(
        $from,
        $to,
        $ps = DIRECTORY_SEPARATOR
    ) {
        $arFrom = explode($ps, rtrim($this->normalize($from, $ps), $ps));
        $arTo   = explode($ps, rtrim($this->normalize($to, $ps), $ps));
        while (count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0])) {
            array_shift($arFrom);
            array_shift($arTo);
        }
        return str_pad('', count($arFrom) * 3, '..' . $ps) . implode($ps, $arTo);
    }

    public function normalize(
        $path,
        $ds
    ) {
        return str_replace(array('/', '\\'), $ds, $path);
    }

}
