.PHONY: archive clean

archive: clean
	zip -r wp-serverless-api.zip *.php *.md

clean:
	rm -f wp-serverless-api.zip
