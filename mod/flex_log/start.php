<?php
/**
 * Flex Log
 *
 *
 * @author Sebastien Routier
 * @license http://opensource.org/licenses/GPL-3.0 (GPL-3.0)
 */

class FlexLog {
	private $logLevel = FlexLogLevel::ERROR;

	public function __construct($level) {
		if (is_numeric($level)) {
			$this->logLevel = $level;
		}
	}

	public function debug($msg) {
		$rc = NULL;

		if ($this->logLevel <= FlexLogLevel::DEBUG) {
			$rc = self::log($msg, FlexLogLevel::DEBUG);
		}
		return $rc;
	}

	public function info($msg) {
		$rc = NULL;

		if ($this->logLevel <= FlexLogLevel::INFO) {
			$rc = self::log($msg, FlexLogLevel::INFO);
		}
		return $rc;
	}

	public function warn($msg) {
		$rc = NULL;

		if ($this->logLevel <= FlexLogLevel::WARN) {
			$rc = self::log($msg, FlexLogLevel::WARN);
		}
		return $rc;
	}

	public function error($msg) {
		$rc = NULL;

		if ($this->logLevel <= FlexLogLevel::ERROR) {
			$rc = self::log($msg, FlexLogLevel::ERROR);
		}
		return $rc;
	}

	public function fatal($msg) {
		$rc = NULL;

		if ($this->logLevel <= FlexLogLevel::FATAL) {
			$rc = self::log($msg, FlexLogLevel::FATAL);
		}
		return $rc;
	}

	private function log($msg, $level) {
		$elggLevel = FlexLogLevel::getElggLogLevel($level);
		$levelName = FlexLogLevel::getLevelName($level);
		$rc = elgg_dump("[SR-".$levelName."] ".$msg, false, $elggLevel);
		return $rc;

	}
}

class FlexLogLevel {
	const ALL   = 0;
	const DEBUG = 1000;
	const INFO  = 2000;
	const WARN  = 3000;
	const ERROR = 4000;
	const FATAL = 5000;
	const OFF   = 9000;

	public function getElggLogLevel($level) {
		$elggLogLevel = 'NOTICE';

		switch ($level) {
			case FlexLogLevel::ALL:
			case FlexLogLevel::DEBUG:
				$elggLogLevel = 'DEBUG';
				break;
			case FlexLogLevel::INFO:
			case FlexLogLevel::WARN:
				$elggLogLevel = 'NOTICE';
				break;
			case FlexLogLevel::ERROR:
			case FlexLogLevel::FATAL:
			case FlexLogLevel::OFF:
				$elggLogLevel = 'ERROR';
				break;
		}

		return $elggLogLevel;
	}

	public function getLevelName($level) {
		$levelName = '';

		switch ($level) {
			case FlexLogLevel::ALL:
				$levelName = 'ALL';
				break;
			case FlexLogLevel::DEBUG:
				$levelName = 'DEBUG';
				break;
			case FlexLogLevel::INFO:
				$levelName = 'INFO';
				break;
			case FlexLogLevel::WARN:
				$levelName = 'WARN';
				break;
			case FlexLogLevel::ERROR:
				$levelName = 'ERROR';
				break;
			case FlexLogLevel::FATAL:
				$levelName = 'FATAL';
				break;
			case FlexLogLevel::OFF:
				$levelName = 'OFF';
				break;
		}

		return $levelName;
	}
}

