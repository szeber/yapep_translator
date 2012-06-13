<?php
/**
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */

namespace Common\Dao\Table\Translation;

/**
 * Table class for the phrase table.
 *
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */
class PhraseTable extends \YapepBase\Database\MysqlTable {

	/** id field */
	const FIELD_ID = 'id';

	/** The key of the prase, generated from the phrase */
	const FIELD_KEY = 'key';

	/** The phrase */
	const FIELD_PHRASE = 'phrase';

	/** The identifier of the project which the phrase belongs */
	const FIELD_PROJECT_ID = 'project_id';

	/** The creation time of the inserted phrase entity */
	const FIELD_CREATED_AT = 'created_at';



	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $tableName = 'phrase';

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
			self::FIELD_KEY,
			self::FIELD_PHRASE,
			self::FIELD_PROJECT_ID,
			self::FIELD_CREATED_AT,
		);
	}
}
