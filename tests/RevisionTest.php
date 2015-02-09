<?php


class RevisionTest extends \PHPUnit_Framework_TestCase
{

	private static $revisionMap = [
		'file.js' => 'file-73a4818b.min.js',
	];

	public function testDev()
	{
		$ar = new \Serebro\Assets\Revision('dev');
		$this->assertEquals('dev', $ar->getEnv());

		$ar->setRevisionMap(self::$revisionMap);
		$ar->setPrefix('http://example.com');
		$this->assertTrue(strpos($ar->getUrl('file.js'), '/build/dev/js/file.js?') > -1);
	}

	public function testQA()
	{
		$ar = new \Serebro\Assets\Revision('test');
		$ar->setRevisionMap(self::$revisionMap);
		$ar->setPrefix('http://example.com');

		$this->assertEquals('/build/test/js/file-73a4818b.min.js', $ar->getUrl('file.js'));
	}

	public function testProd()
	{
		$ar = new \Serebro\Assets\Revision('prod');
		$ar->setRevisionMap(self::$revisionMap);
		$ar->setPrefix('http://example.com');

		$this->assertEquals('http://example.com/js/file-73a4818b.min.js', $ar->getUrl('file.js'));
	}

	/**
	 * @expectedException \Serebro\Assets\UndefinedEnvException
	 */
	public function testEnvException()
	{
		\Serebro\Assets\Revision::setAvailableEnvironments(['test' => \Serebro\Assets\Revision::ENV_TEST]);
		new \Serebro\Assets\Revision('dev');
	}

}
