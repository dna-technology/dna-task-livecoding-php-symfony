start:
	docker compose up -d
	docker compose cp php:/srv/vendor ./vendor

stop:
	docker compose down -v

.PHONY: start stop
