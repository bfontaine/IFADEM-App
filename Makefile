# IFADEM App Makefile
# v0.1.0

tpl/cache:
	mkdir -p $@
	chmod 777 $@

php-dependencies:
	composer install
