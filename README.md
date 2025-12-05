# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds the distributable version of the framework.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

## Logistics & Distribution Module

- Run `php spark migrate --all` then `php spark db:seed LogisticsSeeder` to provision logistics tables, demo vehicles, and sample deliveries.
- Core REST endpoints:
	- `POST /api/logistics/deliveries` create delivery payload with branch IDs, items, scheduled_at.
	- `PATCH /api/logistics/deliveries/{id}/status` transition workflow (pending → dispatched → in_transit → delivered → acknowledged).
	- `POST /api/logistics/transfer-requests` create inter-branch request (branch manager role).
	- `PATCH /api/logistics/transfer-requests/{id}/approve` approve/reject and optionally auto-create delivery (admin/coordinator role).
	- `POST /api/logistics/routes/optimize` stub nearest-neighbor route optimizer with OSRM/Google hook.
- Sample cURL:
	```bash
	curl -X POST "https://your-host/api/logistics/deliveries" \
			 -H "Content-Type: application/json" \
			 -b "ci_session=..." \
			 -d '{
						"source_branch_id": 1,
						"destination_branch_id": 3,
						"scheduled_at": "2025-12-10T08:00:00+08:00",
						"items": [{"product_id": 5, "quantity": 20}],
						"notes": "Fragile goods"
				}'
	```
- Acceptance highlights: delivery creation returns `delivery_code`, notifications/log activity emit on status changes, transfer requests flow from branch manager to central admin, and audit entries stored in `logistics_activity_logs`.
