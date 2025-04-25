# wp-serverless-api

Explore WordPress data via the WP REST API as a JSON file for static WordPress Hosting on [Shifter](https://getshifter.io).

1. Install as a WordPress Plugin
2. Activate and save or create a new post or page
3. Create a new static Artifact on Shifter
4. Visit your new WP Serverless API endpoint at `example.com/wp-content/wp-sls-api/db.json`

## CHANGELOG

### 0.2.1

- Removed environment determination to generate db.json even in local environment [#2](https://github.com/getshifter/wp-serverless-api/pull/2)

### 0.2.0

- [BREAKING CHANGE] Change save path from `/wp-content/uploads/wp-sls-api/db.json` to `/wp-content/wp-sls-api/db.json`
