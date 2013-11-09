#! /usr/bin/env python3
# -*- coding: UTF-8 -*-
#
# This script calls the Web services and replicate IFADEM's files locally,
# keeping the same directories structure.
# Call it once for local testing.

import os
import re
import sys
from urllib.request import urlopen
from urllib.parse   import unquote
from urllib.error   import HTTPError

def mkdir_p(p):
    try:
        os.makedirs(p, exist_ok=True)
    except OSError as o:
        print("mkdir -p %s - error: %s" % (p, o))

def add_urls(q, m):
    base = 'https://c2i.education.fr/ifademws/ws.php?wsformat=json&wsfunction='
    ress = urlopen(base + m)
    locs = re.finditer(r'"http:.*?www\.ifadem\.org.*?"', str(ress.read())[3:])
    for loc in locs:
        q.append(re.sub(r'\\', '', loc.group(0))[1:-1])

def retrieve_resource(url):
    clean_url = unquote(url)
    path = ''
    if clean_url.startswith('/'):
        path = clean.url[1:]
    else:
        path = clean_url[8:].split('/', 1)[1]

    if os.path.isfile(path):
        return

    mkdir_p(os.path.dirname(path))

    try:
        u = urlopen(url)
        f = open(path, 'wb')
        f.write(u.read())
        f.close()
    except HTTPError as e:
        print("\nError: %s with URL=%s" % (e, url))

def print_urls_count(q):
    print("\rQuerying the Web services... %3d URLs." % len(q), end='')
    sys.stdout.flush()

def print_progress(done, total, name):
    fmt = "\rRetrieving resources... %3d/%3d - %-23s"
    name = name.split('/')[-1]
    if (len(name) > 23):
        name = name[:10] + '...' + name[-10:]
    print(fmt % (done, total, name), end='')
    sys.stdout.flush()

def main():
    queue = []

    print('', end='')
    print_urls_count(queue)
    add_urls(queue, 'getAllRessources')
    print_urls_count(queue)
    add_urls(queue, 'getAllMP3')
    print_urls_count(queue)
    print('')

    total = len(queue)
    for i, url in enumerate(queue):
        retrieve_resource(url)
        print_progress(i, total, url)

    print_progress(total, total, 'done.')
    print('')

if __name__ == '__main__':
    main()
