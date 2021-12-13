---
sidebar_position: 4
title: CI/CD
---

## Versioning

The site supports multiple versions. To edit the `v1` version, please edit the `/docs/versioned_docs/version-v1` folder.

To edit the next (currently `v2`) version, please edit the `/docs/docs/` folder.

### Create a new version

To create a new version (such as a version 1.0)

```bash
npm run docusaurus docs:version 1.0
```

The `docs` folder is copied into `versioned_docs/version-1.0`

Your docs now have 2 versions:

- `1.0` at `http://localhost:3000/docs/` for the version 1.0 docs
- `current` at `http://localhost:3000/docs/next/` for the **upcoming, unreleased docs**

#### Update an existing version

It is possible to edit versioned docs in their respective folder:

- `versioned_docs/version-1.0/hello.md` updates `http://localhost:3000/docs/hello`
- `docs/hello.md` updates `http://localhost:3000/docs/next/hello`

## CI

When submitting PRs, the folder is run through:

- eslint
- secret detection
- spelling checker
- [alex](https://alexjs.com/) - insensitive & inconsiderate writing detector

## CD

The production site is deployed to [adam-rms.com](https://adam-rms.com) through Cloudflare pages, kept up-to-date with the `v2` branch.

When submitting PRs, a build is generated by Netlify, which provides a demo url to test in a browser