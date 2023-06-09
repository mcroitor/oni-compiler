PHP=php.exe
HOST=localhost
PORT=8000
DATABASE=data/database.sqlite
METAGEN=php ./tools/metadb/generator.php
METAPATH=./core/meta/
MODMAN=./tools/module_manager/src/manager.php

help:
	@echo "usage:"; \
	echo "  make install - create database"; \
	echo "  make start   - start web server"; \
	echo "  make clean   - remove database"; \
	echo "  make meta    - recreate meta information"; \
	echo "  make rebuild - clean install meta";

install:
	mkdir --parents ./data/contests/ ./data/tasks/ ./data/tmp/; \
	php $(MODMAN) --install --config=./tools/module_manager/modules.json
	${PHP} ./cli/install.php

start:
	php -S ${HOST}:${PORT}

clean:
	unlink ${DATABASE}

meta:
	@echo "recreate metadata"; \
	${METAGEN} --dsn='sqlite:${DATABASE}' --output=${METAPATH}

rebuild: clean install meta
