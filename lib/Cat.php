<?php

class Cat {
    const TOP_MARGIN = 20;
    const LINE_HEIGHT = 100;
    const CHILD_MARGIN_TOP = 30;
    const TEXT_MARGIN_TOP = 30;
    const TEXT_MARGIN_LEFT = 50;

    /**
     * @var int
     */
    public static $current_y = self::TOP_MARGIN + self::LINE_HEIGHT;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $stopDate;

    /**
     * @var Cat
     */
    public $parent;

    /**
     * @var Cat[]
     */
    public $childs = array();

    /**
     * @var float
     */
    public $pos_x;

    /**
     * @var float
     */
    public $pos_y;

    /**
     * @var boolean
     */
    public $current;

    /**
     * Cat constructor.
     * @param $name
     * @param $image
     * @param string $parent
     * @param $start
     * @param $stop
     * @param boolean $current
     */
    public function __construct($name, $image, $parent, $start, $stop, $current)
    {
        $this->name = $name;
        $this->image = $image;
        $this->startDate = $start;
        $this->stopDate = $stop;
        $this->current = $current;
        if($parent) {
            $this->parent = CatManager::getInstance()->cats[$parent];
            $this->parent->addChild($this);
        }
        $this->pos_x = $this->getStartToPixel();
    }

    /**
     * @return string tri hex color
     */
    public function getColor()
    {
        $code = dechex(crc32(md5($this->name)));
        $code = substr($code, 0, 6);
        return $code;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return 'img/' . $this->image;
    }

    /**
     * @return int
     */
    public function getStartToPixel()
    {
        $diff = strtotime($this->startDate) - strtotime(CatManager::START_YEAR . '-01-01');
        $yearDiff = $diff / 31536000;
        return (int)round($yearDiff * CatManager::PIXEL_PER_YEAR);
    }

    /**
     * @return int
     */
    public function getLifeToPixel()
    {
        if(!$this->stopDate) {
            if($this->current) {
                $date = strtotime(date('Y-m-d'));
            } else {
                if(count($this->childs) > 0) {
                    $lastChild = $this->childs[count($this->childs) - 1];
                    $date = strtotime($lastChild->startDate) + 31536000;
                } else {
                    $date = strtotime($this->startDate) + 31536000 * 3;
                }
            }
        } else {
            $date = strtotime($this->stopDate);
        }
        $diff = $date - strtotime($this->startDate);
        $yearDiff = $diff / 31536000;
        return (int)round($yearDiff * CatManager::PIXEL_PER_YEAR);
    }

    public function getLineWidth()
    {
        return max(2, 10 - $this->getParentNumber());
    }

    public function getParentNumber()
    {
        $parents = 1;
        if($this->parent)
            $parents += $this->parent->getParentNumber();
        return $parents;
    }

    /**
     * @param Cat $cat
     */
    public function addChild(Cat $cat)
    {
        $this->childs[] = $cat;
    }

    public function generateSVG()
    {
        self::$current_y += self::CHILD_MARGIN_TOP;
        $this->pos_y = self::$current_y;
        self::$current_y += self::LINE_HEIGHT;

        CatManager::getInstance()->lines[] = SVGDraw::drawLine($this->pos_x, $this->pos_y, $this->pos_x + $this->getLifeToPixel(), $this->pos_y, $this->getColor(), $this->getLineWidth());
        CatManager::getInstance()->texts[] = SVGDraw::drawText($this->pos_x + self::TEXT_MARGIN_LEFT, $this->pos_y + self::TEXT_MARGIN_TOP, $this->getColor(), $this->name, 24);

        if(!$this->stopDate && !$this->current)
            CatManager::getInstance()->texts[] = SVGDraw::drawText($this->pos_x + $this->getLifeToPixel(), $this->pos_y, $this->getColor(), '?');

        if($this->parent) {
            CatManager::getInstance()->lines[] = SVGDraw::drawLine($this->pos_x, $this->pos_y, $this->pos_x, $this->parent->pos_y, $this->parent->getColor(), $this->parent->getLineWidth());
            CatManager::getInstance()->circles[] = SVGDraw::drawCircle($this->pos_x, $this->parent->pos_y, 7, $this->parent->getColor());
        }
        CatManager::getInstance()->circles[] = SVGDraw::drawCircle($this->pos_x, $this->pos_y, 50, $this->getColor());
        CatManager::getInstance()->circles[] = SVGDraw::drawCircle($this->pos_x, $this->pos_y, 50, $this->getColor(), $this->getImage());

        if(count($this->childs) > 0)
            foreach($this->childs as $child)
                $child->generateSVG();
    }

    public function recursiveSort()
    {
        usort($this->childs, function(Cat $a, Cat $b) {
            return strcmp($a->startDate, $b->startDate);
        });
        foreach($this->childs as $child)
            $child->recursiveSort();
    }
}