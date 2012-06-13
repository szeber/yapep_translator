<?php
/**
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */

namespace Common\Dao\Table\Translation;

/**
 * Table class for the language table.
 *
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */
class LanguageTable extends \YapepBase\Database\MysqlTable {

	/** id field */
	const FIELD_ID = 'id';

	/** The ISO 639-1 Code of the language */
	const FIELD_CODE = 'code';

	/** The english name of the language */
	const FIELD_NAME = 'name';



	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $tableName = 'language';

	/**
	 * The default connection name what should be used for the database connection.
	 *
	 * @var string
	 */
	protected $defaultDbConnectionName = 'translation';

	/**
	 * Returns the fields of the table.
	 *
	 * @return array
	 */
	public function getFields() {
		return array(
			self::FIELD_ID,
			self::FIELD_CODE,
			self::FIELD_NAME,
		);
	}
}
