### Atgimusi Lineage 2 serverių donate sistema
![Donate](http://asmanavicius.lt/donate/intro.png)
##### Apie
Ši sistema nėra perkurta senoji sistema, tai nauja, tobulesnė ir saugesnė, turinti daugiau galimybių sistema. Šios sistemos autorius, kaip ir senosios, esu aš, manau tai puikus įrodymas, jog sistemoje nėra jokio žalingo kodo (backdoor ar kt.)

##### Sistemos įdiegimas / atnaujinimas
Norėdami įdiegti sistemą atsisiųskite visus failus, išskleiskite juos ten kur norite, kad veiktų sistema. Tuomet įkelkite donate.sql failą į savo duom. bazę, po to atidarykite "settings" aplanką (jį rasite ten kur sukėlėte visus failus), jame turėtų būti keli failai kuriuose rasite daug įvairių nustatymų, juos galite koreguoti pagal save.

Norėdami atnaujinti sistemą vėl gi atsisiųskite visus failus ir pakeiskite jais senuosius, po to atnaujinkite duom. bazę su donate.sql failiuku (geriausia jei ištrintumėte senas donate duom. bazės lentutes ir tada sukurtumėte naujas).

##### Šiuo metu sistemoje veikiantys balanso pildymo būdai
* Paypal
* Mokejimai.lt (Paysera) SMS, bei bankiniai apmokėjimai
* Paygol

##### Mokejimai / Paysera SMS nustatymai
Kelias iki callback failo mokejimai.lt sistemoje: http://manopuslapis.lt/index.php/paysera-sms/notify (p.s vietoje manopuslapis.lt nurodykite savo donate sistemos puslapį).

##### Paygol SMS nustatymai
Kelias iki "Url Background (IPN)" paygol sistemoje: http://manopuslapis.lt/index.php/paygol/notify (p.s vietoje manopuslapis.lt nurodykite savo donate sistemos puslapį). Kitų URL (accept ir cancel) nurodyti nereikia.

##### Pagrindiniai dalykai esantys sistemoje šiuo metu
* Daugybė nustatymų
* Galimybė naudoti sistemą su daugiau, nei 1 serveriu
* Galima naudoti atskirus Login ir Game serverius
* Daiktų parduotuvė (galima pirkti tiek pavienius daiktus, tiek visą jų grupę)
* Balanso pildymas įvairiais būdais (paypal, sms, banku ir kt.)
* Veiksmų istorija
* Vartotojo nustatymai (kaip kad slaptažodžio ar el. pašto keitimas)
* Slaptažodžio keitimas jį pamiršus
* Captcha apsaugos kodai
* Kalbų keitimas
* Slaptažodžio užkodavimas pagal nurodytą serverį
* Veikia su bet kuria Lineage 2 kronika (yra galimybė nurodyti duom. bazės stulpelių pavadinimus kiekvienam serveriui)
* Paprastas išvaizdos keitimas

##### Kontaktai
Jei sistemoje radote klaidų, turite pasiūlymų ar norite tiesiog pasibėdavoti apie gyvenimą rašykite el. paštu justas.asmanavicius@gmail.com
