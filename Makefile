build:
	ncc build --config="release" --log-level debug

install:
	sudo ncc package install --package="build/release/net.nosial.loglib.ncc" --skip-dependencies --reinstall -y --log-level debug

clean:
	rm -rf build