<?php

namespace Serebro\Assets\Resource;

use Serebro\Assets\Resource;
use Serebro\Assets\ResourceInterface;

class Js extends Resource implements ResourceInterface
{

	/**
	 * @return string
	 */
	public function output()
	{
		if (empty($this->attributes['type'])) {
			$this->attributes['type'] = 'text/javascript';
		}

		$this->attributes['src'] = $this->getUrl();

		return "<script {$this->getAttributesString()}></script>";
	}
}
