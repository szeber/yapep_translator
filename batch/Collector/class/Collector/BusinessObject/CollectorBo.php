<?php
/**
 * @package    Collector
 * @subpackage BusinessObject
 */

namespace Collector\BusinessObject;

use YapepBase\Application;
use YapepBase\Database\MysqlTable;

use Common\Dao\Table\Translation\ProjectTable;

/**
 * Business logic for collecting the translatable items from a YapepBase project.
 *
 * @package    Collector
 * @subpackage BusinessObject
 */
class CollectorBo extends \YapepBase\BusinessObject\BoAbstract {

	/**
	 * Returns the names of the existing projects.
	 *
	 * @return array
	 */
	public function getProjects() {
		$projectTable = new ProjectTable();

		$projects = $projectTable->select(array(), ProjectTable::FIELD_NAME, MysqlTable::ORDER_ASC);

		$result = array();
		foreach ($projects as $project) {
			$result[] = $project[ProjectTable::FIELD_NAME];
		}

		return $result;
	}

	/**
	 * Returns the detailes of the goven project.
	 *
	 * @param string $projectName   The name of the project.
	 *
	 * @return array|bool   An associative array which contains the details of the project,
	 *                      or FALSE if there's no project in the Database with the goven name.
	 */
	public function getProjectByName($projectName) {
		$projectTable = new ProjectTable();

		return $projectTable->selectOne(array(ProjectTable::FIELD_NAME => $projectName));
	}

	/**
	 * Synchrnizes the collected textdIds from the files with the database.
	 *
	 * @param string $directory   The path of the directory.
	 * @param int    $projectId   The identifier of the porject.
	 *
	 * @return array   The textIds what weren't in the database.
	 */
	public function syncTextIds($directory, $projectId) {
		$phrases = array();

		$files = $this->getFiles($directory);

		foreach ($files as $filePath) {
			$source = @file_get_contents($directory . $filePath);
			$tokens = array_reverse(token_get_all($source));

			while (($token = array_pop($tokens)) !== null) {
				if (!is_string($token) && $token[0] === T_STRING) {

					if ($token[1] == '_') {
						$phrases[] = $this->processTranslatorFunction($tokens);
					}
				}
			}
		}

		$newPhrases = $this->getTranslationDao()->comparePhrases($projectId, $phrases);

		$result = array();
		foreach ($newPhrases as $phrase) {
			$phraseId = $this->getTranslationDao()->createPhrase($projectId, $phrase);
			$result[$phraseId] = $phrase;
		}

		return $result;
	}

	/**
	 * Processes the translator function calls.
	 *
	 * @param array $tokens   An array which contains the tokens from the call of the function till the end of the file.
	 *                        The processed tokens will be removed from the array.
	 *
	 * @return bool
	 */
	private function processTranslatorFunction(&$tokens) {
		// We read the whole function call as a string
		$depth = 0;
		$functionText = '_';
		while (($token = array_pop($tokens)) !== null) {
			$tokenStr = is_array($token) ? $token[1] : $token;
			$functionText .= $tokenStr;
			// We're counting the opening and closing parentheses to know where is the end of the function call
			if ($tokenStr == '(' || $tokenStr == ')') {
				$depth += ($tokenStr == ')' ? -1 : 1);
			}
			// We're breaking the cycle at the end of the function call
			if ($depth == 0) {
				break;
			}
		}
		// Elemezzuk a fuggvenyt - ha tudjuk eloforditani, akkor megtesszuk
		$matches = array();

		$result = false;
		if (preg_match('#^
					_\(\s*
					(\'|")(?P<id>.+)(?<!\\\\)\1
					\s*
					(?:(?:,\s*(?P<params>array\(.*))?\))
					$#xs',
			$functionText,
			$matches)) {

			$result = $matches['id'];
		}
		return $result;
	}

	/**
	 * Returns the list of the files in the given directory.
	 *
	 * @param string $directory   The path of the directory.
	 * @param string $subPath     The relative path in the given directory to narrow down the list.
	 *
	 * @return array
	 */
	private function getFiles($directory, $subPath = '') {
		$result = array();
		$path = $directory . $subPath;

		if (is_dir($path) && (($files = scandir($path)) !== false)) {
			foreach ($files as $file) {
				if (in_array($file, array('.', '..'))) {
					continue;
				}
				elseif (is_dir($path.$file)) {
					$result = array_merge($result, $this->getFiles($directory, $subPath . $file . '/'));
				}
				else {
					$result[] = $subPath . $file;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns the TranslationDao
	 *
	 * @return \Common\Dao\TranslationDao
	 */
	protected function getTranslationDao() {
		return Application::getInstance()->getDiContainer()->getDao('Translation');
	}
}