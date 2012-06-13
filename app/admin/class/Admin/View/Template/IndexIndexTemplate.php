<?php
/**
 * @package    Admin
 * @subpackage View\Template
 */

namespace Admin\View\Template;

/**
 * @package    Admin
 * @subpackage View\Template
 */
class IndexIndexTemplate extends \YapepBase\View\TemplateAbstract {

	/**
	 * Does the actual rendering.
	 */
	protected function renderContent() {
// ------------------- HTML ------------------- ?>

<?=$this->_('This is just a test page at the moment') ?>
<?=$this->_('You can find here absolutely nothing.') ?>

<?php // ------------------- /HTML ------------------
	}
}