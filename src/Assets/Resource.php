<?php

namespace Serebro\Assets;

abstract class Resource
{

	/** @var string */
	protected $filename;

	/** @var bool */
	protected $isLocal;

	/** @var array */
	protected $attributes;

	/** @var RevisionInterface */
	protected $assetsRevision;


	/**
	 * @param string            $filename
	 * @param bool              $isLocal
	 * @param array             $attributes
	 * @param RevisionInterface $assetsRevision
	 */
	abstract public function __construct(
		$filename,
		$isLocal = true,
		$attributes = null,
		RevisionInterface $assetsRevision = null
	);

	/**
	 * @return string
	 */
	abstract public function output();

	/**
	 * @return string
	 */
	public function getUri()
	{
		return $this->isLocal && !empty($this->assetsRevision) ? $this->assetsRevision->getUrl($this->filename) : $this->filename;
	}

	/**
	 * @return string
	 */
	protected function getAttributesString()
	{
		$attributes = [];
		foreach ($this->attributes as $name => $value) {
			$attributes[] = "$name=\"$value\"";
		}

		return join(' ', $attributes);
	}
}
