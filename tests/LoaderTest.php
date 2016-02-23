<?php

require '_support/MockLoader.php';
require '_support/Config/MockAutoloadConfig.php';

class LoaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var MockLoader
	 */
	protected $loader;

	//--------------------------------------------------------------------

	public function setUp()
	{
		$config = new MockAutoload();
		$config->psr4 = [
			'App\Libraries'   => '/application/somewhere',
			'App'             => '/application',
		    'Blog'            => '/modules/blog'
		];

	    $this->loader = new MockLoader($config);

		$this->loader->setFiles([
			APPPATH.'index.php',
			APPPATH.'Views/index.php',
		    APPPATH.'Views/admin/users/create.php',
		    '/modules/blog/Views/index.php',
		    '/modules/blog/Views/admin/posts.php'
		]);
	}

	//--------------------------------------------------------------------

	public function testLocateFileWorksInApplicationDirectory()
	{
		$file = 'index';

		$expected = APPPATH.'Views/index.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileWorksInApplicationDirectoryWithoutFolder()
	{
		$file = 'index';

		$expected = APPPATH.'index.php';

		$this->assertEquals($expected, $this->loader->locateFile($file));
	}

	//--------------------------------------------------------------------

	public function testLocateFileWorksInNestedApplicationDirectory()
	{
		$file = 'admin/users/create';

		$expected = APPPATH.'Views/admin/users/create.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileReplacesFolderName()
	{
		$file = '\Blog\views/admin/posts.php';

		$expected = '/modules/blog/Views/admin/posts.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileReplacesFolderNameLegacy()
	{
		$file = 'Views/index.php';

		$expected = APPPATH.'Views/index.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileCanFindNamespacedView()
	{
	    $file = '\Blog\index';

		$expected = '/modules/blog/Views/index.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileCanFindNestedNamespacedView()
	{
		$file = '\Blog\admin/posts.php';

		$expected = '/modules/blog/Views/admin/posts.php';

		$this->assertEquals($expected, $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------

	public function testLocateFileReturnsEmptyWithBadNamespace()
	{
		$file = '\Blogger\admin/posts.php';

		$this->assertEquals('', $this->loader->locateFile($file, 'Views'));
	}

	//--------------------------------------------------------------------
}

