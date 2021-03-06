<?php
/**
 * WhFonts widget class
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package YiiWheels.widgets.formhelpers
 * @uses YiiStrap.helpers.TbArray
 * @uses YiiStrap.helpers.TbHtml
 */
Yii::import('bootstrap.helpers.TbArray');
Yii::import('bootstrap.helpers.TbHtml');

class WhFonts extends CInputWidget
{
	/**
	 * Editor options that will be passed to the editor.
	 *
	 * - family
	 *
	 * @see http://vincentlamanna.com/BootstrapFormHelpers/font.html
	 */
	public $pluginOptions = array();

	/**
	 * @var bool whether to use bootstrap helper select Box widget
	 */
	public $useHelperSelectBox = false;

	/**
	 * @var array extra config options for helper select box
	 */
	public $helperOptions = array();


	/**
	 * Widget's initialization method
	 * @throws CException
	 */
	public function init()
	{

		$this->attachBehavior('ywplugin', array('class' => 'yiiwheels.behaviors.WhPlugin'));

		TbHtml::addCssClass('bfh-fonts', $this->htmlOptions);
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Renders the input file field
	 */
	public function renderField()
	{
		list($name, $id) = $this->resolveNameID();

		TbArray::defaultValue('id', $id, $this->htmlOptions);
		TbArray::defaultValue('name', $name, $this->htmlOptions);

		if ($this->useHelperSelectBox) {
			$select = Yii::createComponent(CMap::mergeArray($this->helperOptions, array(
				'class' => 'yiiwheels.widgets.formhelpers.WhSelectBox',
				'htmlOptions' => $this->htmlOptions,
				'model' => $this->model,
				'attribute' => $this->attribute,
				'name' => $this->name,
				'value' => $this->value,
				'wrapperOptions' => array(
					'class' => 'bfh-fonts',
					'data-family' => $this->hasModel() ? $this->model->{$this->attribute} : $this->value,
				)
			)));
			$select->init();
			$select->run();
		} else {
			$this->htmlOptions['data-family'] = $this->hasModel()
				? $this->model->{$this->attribute}
				: $this->value;

			if ($this->hasModel()) {
				echo CHtml::activeDropDownList($this->model, $this->attribute, array(), $this->htmlOptions);
			} else {
				echo CHtml::dropDownList($name, $this->value, array(), $this->htmlOptions);
			}
		}
	}

	/**
	 * Registers client script
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		$assetsUrl = $this->getAssetsUrl($path);

		/* @var $cs CClientScript */
		$cs = Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/bootstrap-formhelpers.css');
		$cs->registerScriptFile($assetsUrl . '/js/bootstrap-formhelpers-fonts.codes.js');
		$cs->registerScriptFile($assetsUrl . '/js/bootstrap-formhelpers-fonts.js');

		/* initialize plugin */
		if (!$this->useHelperSelectBox) {
			$selector = '#' . TbArray::getValue('id', $this->htmlOptions, $this->getId());
			$this->getApi()->registerPlugin('bfhfonts', $selector, $this->pluginOptions);
		}
	}
}