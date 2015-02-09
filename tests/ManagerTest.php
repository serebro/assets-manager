<?php


class ManagerTest extends \PHPUnit_Framework_TestCase
{

	private static $revisionMap = [
		'file.js' => 'file-73a4818b.min.js',
	];

	public function testA()
	{
		$am = \Serebro\Assets\Manager::getInstance();
		$this->assertInstanceOf('\Serebro\Assets\Manager', $am);

		$ar = new \Serebro\Assets\Revision('prod');
		$ar->setRevisionMap(self::$revisionMap);
		$ar->setPrefix('http://example.com');

		$am->setRevisionManager($ar)
			->collection('head')
			->addCss('file.css')
			->addJs('file.js')
			->addJs('file.js') // double
			->collection('body')
			->addCss('special.css')
			->addCss('special.css') // double
			->addJs('file2.js');

		$this->assertFalse($am->outputCss('undefined'));

		$expected = '<link rel="stylesheet" href="http://example.com/css/file.css"/>';
		$this->assertEquals($expected, $am->outputCss('head'));

		$expected = '<script type="text/javascript" src="http://example.com/js/file-73a4818b.min.js"></script>';
		$this->assertEquals($expected, $am->outputJs('head'));

		$expected = '<link rel="stylesheet" href="http://example.com/css/special.css"/>';
		$this->assertEquals($expected, $am->outputCss('body'));

		$expected = '<script type="text/javascript" src="http://example.com/js/file2.js"></script>';
		$this->assertEquals($expected, $am->outputJs('body'));
	}
}
