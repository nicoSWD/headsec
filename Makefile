.DEFAULT_GOAL := build-phar
.PHONY: clean

install-composer:
	sh build/install-composer.sh

build-phar: install-composer
	build/composer.phar install --no-dev --optimize-autoloader
	bin/create-phar build/headsec.phar
	chmod u+x build/headsec.phar

dev: install-composer
	build/composer.phar install --dev
	bin/create-phar build/headsec.phar
	chmod u+x build/headsec.phar

test: install-composer
	build/composer.phar install --dev
	vendor/bin/phpunit

install:
	cp build/headsec.phar /usr/local/bin/headsec

clean:
	rm build/headsec.phar
