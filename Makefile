# Variables
NCC = ncc
PACKAGE_NAME = net.nosial.loglib.ncc
BUILD_CONFIG = release
BUILD_STATIC_CONFIG = release_static

# Directories
SRC_DIR = src
BUILD_DIR = build
RELEASE_BUILD_DIR = $(BUILD_DIR)/$(BUILD_CONFIG)
RELEASE_STATIC_BUILD_DIR = $(BUILD_DIR)/$(BUILD_STATIC_CONFIG)

.PHONY: all release release_static install uninstall clean

all: release release_static install

release: prepare_build
	$(NCC) build --config=$(BUILD_CONFIG) --out-dir=$(RELEASE_BUILD_DIR)

release_static: prepare_build_static
	$(NCC) build --config=$(BUILD_STATIC_CONFIG) --out-dir=$(RELEASE_STATIC_BUILD_DIR)

install: prepare_build
	$(NCC) package install --package="$(RELEASE_BUILD_DIR)/$(PACKAGE_NAME)" --skip-dependencies -y

uninstall:
	$(NCC) package uninstall -y --package="$(PACKAGE_NAME)"

clean:
	rm -rf $(RELEASE_BUILD_DIR)
	rm -rf $(RELEASE_STATIC_BUILD_DIR)

prepare_build:
	mkdir -p $(RELEASE_BUILD_DIR)

prepare_build_static:
	mkdir -p $(RELEASE_STATIC_BUILD_DIR)