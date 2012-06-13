<?php
/**
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */

namespace Common\Dao\Table\Translation;

/**
 * Table class for the user table.
 *
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */
class UserTable extends \YapepBase\Database\MysqlTable {

	/** id field */
	const FIELD_ID = 'id';

	/** The name of the user */
	const FIELD_NAME = 'name';

	/** Flag which indicates the status of the user (1 - can log in, 0 - banned) */
	const FIELD_IS_ENABLED = 'is_enabled';

	/** Flag which indicates that the user is an administrator) */
	const FIELD_IS_ADMIN = 'is_admin';



	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $tableName = 'user';

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
			self::FIELD_IS_ENABLED,
			self::FIELD_IS_ADMIN,
		);
	}
}
