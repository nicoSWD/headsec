.DEFAULT_GOAL := build-phar
.PHONY: clean

build-phar:
		composer install --no-dev
		php ./bin/create-phar.php ./build/httpsec.phar

install:
		cp ./build/httpsec.phar /usr/local/bin/httpsec
		chmod u+x /usr/local/bin/httpsec

test:
		composer install --dev
		./vendor/bin/phpunit

clean:
		rm ./build/httpsec.phar
