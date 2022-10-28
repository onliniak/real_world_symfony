# Zainstaluj symfonię CLI
## Chwilowo tylko na Ubuntu
if [  -n "$(uname -a | grep Ubuntu)" ]; then
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
fi
# Start server
symfony server:start