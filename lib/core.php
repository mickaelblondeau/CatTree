<?php

include 'Cat.php';
include 'CatManager.php';

CatManager::getInstance()->loadCats('cats.json');

function drawLine($x1, $y1, $x2, $y2, $color) {
    return '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" style="stroke:#' . $color . ';stroke-width:2" />';
}

function drawCircle($x, $y, $r, $color, $image = null) {
    if($image) {
        return '
            <defs>
                <pattern id="img'. $color .'" x="0%" y="0%" height="100%" width="100%" viewBox="0 0 512 512">
                    <image x="0%" y="0%" width="512" height="512" xlink:href="' . $image . '"></image>
                </pattern>
            </defs>
            <circle cx="' . $x . '" cy="' . $y . '" r="' . $r . '" stroke="#' . $color . '" stroke-width="2" fill="url(#img'. $color .')" />
        ';
    } else {
        return '<circle cx="' . $x . '" cy="' . $y . '" r="' . $r . '" stroke="#' . $color . '" stroke-width="2" fill="#ffffff" />';
    }
}

function drawText($x, $y, $color, $text) {
    return '<text x="' . $x . '" y="' . $y . '" fill="#' .$color . '" font-family="Comic Sans MS" font-size="18">' . $text . '</text>';
}