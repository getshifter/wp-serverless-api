# wp-serverless-api

Explore WordPress data via the WP REST API as a JSON file for static WordPress Hosting on [Shifter](https://getshifter.io).

1. Install as a WordPress Plugin
2. Visit any single valid WP REST API endpoint

# Considerations

This plugin works best with headless applications. Its design mirrors and writes REST requests to file within the uploads folder. Currently, it only supports single post requests, such as /posts/1.

When using this plugin on Shifter, the static JSON file is synced to the CDN, which allows access to the data, whether WordPress is running or not.

# Roadmap

- Add support for query params, including per_page to support post arrays.

## CHANGELOG

### 0.3.0

- [BREAKING CHANGE] Change save path from `/wp-content/wp-sls-api/db.json` to `/wp-content/wp-json/wp/v2/{post_type}/{id}`

### 0.2.0

- [BREAKING CHANGE] Change save path from `/wp-content/uploads/wp-sls-api/db.json` to `/wp-content/wp-sls-api/db.json`
