Assets manager
---

It can work with "rev-manifest.json" file of [gulp-rev](https://github.com/sindresorhus/gulp-rev) plugin.

```php
$env = 'prod';
$revisionMap = json_decode(file_get_contents('build/manifest.json'), true); // { "file1.js": "file2-73a4818b.min.js", "file2.js": "file2-54e1080b.min.js" }

$ar = new \Serebro\Assets\Revision($env);
$ar->setRevisionMap($revisionMap)->setPrefix('http://cdn.example.com');


$assetsManager = \Serebro\Assets\Manager::getInstance();
$assetsManager
	->setRevisionManager($ar)
	->collection('head')
	->addCss('main.css')
	->addJs('file1.js')
	->collection('body')
	->addCss('special.css')
	->addJs('file2.js');

echo $assetsManager->outputCss('head') . PHP_EOL;
echo $assetsManager->outputJs('head') . PHP_EOL;
echo $assetsManager->outputCss('body') . PHP_EOL;
echo $assetsManager->outputJs('body') . PHP_EOL;
```

### Result:

```html
<link rel="stylesheet" href="http://cdn.example.com/css/main.css"/>
<script type="text/javascript" src="http://cdn.example.com/js/file1-73a4818b.min.js"></script>
<link rel="stylesheet" href="http://cdn.example.com/css/special.css"/>
<script type="text/javascript" src="http://cdn.example.com/js/file2-54e1080b.min.js"></script>
```
