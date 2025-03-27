## when by react container:

Compiled with problems:
ERROR
[eslint] EACCES: permission denied, open '/app/node_modules/.cache/.eslintcache'


## steps:
```bash
 docker exec -u root -it react_app bash
```
```bash
chown -R node:node /app/node_modules/
```
```bash
rm -rf node_modules/.cache/.eslintcache
```
```bash
exit
```
```bash
docker compose build --no-cache
```
```bash
docker compose up -d
```

## when error with the symfony container:

## steps:
```bash
docker exec -it symfony_app bash
```
```bash
composer install
```
```bash
docker compose down
```
```bash
docker compose build --no-cache
```
```bash
docker compose up -d
```