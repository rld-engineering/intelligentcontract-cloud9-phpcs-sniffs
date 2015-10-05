ROOT_DIR:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

phpunit:
	cd $(ROOT_DIR); phpunit .

phpunit-coverage:
	cd $(ROOT_DIR); phpunit-xdebug --coverage-text .