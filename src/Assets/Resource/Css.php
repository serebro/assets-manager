<?php

namespace Serebro\Assets\Resource;

use Serebro\Assets\Resource;
use Serebro\Assets\RevisionInterface;

class Css extends Resource
{

	/**
	 * @param string            $filename
	 * @param bool              $isLocal
	 * @param null              $attributes
	 * @param RevisionInterface $assetsRevision
	 */
	public function __construct(
		$filename,
		$isLocal = true,
		$attributes = null,
		RevisionInterface $assetsRevision = null
	) {
		$this->filename = $filename;
		$this->isLocal = $isLocal;
		$this->attributes = $attributes;
		$this->assetsRevision = $assetsRevision;
	}

	/**
	 * @return string
	 */
	public function output()
	{
		if (empty($this->attributes['rel'])) {
			$this->attributes['rel'] = 'stylesheet';
		}

		$this->attributes['href'] = $this->getUri();

		return "<link {$this->getAttributesString()}/>";
	}
}
