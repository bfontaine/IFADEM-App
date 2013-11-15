# IFADEM App Makefile
# v0.1.0

tpl/cache:
	mkdir -p $@
	chmod 777 $@

usersdata: usersdata.json p resources

usersdata.json:
	echo "{}" > $@

# directory which contains *.rss podcast feeds
# it may be changed using config.php
p:
	mkdir -p $@
	chmod o+w $@

# idem for cached resources
resources:
	mkdir -p $@
	chmod o+w $@

clean:
	rm -f *~
