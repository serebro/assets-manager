<?php

namespace Serebro\Assets;

interface ResourceInterface
{

	/**
	 * @param string $filename
	 * @param bool   $isLocal
	 * @param array  $attributes
	 */
	public function __construct(
		$filename,
		$isLocal = true,
		$attributes = null
	);

	/**
	 * @return string
	 */
	public function output();

	/**
	 * @return string
	 */
	public function getFilename();

	/**
	 * @param RevisionInterface $assetsRevision
	 * @return $this
	 */
	public function setAssetsRevision(RevisionInterface $assetsRevision);

	/**
	 * @return RevisionInterface
	 */
	public function getAssetsRevision();
}
