#!/usr/bin/env bash

# Get the correct directory for the build if running from <repo-root>
SCRIPTDIR=$(dirname "$0")
DOCKERFILE="$SCRIPTDIR/Dockerfile"
echo "Building '$DOCKERFILE'..."

podman build -f "$DOCKERFILE" -t portfoliodev:latest "$SCRIPTDIR"