# Elonn

Elonn is a multi-service platform built around shared identity, runtime composition, and portable application surfaces.

This repository is the public front door. It handles login, registration, account state, logout, and the handoff into the runtime shell. It does not own identity data.

## Service map

- `api.elonn.local`: identity authority and member directory
- `maps.elonn.local`: canonical field dataset
- `social.elonn.local`: social objects and direct messages
- `time.elonn.local`: calendars and events
- `world.elonn.local`: composition and presentation contract layer
- `web.elonn.local`: browser runtime implementation
- `admin.elonn.local`: operator console

## What lives here

- public account surface
- login and registration routes
- account and logout flow
- CalDAV connection settings
- environment-aware handoff into `web.elonn`

## Current stack flow

1. A user visits `elonn.local` or `elonn.com`
2. Login and registration post to `api.elonn`
3. `api.elonn` sets the shared auth token
4. `web.elonn` loads JSON runtime composition from `world.elonn`
5. `world.elonn` composes maps, social, time, and identity data
6. `web.elonn` renders the shell and carry panels

## Guidance

The repo-specific implementation notes live in [`elonn.md`](./elonn.md).

## Local URLs

- `http://elonn.local/`
- `http://elonn.local/account/login`
- `http://elonn.local/account/register`
- `http://elonn.local/account`

## Production URLs

- `https://elonn.com/`
- `https://elonn.com/account/login`
- `https://elonn.com/account/register`
- `https://elonn.com/account`
