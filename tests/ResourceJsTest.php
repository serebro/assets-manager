<?php


class ResourceJsTest extends \PHPUnit_Framework_TestCase
{

	public function testOutput()
	{
		$ar = new \Serebro\Assets\Revision('dev');

		$js = new \Serebro\Assets\Resource\Js('file.js', true, ['async' => 'async']);
		$js->setAssetsRevision($ar);
		$expected = '|<script async="async" type="text/javascript" src="/build/dev/js/file.js\?(\d+)"></script>|';
		$this->assertTrue((bool)preg_match($expected, $js->output()));

		$js = new \Serebro\Assets\Resource\Js(
			'//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
			false,
			['async' => 'async']
		);
		$expected = '<script async="async" type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
		$this->assertEquals($expected, $js->output());
	}
}
