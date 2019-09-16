<?php

namespace Platform\App\RuleCommanding;

interface RuleBus
{
	public function execute ($data, $actor, $method);
}
