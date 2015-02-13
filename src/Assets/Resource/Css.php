<?php

namespace Serebro\Assets\Resource;

use Serebro\Assets\Resource;
use Serebro\Assets\ResourceInterface;

class Css extends Resource implements ResourceInterface
{

	/**
	 * @return string
	 */
	public function output()
	{
		if (empty($this->attributes['rel'])) {
			$this->attributes['rel'] = 'stylesheet';
		}

		$this->attributes['href'] = $this->getUrl();

		return "<link {$this->getAttributesString()}/>";
	}
}
