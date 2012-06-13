<?php
/**
 * @package   Common
 * @subpackage Dao
 */

namespace Common\Dao;


use YapepBase\Database\DbFactory;
use YapepBase\Exception\Exception;

/**
 * Data access for Translation related data.
 *
 * @package   Common
 * @subpackage Dao
 */
class TranslationDao extends \YapepBase\Dao\DaoAbstract {

	/**
	 * Compares the given phrase list to phrase list in the database.
	 *
	 * @param int   $projectId   The identifier of the project what should be compared to the given list.
	 * @param array $phrases     The list what should be compared to the database.
	 *
	 * @return array   The array of phrases what are in the given list, but are not in the database.
	 */
	public function comparePhrases($projectId, array $phrases) {
		if (empty($phrases)) {
			return array();
		}
		$newPhrases = array();

		$connection = DbFactory::getConnection('translation', DbFactory::TYPE_READ_WRITE);
		$connection->beginTransaction();

		$dropTable = 'DROP TABLE IF EXISTS phrase_temp';

		try {
			// We're creating a temporary table to store the given list on order to able to join
			$createTable = '
				CREATE TEMPORARY TABLE
					phrase_temp
				LIKE
					phrase
			';

			$connection->query($createTable);

			$rows = array();
			$queryParams = array();
			foreach ($phrases as $index => $phrase) {
				$paramNamePhrase = 'phrase_' . $index;
				$paraNameProject = 'projectId_' . $index;

				$rows[] = '(MD5(:_' . $paramNamePhrase . '), :_' . $paramNamePhrase
					. ', :_' . $paraNameProject . ', NOW())';

				$queryParams[$paramNamePhrase] = $phrase;
				$queryParams[$paraNameProject] = $projectId;
			}

			$insert = '
				INSERT INTO
					phrase_temp
					(`key`, phrase, project_id, created_at)
				VALUES
					' . implode(', ', $rows) . '
			';

			$connection->query($insert, $queryParams);

			// Selecting the new phrases
			$query = '
				SELECT
					PT.phrase
				FROM
					phrase P
					RIGHT JOIN
					phrase_temp PT
						ON P.phrase = PT.phrase
						AND
						P.project_id = PT.project_id
				WHERE
					P.id IS NULL
			';

			$newPhrases = $connection->query($query)->fetchColumnAll();

			$connection->query($dropTable);

			$connection->completeTransaction();
		}
		catch (Exception $e) {
			$connection->query($dropTable);
			$connection->completeTransaction();

			throw $e;
		}

		return $newPhrases;
	}

	/**
	 * Inserts the given phrase in to the database.
	 *
	 * @param int    $projectId   The identifier of the project.
	 * @param string $phrase      The phrase itself.
	 *
	 * @return int   The identifier of the created phrase.
	 */
	public function createPhrase($projectId, $phrase) {
		$connection = DbFactory::getConnection('translation', DbFactory::TYPE_READ_WRITE);

		$insert = '
			INSERT INTO
				phrase
				(`key`, phrase, project_id, created_at)
			VALUES
				(:_key, :_phrase, :_projectId, :_createdAt)
		';

		$connection->query($insert, array(
			'key'       => '',
			'phrase'    => $phrase,
			'projectId' => $projectId,
			'createdAt' => date('Y-m-d H:i:s')
		));

		return (int)$connection->lastInsertId();
	}
}