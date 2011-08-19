## Bookkeeper

### Installation

These instructions are for the example backend.

1. Copy `config.sample.js` to `config.js`.
2. Edit `config.js` to point to wherever the `example-backend/` directory is on your server.
3. Give your web server user access to write to `example-backend/goals.dat` and `example-backend/entries.dat` (usually something like `chgrp www example-backend/*.dat` and `chmod g+w example-backend/*.dat` will suffice, replacing `www` with the user Apache is running as).
