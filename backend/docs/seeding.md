# Seeding

The backend includes a profile-based seed command:

```bash
composer seed -- --profile=dev
```

Supported profiles:

- `minimal`: smallest usable dataset (admin + teacher and baseline inventory).
- `dev`: `minimal` plus student test user data.
- `demo`: `dev` plus additional demo users and inventory records.

Examples:

```bash
composer seed -- --profile=minimal
composer seed -- --profile=dev
composer seed -- --profile=demo
```

The command is idempotent for the seeded records and can be run repeatedly.

Safety:

- Seeding is blocked when `APP_ENV=production` (or `prod`) unless `--force` is passed.
- Use `--force` only when you explicitly want to seed a production-like environment.
