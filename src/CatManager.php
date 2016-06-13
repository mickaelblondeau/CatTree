<?php

namespace App;

class CatManager
{
    const START_YEAR = 1980;
    const PIXEL_PER_YEAR = 100;

    /**
     * @var CatManager
     */
    public static $instance;

    /**
     * @var Cat[]
     */
    public $rootCats;

    /**
     * @var Cat[]
     */
    public $cats;

    /**
     * @var array
     */
    public $lines;

    /**
     * @var array
     */
    public $circles;

    /**
     * @var array
     */
    public $texts;

    /**
     * @var int
     */
    public $maxX = 0;

    /**
     * @var int
     */
    public $maxY = 0;

    /**
     * @return CatManager
     */
    public static function getInstance()
    {
        if(!self::$instance)
            self::$instance = new CatManager();
        return self::$instance;
    }

    public function loadCats($file)
    {
        $json = file_get_contents(__DIR__ . '/../web/' . $file);
        $cats = json_decode($json);
        foreach($cats as $cat)
            $this->addCat($cat->name, $cat->img, $cat->parent, $cat->birth, $cat->death, $cat->current);
    }

    /**
     * @param string $name
     * @param string $image
     * @param Cat $parent
     * @param string $start
     * @param string $stop
     * @param boolean $current
     * @return Cat
     */
    public function addCat($name, $image, $parent, $start, $stop, $current = false)
    {
        $cat = new Cat($name, $image, $parent, $start, $stop, $current);
        if(!$parent) {
            $this->rootCats[] = $cat;
        }
        $this->cats[$name] = $cat;
        return $cat;
    }

    public function sortCats()
    {
        usort($this->rootCats, function(Cat $a, Cat $b) {
            return strcmp($a->startDate, $b->startDate);
        });

        foreach($this->rootCats as $cat)
            $cat->recursiveSort();
    }

    /**
     * @return string
     */
    public function renderTree()
    {
        foreach($this->rootCats as $cat)
            $cat->generateSVG();
        return $this->drawLines() . $this->drawCircles() . $this->drawTexts();
    }

    /**
     * @return string
     */
    public function drawLines()
    {
        $svg = '';

        $this->lines = array_reverse($this->lines);

        foreach($this->lines as $line)
            $svg .= $line;

        return $svg;
    }

    /**
     * @return string
     */
    public function drawCircles()
    {
        $svg = '';

        foreach($this->circles as $circle)
            $svg .= $circle;

        return $svg;
    }

    public function drawTexts()
    {
        $svg = '';

        foreach($this->texts as $text)
            $svg .= $text;

        return $svg;
    }

    /**
     * @return string
     */
    public function renderDates()
    {
        $svg = '';

        $years = date('Y') + 1 - self::START_YEAR;
        $segPerYear = 12;
        for($i = 0; $i < $years; $i++) {
            $x = $i * self::PIXEL_PER_YEAR;
            $svg .= SVGDraw::drawLine($x, 25, $x, 10000, 'eaeaea', 0.3);
            $svg .= SVGDraw::drawLine($x, 25, $x, 40, 'cccccc');
            $svg .= SVGDraw::drawText($x, 20, 'cccccc', self::START_YEAR + $i);
            for($j = 0; $j < $segPerYear; $j++) {
                $x2 = $x + $j * self::PIXEL_PER_YEAR / $segPerYear;
                $svg .= SVGDraw::drawLine($x2, 25, $x2, 30, 'cccccc');
            }
        }

        $todayX = (((strtotime(date('Y-m-d')) - strtotime(date('Y') . '-01-01')) / 31536000) + $i - 1) * self::PIXEL_PER_YEAR;
        $svg .= SVGDraw::drawLine($todayX, 25, $todayX, 10000, '000000');
        $this->maxX = (int)$todayX + 100;

        return $svg;
    }
}