#!/bin/sh

cd "$(dirname "$(realpath "$0")")"
/usr/bin/env php -S localhost:8888
