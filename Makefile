# IFADEM App Makefile
# v0.1.0

JSCACHE=.jscache

tpl/cache:
	mkdir -p $@
	chmod 777 $@

php-dependencies:
	composer install

usersdata: usersdata.json p

usersdata.json:
	echo "{}" > $@

# directory which contains *.rss podcast feeds
# it may be changed using config.php
p:
	mkdir -p $@
	chmod o+w $@

clean:
	rm -f *~ $(JSCACHE)
