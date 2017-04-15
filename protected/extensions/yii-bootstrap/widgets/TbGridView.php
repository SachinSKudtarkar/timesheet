<?php

/**
 * TbGridView class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.widgets
 */
Yii::import('zii.widgets.grid.CGridView');
Yii::import('bootstrap.widgets.TbDataColumn');

/**
 * Bootstrap Zii grid view.
 */
class TbGridView extends CGridView {
    // Table types.

    const TYPE_STRIPED = 'striped';
    const TYPE_BORDERED = 'bordered';
    const TYPE_CONDENSED = 'condensed';
    const TYPE_HOVER = 'hover';
    
    /**
     * @var string|array the table type.
     * Valid values are 'striped', 'bordered' and/or 'condensed'.
     */
    public $enableLegend = false;
    public $legend;
    
    public $legendText;
    
    public $legendCssClass;
    
    /**
     * @var string|array the table type.
     * Valid values are 'striped', 'bordered' and/or 'condensed'.
     */
    public $type;

    /**
     * @var string the CSS class name for the pager container. Defaults to 'pagination'.
     */
    public $pagerCssClass = 'pagination';

    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
     */
    public $pager = array('class' => 'bootstrap.widgets.TbPager');
     /**
     * @var array the configuration for the pagercustom.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPagerCustom')</code>.
     */
    public $pagerCustom = array('class' => 'bootstrap.widgets.TbPagerCustom');

    /**
     * @var string the URL of the CSS file used by this grid view.
     * Defaults to false, meaning that no CSS will be included.
     */
    public $cssFile = false;

    /**
     * Initializes the widget.
     */
    public function init() {
        parent::init();
        $classes = array('table');

        if (isset($this->type)) {
            if (is_string($this->type))
                $this->type = explode(' ', $this->type);

            if (!empty($this->type)) {
                $validTypes = array(self::TYPE_STRIPED, self::TYPE_BORDERED, self::TYPE_CONDENSED, self::TYPE_HOVER);

                foreach ($this->type as $type) {
                    if (in_array($type, $validTypes))
                        $classes[] = 'table-' . $type;
                }
            }
        }

        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->itemsCssClass))
                $this->itemsCssClass .= ' ' . $classes;
            else
                $this->itemsCssClass = $classes;
        }
    }
    public function renderLegend()
    { 
        if (!$this->enableLegend)
            return;
        echo '<div class="'.$this->legendCssClass.'">';
        echo $this->legendText;
         echo '</div>';
    }
    /*
     * This Method used for raner custom Page If applicable
     */
    public function renderPagerCustom() {
        if (!$this->enablePagination)
            return;

        $pagerCustom = array();
        $class = 'CLinkPager';
        if (is_string($this->pagerCustom))
            $class = $this->pagerCustom;
        elseif (is_array($this->pagerCustom)) {
            $pagerCustom = $this->pagerCustom;
            if (isset($pagerCustom['class'])) {
                $class = $pagerCustom['class'];
                unset($pagerCustom['class']);
            }
        }
        $pagerCustom['pages'] = $this->dataProvider->getPagination();

        if ($pagerCustom['pages']->getPageCount() > 1) {
            echo '<div class="' . $this->pagerCssClass . '">';
            $this->widget($class, $pagerCustom);
            echo '</div>';
        }
        else
            $this->widget($class, $pagerCustom);
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns() {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class']))
                $this->columns[$i]['class'] = 'bootstrap.widgets.TbDataColumn';
        }

        parent::initColumns();
    }

    /**
     * Creates a column based on a shortcut column specification string.
     * @param mixed $text the column specification string
     * @return \TbDataColumn|\CDataColumn the column instance
     * @throws CException if the column format is incorrect
     */
    protected function createDataColumn($text) {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
            throw new CException(Yii::t('zii', 'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));

        $column = new TbDataColumn($this);
        $column->name = $matches[1];

        if (isset($matches[3]) && $matches[3] !== '')
            $column->type = $matches[3];

        if (isset($matches[5]))
            $column->header = $matches[5];

        return $column;
    }

}
