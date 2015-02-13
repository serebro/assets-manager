<?php

namespace Serebro\Assets;

use InvalidArgumentException;

abstract class Resource implements ResourceInterface
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
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param array  $attributes
	 */
	public function __construct($filename, $isLocal = true, $attributes = null)
	{
		if (!is_string($filename)) {
			throw new InvalidArgumentException('The argument "filename" is not of the expected type "string"');
		}
		if (!is_bool($isLocal)) {
			throw new InvalidArgumentException('The argument "isLocal" is not of the expected type "boolean"');
		}
		if ($attributes !== null && !is_array($attributes)) {
			throw new InvalidArgumentException('The argument "attributes" is not of the expected type "array"');
		}

		$this->filename = $filename;
		$this->isLocal = $isLocal;
		$this->attributes = $attributes;
	}

	/**
	 * @return string
	 */
	abstract public function output();

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->isLocal && !empty($this->assetsRevision) ? $this->assetsRevision->getUrl($this->filename) : $this->filename;
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * @param RevisionInterface $assetsRevision
	 * @return $this
	 */
	public function setAssetsRevision(RevisionInterface $assetsRevision)
	{
		$this->assetsRevision = $assetsRevision;
	}

	/**
	 * @return RevisionInterface
	 */
	public function getAssetsRevision()
	{
		return $this->assetsRevision;
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
