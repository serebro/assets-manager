<?php

namespace Serebro\Assets;

use Closure;
use Exception;
use InvalidArgumentException;
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

	protected static $resourceTypes = [
		'js' => 'Serebro\Assets\Resource\Js',
		'css' => 'Serebro\Assets\Resource\Css',
	];

	/** @var Manager */
	protected static $instance;

	/** @var RevisionInterface */
	protected $assetsRevision;

	/** @var Collection[] */
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

	/**
	 * @param $resourceTypes
	 */
	public static function setResourceTypes($resourceTypes)
	{
		if (!is_array($resourceTypes)) {
			throw new InvalidArgumentException('Argument "resourceTypes" must be an array.');
		}

		self::$resourceTypes = $resourceTypes;
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
		if (!is_string($collectionName)) {
			throw new InvalidArgumentException('Argument "collectionName" must be a string.');
		}

		if (empty($this->collections[$collectionName])) {
			$this->collections[$collectionName] = new Collection();
		}

		$this->collection = $this->collections[$collectionName];
		return $this;
	}

	/**
	 * @param ResourceInterface $resource
	 * @return $this
	 */
	public function add(ResourceInterface $resource)
	{
		$filename = $resource->getFilename();
		if (in_array($filename, $this->resources)) {
			// skip duplicates
			return $this;
		}

		$this->resources[] = $filename;
		$resource->setAssetsRevision($this->assetsRevision);
		$this->collection->append($resource);
		return $this;
	}

	/**
	 * The alias for JS resource
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param array  $attributes
	 * @return $this
	 */
	public function addJs($filename, $isLocal = true, $attributes = null)
	{
		$this->add(new Js($filename, $isLocal, $attributes));
		return $this;
	}

	/**
	 * The alias for CSS resource
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param array  $attributes
	 * @return $this
	 */
	public function addCss($filename, $isLocal = true, $attributes = null)
	{
		$this->add(new Css($filename, $isLocal, $attributes));
		return $this;
	}

	/**
	 * @param string         $collectionName
	 * @param string|Closure $filter
	 * @return bool|string
	 * @throws Exception
	 */
	public function output($collectionName, $filter)
	{
		if (!is_string($collectionName)) {
			throw new InvalidArgumentException('Argument "collectionName" must be a string.');
		}

		if (empty($this->collections[$collectionName])) {
			return false;
		}

		if (!is_string($filter) && !is_callable($filter, true)) {
			throw new InvalidArgumentException('Argument "collectionName" must be a string or callable');
		}

		if (is_string($filter)) {
			if (!empty(self::$resourceTypes[$filter]) && class_exists(self::$resourceTypes[$filter])) {
				$filter = self::$resourceTypes[$filter];
			}
			$filter = function (ResourceInterface $resource) use ($filter) {
				return $resource instanceof $filter;
			};
		}

		$resources = array_filter((array)$this->collections[$collectionName], $filter);

		$strings = array_map(function (ResourceInterface $resource) {
			return $resource->output();
		}, $resources);

		return join(PHP_EOL, $strings);
	}

	/**
	 * The alias for JS output
	 * @param string $collectionName
	 * @return string
	 * @throws Exception
	 */
	public function outputJs($collectionName = self::DEFAULT_COLLECTION_NAME)
	{
		return $this->output($collectionName, 'js');
	}

	/**
	 * The alias for CSS output
	 * @param string $collectionName
	 * @return string
	 * @throws Exception
	 */
	public function outputCss($collectionName = self::DEFAULT_COLLECTION_NAME)
	{
		return $this->output($collectionName, 'css');
	}

	/**
	 * Reset instance
	 */
	public static function reset()
	{
		self::$instance->assetsRevision = null;
		self::$instance->collection = null;
		self::$instance->collections = [];
		self::$instance->resources = [];
		self::$instance->collection(self::DEFAULT_COLLECTION_NAME);
	}

	final private function __wakeup()
	{
	}

	final private function __clone()
	{
	}
}
