#!/bin/bash
set -e

TEST=`psql -U postgres <<- EOSQL
   SELECT 1 FROM pg_database WHERE datname='$DB_NAME';
EOSQL`
echo "******CREATING DOCKER DATABASE******"
if [[ $TEST == "1" ]]; then
    # database exists
    # $? is 0
    exit 0
else
psql -U postgres  <<- EOSQL
CREATE DATABASE $DB_NAME WITH OWNER $POSTGRES_USER TEMPLATE template0 ENCODING 'UTF8';
GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $POSTGRES_USER;
EOSQL

fi
echo ""
echo "******DATABASE CREATED******"