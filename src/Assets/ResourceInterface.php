<?php

namespace Serebro\Assets;

interface ResourceInterface
{

	/**
	 * @param string            $filename
	 * @param bool              $isLocal
	 * @param array             $attributes
	 * @param RevisionInterface $assetsRevision
	 */
	public function __construct(
		$filename,
		$isLocal = true,
		$attributes = null,
		RevisionInterface $assetsRevision = null
	);

	/**
	 * @return string
	 */
	public function output();
}
