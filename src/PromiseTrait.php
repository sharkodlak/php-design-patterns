<?php

namespace Sharkodlak\DesignPatterns;

/** Promise (Future) interface for asynchronous programming.
 */
trait PromiseTrait implements Promise {
	const STATE_PENDING = -1;
	const STATE_REJECTED = 0;
	const STATE_FULFILLED = 1;
	private $state = self::STATE_PENDING;
	private $fulfillHandlers = [];
	private $rejectHandlers = [];

	public function then(callable $onFulfilled = null, callable $onRejected = null) {
		if ($onFulfilled !== null) {
			$this->fulfillHandlers[] = $onFulfilled;
		}
		if ($onRejected !== null) {
			$this->rejectHandlers[] = $onRejected;
		}
		$this->runIfInFinalState();
	}

	private function runIfInFinalState() {
		if ($this->state === self::STATE_FULFILLED) {
			while ($handler = array_shift($this->fulfillHandlers)) {
				call_user_func($handler);
			}
		}
		else if ($this->state === self::STATE_REJECTED) {
			while ($handler = array_shift($this->rejectHandlers)) {
				call_user_func($handler);
			}
		}
	}

	protected function setStateFullfilled() {
		$this->setState(self::STATE_FULFILLED);
	}

	protected function setStateRejected() {
		$this->setState(self::STATE_REJECTED);
	}

	private function setState($state) {
		if ($this->state !== self::STATE_PENDING) {
			throw new \Sharkodlak\Exceptions\IllegalStateException('Promise already in final state.');
		}
		$this->state = $state;
		$this->runIfInFinalState();
	}
}
