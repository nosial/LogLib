debug:
	ncc build --config="debug"

release:
	ncc build --config="release"

install:
	ncc package install --package="build/release/net.nosial.loglib.ncc"

install-debug:
	ncc package install --package="build/debug/net.nosial.loglib.ncc"

uninstall:
	ncc package uninstall -y --package="net.nosial.loglib"