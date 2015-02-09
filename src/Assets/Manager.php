<?php

namespace Serebro\Assets;

use Closure;
use Serebro\Assets\Resource;
use Serebro\Assets\Resource\Css;
use Serebro\Assets\Resource\Js;

/**
 * Class Manager
 * @singleton
 * @package Serebro\Assets
 */
class Manager
{

	const DEFAULT_COLLECTION_NAME = 'head';

	/** @var Manager */
	protected static $instance;

	/** @var RevisionInterface */
	protected $assetsRevision;

	/** @var array Collection[] */
	private $collections = [];

	/** @var Collection */
	private $collection;

	/** @var array */
	private $resources = [];


	/**
	 * @return Manager|static
	 */
	final public static function getInstance()
	{
		return isset(static::$instance) ? static::$instance : static::$instance = new static;
	}

	final private function __construct()
	{
		$this->collection(self::DEFAULT_COLLECTION_NAME);
		$this->init();
	}

	protected function init()
	{
	}

	/**
	 * @param RevisionInterface $assetsRevision
	 * @return $this
	 */
	public function setRevisionManager(RevisionInterface $assetsRevision)
	{
		$this->assetsRevision = $assetsRevision;
		return $this;
	}

	/**
	 * @param string $collectionName
	 * @return $this
	 */
	public function collection($collectionName)
	{
		if (empty($this->collections[$collectionName])) {
			$this->collections[$collectionName] = new Collection();
		}

		$this->collection = $this->collections[$collectionName];
		return $this;
	}

	/**
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param null   $attributes
	 * @return $this
	 */
	public function addJs($filename, $isLocal = true, $attributes = null)
	{
		if (in_array($filename, $this->resources)) {
			return $this;
		}
		$this->resources[] = $filename;
		$this->collection->append(new Js($filename, $isLocal, $attributes, $this->assetsRevision));
		return $this;
	}

	/**
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param null   $attributes
	 * @return $this
	 */
	public function addCss($filename, $isLocal = true, $attributes = null)
	{
		if (in_array($filename, $this->resources)) {
			return $this;
		}
		$this->resources[] = $filename;
		$this->collection->append(new Css($filename, $isLocal, $attributes, $this->assetsRevision));
		return $this;
	}

	/**
	 * @param string $collectionName
	 * @return string
	 */
	public function outputJs($collectionName = self::DEFAULT_COLLECTION_NAME)
	{
		return $this->output($collectionName, function (ResourceInterface $resource) {
			return $resource instanceof Js;
		});
	}

	/**
	 * @param string $collectionName
	 * @return string
	 */
	public function outputCss($collectionName = self::DEFAULT_COLLECTION_NAME)
	{
		return $this->output($collectionName, function (ResourceInterface $resource) {
			return $resource instanceof Css;
		});
	}

	/**
	 * @param string  $collectionName
	 * @param Closure $filter
	 * @return string
	 */
	protected function output($collectionName, Closure $filter)
	{
		if (empty($this->collections[$collectionName])) {
			return false;
		}

		$resources = array_map(function (ResourceInterface $resource) {
			return $resource->output();
		}, array_filter((array)$this->collections[$collectionName], $filter));

		return join(PHP_EOL, $resources);
	}

	final private function __wakeup()
	{
	}

	final private function __clone()
	{
	}
}
