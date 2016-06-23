<?php

namespace Sharkodlak\DesignPatterns;

/** Promise (Future) interface for asynchronous programming.
 */
interface Promise {
	public function then(callable $onFulfilled = null, callable $onRejected = null);
}
