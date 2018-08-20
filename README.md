# Notice manager for WordPress Dashboard
A small library to easily manage notice messages in the WordPress dashboard

## Installation
Use [Composer](https://getcomposer.org/) to require the library. The package isn't available on Packagist yet, so you have to include its url in your composer file manually:

```bash
{
  "name": "Your project",
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/antonioeatgoat/notice-manager-for-wp-dashboard"
    }
  ],
  "require": {
    "antonioeatgoat/notice-manager-for-wp-dashboard":"dev-master"
  }
}
```

Then include the [Composer](https://getcomposer.org/) autoload file in your project::

```php
require_once 'vendor/autoload.php'
```

If that's not an option then clone or download the package and require the `notice-manager-for-wp-dashboard/autoload.php` file in your code:

```php
require_once 'path/to/notice-manager-for-wp-dashboard/autoload.php';
```

Where `path/to/notice-manager-for-wp-dashboard/autoload.php` is the absolute path to the `autoload.php` file.

## Example

```php
$notice = ( new aeg_NM_NoticeFactory() )->create(
	'hello-world-notice',
	'<strong>Hello World</strong> - This is a sample notice',
	array(
		'show_close_btn' => true,
		'cta_anchor'     => 'This is a custom CTA!',
		'cta_href'       => '#',
		'dismiss_anchor' => "Don't show it again",
		'dismiss_mode'   => 'global',
		'status'         => 'success'
	));
aeg_NM_NoticesManager::init()->add($notice);
```
