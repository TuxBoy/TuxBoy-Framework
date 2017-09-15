
server: ## Lance le serveur de dev
	php -S localhost:8000 -t public -d display_errors=1 -d xdebug.remote_enable=1 -d xdebug.remote_autostart=1

#############
# Test & Linters
#############
lint: vendor ## Vérifie le code
	./vendor/bin/phpcs -s && \
	./vendor/bin/php-cs-fixer fix --diff --dry-run -v

lint.fix: vendor ## Vérifie le code et le corrige tout seul
	./vendor/bin/phpcbf; \
	./vendor/bin/php-cs-fixer fix --diff -v

#############
# Fichiers
#############
vendor: composer.lock
	composer install $(COMPOSER_ARGS)

composer.lock: composer.json
	composer update $(COMPOSER_ARGS)

build/logs/coveralls-upload.json: build/logs/clover.xml
	./vendor/bin/coveralls

config.php: config.php.dist ## Génère le fichier de configuration
	cp config.php.dist config.php
