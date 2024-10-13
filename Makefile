# Variables
DEFAULT_CONFIGURATION ?= release
LOG_LEVEL = debug

# Default Target
all: release release_static release-compressed debug-compressed release-executable release_static-executable release-compressed-executable debug-compressed-executable

# Build Steps
release:
	ncc build --config=release --log-level $(LOG_LEVEL)
release_static:
	ncc build --config=release_static --log-level $(LOG_LEVEL)
release-compressed:
	ncc build --config=release-compressed --log-level $(LOG_LEVEL)
debug-compressed:
	ncc build --config=debug-compressed --log-level $(LOG_LEVEL)
release-executable:
	ncc build --config=release-executable --log-level $(LOG_LEVEL)
release_static-executable:
	ncc build --config=release_static-executable --log-level $(LOG_LEVEL)
release-compressed-executable:
	ncc build --config=release-compressed-executable --log-level $(LOG_LEVEL)
debug-compressed-executable:
	ncc build --config=debug-compressed-executable --log-level $(LOG_LEVEL)


install: release
	ncc package install --package=build/release/net.nosial.loglib.ncc --skip-dependencies --build-source --reinstall -y --log-level $(LOG_LEVEL)

test: release
	[ -f phpunit.xml ] || { echo "phpunit.xml not found"; exit 1; }
	phpunit

clean:
	rm -rf build

.PHONY: all install test clean release release_static release-compressed debug-compressed release-executable release_static-executable release-compressed-executable debug-compressed-executable