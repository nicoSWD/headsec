.DEFAULT_GOAL := build-phar
.PHONY: clean

build-phar:
		composer install --no-dev
		php ./bin/create-phar.php ./build/headsec.phar

install:
		cp ./build/headsec.phar /usr/local/bin/headsec
		chmod u+x /usr/local/bin/headsec

test:
		composer install --dev
		./vendor/bin/phpunit

clean:
		rm ./build/headsec.phar
