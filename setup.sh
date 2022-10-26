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