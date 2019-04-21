.DEFAULT_GOAL := build-phar
.PHONY: clean

build-phar:
	composer install --no-dev --optimize-autoloader
	bin/create-phar build/headsec.phar
	chmod u+x build/headsec.phar

dev:
	composer install --dev
	bin/create-phar build/headsec.phar
	chmod u+x build/headsec.phar

test:
	composer install --dev
	vendor/bin/phpunit

install:
	cp build/headsec.phar /usr/local/bin/headsec

clean:
	rm build/headsec.phar
