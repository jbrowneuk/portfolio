#!/usr/bin/env bash

# Get the correct directory if running from <repo-root>
SCRIPTDIR="$(dirname "$0")"
REPOROOT="$SCRIPTDIR/../"

podman run -it --rm -v $REPOROOT/src:/var/www/html:Z -v $REPOROOT/vendor:/var/www/html/vendor:Z -v $REPOROOT/../assets:/var/www/html/assets:Z -p 3000:80 portfoliodev:latest
