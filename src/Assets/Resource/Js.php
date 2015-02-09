<?php

namespace Serebro\Assets\Resource;

use Serebro\Assets\Resource;
use Serebro\Assets\RevisionInterface;

class Js extends Resource
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
		if (empty($this->attributes['type'])) {
			$this->attributes['type'] = 'text/javascript';
		}

		$this->attributes['src'] = $this->getUri();

		return "<script {$this->getAttributesString()}></script>";
	}
}
