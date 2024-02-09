## Funkcionalnosti za neulogovanog korisnika:
- registrovanje
- logovanje
- uvid u osnovne informacije o aplikaciji
- prikaz nekoliko postova aplikacije
- mogućnost resetovanja lozinke
## Funkcionalnosti za ulogovanog korisnika:
- prikaz postova prijatelja
- prikaz postova "ne"prijatelja
- prikaz svog/tuđeg profila
- prikaz postova svog/tuđeg profila
- editovanje svog profila
- dodavanje/brisanje posta
- dodavanje/brisanje lajka na nečijem postu
- dodavanje/brisanje komentara na nečijem postu
- dodavanje/brisanje reporta na tuđem postu/komentaru
- sklapanje/raskidanje prijateljstva sa drugima
## Funkcionalnosti za administratora:
- sve funkcionalnosti ulogovanog korisnika osim dodavanja/brisanja reporta na tuđem postu/komentaru
- mogućnost brisanja neprikladnih komentara/postova
- mogućnost dodeljivanja admin najaktivnijem korisniku
- pregled statistike lajkova za period vremena
# Pokretanje aplikacije
- Najpre je potrebno pokrenuti Apache i MySQL u okviru XAMPP-a. Zatim je potrebno pokrenuti redom sledeće komande:
- git clone https://github.com/elab-development/internet-tehnologije-projekat-drustvenamrezapivo_2020_0077.git
- cd internet-tehnologije-projekat-drustvenamrezazapivo_2020_0077
## Backend
- cd domacilaravel
- copy .env.example .env
- u .env fajlu definisati naziv baze kao na primer DB_DATABASE=laravel
- composer install
- php artisan migrate
- php artisan db:seed
- php artisan serve
## Frontend
- cd domacireact
- npm install
- npm start
