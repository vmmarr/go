#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U go -d go < $BASE_DIR/go.sql
fi
psql -h localhost -U go -d go_test < $BASE_DIR/go.sql
