<?php

namespace Serebro\Assets;

class Revision implements RevisionInterface
{

	const ENV_DEV = 'dev';
	const ENV_TEST = 'test';
	const ENV_PROD = 'prod';


	/** @var string */
	private $env = 'prod';

	/** @var array */
	private $revisionMap = [];

	/** @var string */
	private $prefix = '';

	/** @var string */
	private $postfix = '';

	/** @var string */
	private $path = '';

	/** @var array */
	private static $envs = [
		'dev' => ['type' => self::ENV_DEV, 'path' => '/build/dev/'],
		'test' => ['type' => self::ENV_TEST, 'path' => '/build/test/'],
		'prod' => ['type' => self::ENV_PROD, 'path' => '/build/prod/'],
	];

	/**
	 * @param string $env
	 * @throws UndefinedEnvException
	 */
	public function __construct($env)
	{
		$env = strtolower($env);
		if (empty(self::$envs[$env])) {
			throw new UndefinedEnvException('Environment "' . $env . '" does not define. Defined: ' . join(', ', array_keys(self::$envs)));
		}

		$this->env = $env;
	}

	/**
	 * @param array $envs [customer_name => key]
	 */
	public static function setAvailableEnvironments($envs)
	{
		self::$envs = $envs;
	}

	/**
	 * @return string
	 */
	public function getEnv()
	{
		return $this->env;
	}

	/**
	 * @param array $map
	 *        Example:
	 *        array(
	 *        "file1.js" => "file1-73a4818b.min.js",
	 *        "file2.css" => "file2-54e1080b.min.css"
	 *        )
	 * @return $this
	 */
	public function setRevisionMap($map)
	{
		$this->revisionMap = $map;
		return $this;
	}

	/**
	 * @param string $prefix
	 * @return $this
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = rtrim($prefix, '/');
		return $this;
	}

	/**
	 * @param string $postfix
	 * @return $this
	 */
	public function setPostfix($postfix)
	{
		$this->postfix = trim($postfix);
		return $this;
	}

	/**
	 * @param string $path
	 * @return $this
	 */
	public function setPath($path)
	{
		$this->path = trim($path);
		return $this;
	}

	/**
	 * @param string $bundleName
	 * @return string
	 */
	public function getUrl($bundleName)
	{
		$env = self::$envs[$this->env];
		switch ($env['type']) {
			case self::ENV_DEV:
				$this->setPrefix($env['path']);
				$filename = $bundleName;
				$fullName = 'build/dev/' . $this->getFullName($bundleName);
				$postfix = file_exists($fullName) ? filemtime($fullName) : time();
				$this->setPostfix('?' . $postfix);
				break;
			case self::ENV_TEST:
				$this->setPrefix($env['path']);
				$filename = $this->getFilename($bundleName);
				break;
			default:
				$filename = $this->getFilename($bundleName);
		}

		$fullName = $this->getFullName($filename);
		return $this->buildPath($fullName);
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	protected function buildPath($filename)
	{
		return "{$this->prefix}/{$filename}{$this->postfix}";
	}

	/**
	 * @param string $bundleName
	 * @return string
	 */
	protected function getFilename($bundleName)
	{
		return empty($this->revisionMap[$bundleName]) ? $bundleName : $this->revisionMap[$bundleName];
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	protected function getFullName($filename)
	{
		$path = empty($this->path) ? pathinfo($filename, PATHINFO_EXTENSION) : $this->path;
		$path .= '/';
		return "{$path}{$filename}";
	}
}
