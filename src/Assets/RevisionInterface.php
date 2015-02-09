<?php

namespace Serebro\Assets;

interface RevisionInterface
{

	/**
	 * @param array $envs
	 */
	public static function setAvailableEnvironments($envs);

	/**
	 * @param string $env
	 */
	public function __construct($env);

	/**
	 * @param array $map
	 * @return mixed
	 */
	public function setRevisionMap($map);

	/**
	 * @param string $bundleName
	 * @return string
	 */
	public function getUrl($bundleName);

}