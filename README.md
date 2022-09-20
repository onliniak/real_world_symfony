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

- Coś tam słyszałem o programowaniu obiektowym i PHP7.
- Coś tam słyszałem o testach.
- Pracuję powoli i dokładnie.
- Jestem samoukiem, co czyni ze mnie doskonałego junior full stack developera + 
użytkownika Linuksa.

## Instalacja
### Wygeneruj klucze.
````
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
chmod -R 775 config/
````