<?php
/**
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */

namespace Common\Dao\Table\Translation;

/**
 * Table class for the translation table.
 *
 * @package      Common
 * @subpackage   Dao\Table\Translation
 */
class TranslationTable extends \YapepBase\Database\MysqlTable {

	/** id field */
	const FIELD_ID = 'id';

	/** The identifier of the translated phrase */
	const FIELD_PHRASE_ID = 'phrase_id';

	/** The identifier of thetranslations language */
	const FIELD_LANGUAGE_ID = 'language_id';

	/** The translated phrase */
	const FIELD_TRANSLATION = 'translation';

	/** The creation time of the inserted translation entity */
	const FIELD_CREATED_AT = 'created_at';

	/** The identifier of the translator */
	const FIELD_USER_ID = 'user_id';



	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $tableName = 'translation';

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
			self::FIELD_PHRASE_ID,
			self::FIELD_LANGUAGE_ID,
			self::FIELD_TRANSLATION,
			self::FIELD_CREATED_AT,
			self::FIELD_USER_ID,
		);
	}
}
