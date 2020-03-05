#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE go_test;"
    psql -U postgres -c "CREATE USER go PASSWORD 'go' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists go
    sudo -u postgres dropdb --if-exists go_test
    sudo -u postgres dropuser --if-exists go
    sudo -u postgres psql -c "CREATE USER go PASSWORD 'go' SUPERUSER;"
    sudo -u postgres createdb -O go go
    sudo -u postgres psql -d go -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O go go_test
    sudo -u postgres psql -d go_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:go:go"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
