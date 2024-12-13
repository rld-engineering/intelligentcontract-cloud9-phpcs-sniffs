ROOT_DIR:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

precommit: phpunit

phpunit:
	cd $(ROOT_DIR); phpunit --display-phpunit-deprecations .

phpunit-coverage:
	cd $(ROOT_DIR); phpunit-xdebug --coverage-text .

phpunit-coverage-ci:
	cd $(ROOT_DIR); phpunit-xdebug  --coverage-html './coverage' .
