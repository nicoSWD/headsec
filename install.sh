INSTALLATION_PATH=/usr/local/bin/headsec
VERSION=v0.1.1

echo "[+] Downloading $VERSION..."
curl https://github.com/nicoSWD/headsec/releases/download/$VERSION/headsec.phar -sSL -o $INSTALLATION_PATH

echo "[+] Setting permissions..."
chmod u+x $INSTALLATION_PATH

echo "[+] Done!"
echo ""
echo "Installed to $INSTALLATION_PATH"
