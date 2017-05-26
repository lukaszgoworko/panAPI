# panAPI

## Configuration
### Postgres
Create an .env file for postgres-configuration under ./conf/postgres directory and use .env.example as template.

### Nginx
Under .conf/sites/ there is a site.conf-File, where you can specify all needed configuration for nginx

### Hosts
You have to add this line to your hosts file to be able to browse to api.hffmnsnmstr.local
```
127.0.0.1   api.pan.local
```


## Starting
Requirements:
*   Installed Docker min Version 1.13

**To start just run**

```
docker-compose up --build
```

**To stop service run**

```
docker-compose stop
```

**To delete service run**

```
docker-compose down
```

Volumes will not be deleted
to delete volumes run
```
docker volume prune
or
docker volume rm {volumename}
```
