This package contains a Selenium2TestCase class that can be used to run end-to-end tests against Selenium 2.

Installing
---

Use [Composer](https://getcomposer.org) and add inside your composer.json file:

```
    "require": {
        "phpunit/phpunit-selenium": "*",
    }
```

then run `composer update`.

Requirements
---

- PHPUnit 4.8, 5.x

The current mainline of this package is 2.x. The old line 1.x is not maintained anymore, but will continue to be available for usage of `SeleniumTestCase`.

Please direct pull requests to [giorgiosironi/phpunit-selenium](https://github.com/giorgiosironi/phpunit-selenium) for automated testing upon merging. Pull requests should be feature branches containing all the commits you want to propose.

Please direct pull requests to [giorgiosironi/phpunit-selenium](https://github.com/giorgiosironi/phpunit-selenium) for automated testing upon merging.

Running the test suite
---

#### Via Vagrant

Just run the following Vagrant commands (a minimal version of `v1.7` is required) and everything will be set up for you. The first start will take some time which depends on the speed of your connection (and less - speed of your computer):

    vagrant up
    vagrant provision
    vagrant ssh

    cd /vagrant
    vendor/bin/phpunit Tests
 
and you must see the `phpunit` testing `phpunit-selenium` project.

