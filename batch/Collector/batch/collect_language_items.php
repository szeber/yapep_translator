<?php
/**
 * Collects the translated language items from the code and inserts those in to the database.
 *
 * @package Collector
 */

namespace Collector;


require __DIR__ . '/../bootstrap.php';


use YapepBase\Application;
use YapepBase\Batch\CliUserInterfaceHelper;
use YapepBase\Batch\BatchScript;
use YapepBase\Exception\I18n\ParameterException;

use Common\Dao\Table\Translation\ProjectTable;

use Collector\BusinessObject\CollectorBo;


/**
 * EventProcessor class
 *
 * @package Collector
 */
class Collector extends BatchScript {

	/**
	 * The name of the project
	 *
	 * @var string
	 */
	protected $project;

	/**
	 * The path to the source code.
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * Business Object for colector.
	 *
	 * @var \Collector\BusinessObject\CollectorBo
	 */
	protected $collectorBo;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->collectorBo = Application::getInstance()->getDiContainer()->getBo('Collector');
	}

	/**
	 * Returns the script's decription.
	 *
	 * This method should return a the description for the script. It will be used as the script description in the
	 * help.
	 *
	 * @return string
	 */
	protected function getScriptDescription() {
		return 'This scripts collects the translated strings from all of the appropriate classes in the given path';
	}

	/**
	 * Sets up the class parameters from the request.
	 *
	 * @return bool   TRUE if all required parameters are available, FALSE on error, or if the help should be shown.
	 */
	protected function prepareSwitches() {
		$helper = new CliUserInterfaceHelper(
			'This scripts collects the translated strings from all of the appropriate classes in the given path');

		$helpUsage   = $helper->addUsage('Show help');
		$fullUsage   = $helper->addUsage('Set up the connection with database options');

		$helper->addSwitch('e', null, 'The running environment. It must be set for non-development environments!',
			array($helpUsage, $fullUsage), false, 'environment');

		$helper->addSwitch('h', 'help', 'Show this help.',
			$helpUsage);

		$helper->addSwitch('p', 'project',
			'The name of the project. Available projectNames are: '
				. implode("\n\t\t", $this->collectorBo->getProjects()),
			$fullUsage, false, 'project');
		$helper->addSwitch('s', 'source', 'The path of the source code.', $fullUsage, false, 'source');


		$args = $helper->getParsedArgs();

		if (false == $args) {
			echo $helper->getUsageOutput();
			return false;
		}

		if (isset($args['help']) || isset($args['h'])) {
			echo $helper->getUsageOutput(true);
			return false;
		}

		$haveError = false;

		if (empty($args['project']) && empty($args['p'])) {
			$haveError = true;
		} else {
			$this->project = (empty($args['project']) ? $args['p'] : $args['project']);
		}

		if (empty($args['source']) && empty($args['s'])) {
			$haveError = true;
		} else {
			$this->source = (empty($args['source']) ? $args['s'] : $args['source']);
		}

		if ($haveError) {
			echo $helper->getUsageOutput();
		}

		return !$haveError;
	}

	/**
	 * Executes the script.
	 */
	protected function execute() {
		$project = $this->collectorBo->getProjectByName($this->project);
		if ($project === false) {
			throw new ParameterException('Unknown project: ' . $this->project);
		}

		$templatePath = rtrim($this->source, '/') . '/View/';
		$controllerPath = rtrim($this->source, '/') . '/Controller/';
		$helperPath = rtrim($this->source, '/') . '/Helper/';

		$this->echoResult('Template', $this->collectorBo->syncTextIds($templatePath, $project[ProjectTable::FIELD_ID]));
		$this->echoResult('Controller', $this->collectorBo->syncTextIds($controllerPath, $project[ProjectTable::FIELD_ID]));
		$this->echoResult('Helper', $this->collectorBo->syncTextIds($helperPath, $project[ProjectTable::FIELD_ID]));
	}

	/**
	 * This function is called, if the process receives an interrupt, term signal, etc. It can be used to clean up
	 * stuff. Note, that this function is not guaranteed to run or it may run after execution.
	 */
	protected function abort() {
		// Do nothing, the script should die by itself.
	}

	/**
	 * Echos the given results.
	 *
	 * @param string $classType   The type of the from where the results was collected.
	 * @param array  $phrases     The collected phrases, where the key is the identifier of the phrase.
	 *
	 * @return void
	 */
	private function echoResult($classType, array $phrases) {
		echo '----New phrases stored from ' . $classType . ': ' . PHP_EOL . PHP_EOL;
		foreach ($phrases as $phraseId => $phrase) {
			echo $phraseId . ' : ' . $phrase . ($phrase == end($phrases) ? '' : PHP_EOL . '-------------' . PHP_EOL);
		}
		echo PHP_EOL . PHP_EOL;
	}
}

$collector = new Collector();
$collector->run();