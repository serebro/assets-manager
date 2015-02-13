<?php


class ResourceCssTest extends \PHPUnit_Framework_TestCase
{

	public function testOutput()
	{
		$ar = new \Serebro\Assets\Revision('dev');

		$css = new \Serebro\Assets\Resource\Css('file.css', true, null);
		$css->setAssetsRevision($ar);
		$expected = '|<link rel="stylesheet" href="/build/dev/css/file.css\?(\d+)"/>|';
		$this->assertTrue((bool)preg_match($expected, $css->output()));

		$css = new \Serebro\Assets\Resource\css('//example.com/main.css', false);
		$expected = '<link rel="stylesheet" href="//example.com/main.css"/>';
		$this->assertEquals($expected, $css->output());
	}
}
