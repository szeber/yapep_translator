<?php
/**
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */

namespace Common\Dao\Table\Translation;

/**
 * Table class for the project table.
 *
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */
class ProjectTable extends \YapepBase\Database\MysqlTable {

	/** id field */
	const FIELD_ID = 'id';

	/** The name of the project */
	const FIELD_NAME = 'name';

	/** The creation time of the inserted project entity */
	const FIELD_CREATED_AT = 'created_at';


	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $tableName = 'project';

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
			self::FIELD_NAME,
			self::FIELD_CREATED_AT,
		);
	}
}
