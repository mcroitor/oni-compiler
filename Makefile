PHP=php.exe
HOST=oni.localhost
PORT=8000

.all: install run

install:
	mkdir -P ./data/contests/ ./data/tasks/ ./data/tmp/; \
	${PHP} ./cli/install.php

run:
	php -S ${HOST}:${PORT}