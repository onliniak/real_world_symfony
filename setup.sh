# Zainstaluj symfonię CLI
## Chwilowo tylko na Ubuntu
if [  -n "$(uname -a | grep Ubuntu)" ]; then
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
fi
# Stwórz klucze JWT
php bin/console lexik:jwt:generate-keypair --skip-if-exists
# Usuń bazę danych, migracje i cache (na wszelki wypadek)
rm var/data.db
rm -r var/cache
rm -r migrations
# Stwórz plik migracji
mkdir migrations
touch migrations/.gitignore
php bin/console make:migration
# Migracja
php bin/console doctrine:migrations:migrate
# Start server
symfony server:start