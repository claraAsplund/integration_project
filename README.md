# PHP Pipedrive Integration
Dette prosjektet lar deg integrere med Pipedrive API for å opprette organisasjoner, personer, og leads basert på testdata. Koden bruker Guzzle-biblioteket for HTTP-forespørsler.
## Krav
- PHP 7.4 eller nyere: Sørg for at PHP er installert og oppdatert på systemet ditt.
- Composer: For å installere avhengigheter 
- Guzzle: Et HTTP-klientbibliotek for PHP som brukes til å håndtere API-forespørsler.
- Et gyldig Pipedrive API-token: Få API-nøkkelen fra Pipedrive-kontoen din for autentisering med API-et.

1. Installer PHP og Composer

For macOS:

1. Installer PHP ved hjelp av Homebrew:
`brew install php`
2. Installer Composer:
`brew install composer`
2. Installer avhengigheter
Naviger til prosjektmappen og kjør `composer install`  
3. Konfigurer API-nøkkel
Finn variabelen apiKey i koden, og erstatt dens verdi med din egen Pipedrive API-token. Eksempel:
`$apiKey = "API_KEY";`
4. Legg til testdata

Plasser testdataene dine i en JSON-fil i test/test_data.json. Eksempel på format:
```
{
"name" : "Ola Nordmann",
"phone" : "12345678",
"email" : "ola.nordmannn@online.no"
"housing_type" : "Enebolig",
"property_size" : 160,
"deal_type" : "Spotpris",
"contact_type" : "Privat"
}
```

5. Kjør koden
Kjør PHP-skriptet ved å bruke følgende kommando i terminalen:
`php pipedrive_lead_integration.php`

## Nøkkelfunksjoner
- Oppretter en organisasjon i Pipedrive.
- Knytter en person til den opprettede organisasjonen.
- Oppretter et lead som er koblet til personen og organisasjonen.
- Håndterer Pipedrive egendefinerte felt ved å bruke feltnummer (ID) i stedet for feltnavn.

## Læringsnotater
Som nybegynner i PHP startet jeg med å lære grunnleggende PHP, og brukte ChatGPT som veiledning. I starten møtte jeg utfordringer med egendefinerte felt i Pipedrive. Etter å ha testet og eksperimentert, fant jeg API-dokumentasjon om å bruke feltnumre (ID) i stedet for feltnavn for egendefinerte felt. Denne løsningen ble inspirert av en diskusjon med min mann.





