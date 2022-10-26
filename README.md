Potrzebny był mi przykładowy projekt do CV. Niedokończone.

Wcześniej nie używałem Symfonii, co ułatwia zrozumienie pracy mojego mózgu. 
W większości przypadków będzie marnować czas, na próbę zrozumienia jak to 
właściwie działa. Na przykład coś tak oczywistego, jak rejestracja zajęło mu 
więcej niż tydzień wpatrywania się w ekran i myślenia „Mam API w 
formacie {user, password} ale ja chcę mieć {user: {user, password}}”. 
Kiedy każdy zajrzy na stronę 
https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/7-manual-token-creation.rst 
ja zajmę się wszystkim, tylko nie tym, co trzeba.

[Real World](https://github.com/gothinkster/realworld) backend demo + Symfony.

## Instalacja
### Zainstaluj Symfonię CLI
https://symfony.com/download
### Stwórz klucze JWT
```
php bin/console lexik:jwt:generate-keypair --skip-if-exists
```
### Usuń bazę danych, migracje i cache (na wszelki wypadek)
```
rm var/data.db
rm -r var/cache
rm -r migrations
```
### Stwórz plik migracji
```
mkdir migrations
touch migrations/.gitignore
php bin/console make:migration
```
### Migracja
```
php bin/console doctrine:migrations:migrate
```
### Start server
```
symfony server:start
```
Możesz też uruchomić skrypt:
```
chmod +x setup.sh && yes | ./setup.sh
```