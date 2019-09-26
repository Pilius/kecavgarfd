**Eilių valdymo sistema**

**Eilių valdymo sistemą sudaro 5 puslapiai:**

1. Naujo vartotojo registracija
2. Laukiančiųjų eilės atvaizdavimas
3. Specialisto puslapis klientų priėmimui
4. Specialistų darbo efektyvumo įvertinimo lentelė
5. Asmeninė klientui likusio laukti laiko forma

**Puslapių aprašymas:**

        Slaptoje įstaigoje klientus priima atsipalaidavęs specialistas Aspecialistas, baikštusis - Bspecialistas, susiraukęs lyg nuo citrinos - Cspecialistas ir paskutinis, bet ne darbščiausias - Dspecialistas. Specialistai turi savo neįveikiamus slaptažodžius atitinkamai a123,b123,c123,d123. **BUG&#39;ai:** _nekorektiškai indikuojamas prisijungęs darbuotojas ir niuansai su „session&quot; kintamaisiais._

        Atvykęs klientas privalo užsiregistruoti registracijos formoje įvesdamas savo vardą, pavardę ir pasirinkdamas pas kurį specialistą nori patekti. Jeigu žvaigždės išsirikiuoja vienoje tiesėje, zodiako ženklas pereina į svarstykles(angl. rissing edge triger) , tuomet parodomas užrašas &quot;užregistruota sėkmingai&quot; ir klientas gauna unikalų savo PIN kodą, su kuriuo gali stebėti kiek jam liko laiko laukti. Jeigu neatitiko duomenų bazės pavadinimas, slaptažodžiai arba sql užklausa, pranešimas &quot;Užregistruoti nepavyko, skambink&quot;.

        Užsiregistravęs klientas gali pasinaudoti &quot;ar jau mano eilė?&quot; forma, kuri parodo, kad dar tikrai ne tavo eilė ir laukti liko visą amžinybę. Arba mažiau. Duomenų bazėje radus tinkamą vardo ir PIN kodo derinį, rodomas likęs laukti laikas iki numatomo aptarnavimo laiko. Numatomas aptarnavimo laikas nustatomas, pagal prieš klientą esančių laukiančiųjų  kiekio ir vidutinį pasirinkto specialisto vidutiniškai kiekvienam klientui skiriamo laiko sandaugą.

        Žiūrėti į laukiančiųjų eilę taip pat įdomu, kaip žiūrėti į elektros lemputę. Jeigu esi elektrikas. Jeigu nesi, pasirenkamas aktualus specialistas, siekiant pagreitinti sql užklausą sumažinamas rodomų rezultatų kiekis ir švieslentėje parodomi tik šio specialisto dar neaptarnauti ir aukščiausiai pagal eilę esantys klientai.

        Specialistų ataskaitoje pateikiama informacija kiek laiko buvo dirbama su kiekvienu klientu. _Proga ir vieta pritempti punktą apie 1:daug, panaudoti sql INNER JOIN ir rikiavimo komandas._

        Specialisto skiltis. Tai yra specialisto darbo namai, o namie jis jaučiasi gerai. Darbotvarkė:

1. Prisijungiama su Aspecialistas ir a123 arba panašiai. Jeigu neteisingi duomenys, rodomas klaidos užrašas
2. Išsaugomas sesijos kintamasis, rodomas prisijungto specialisto vardas parodomi mygtukui kito eilėje esančio kliento priėmimui arba paleidimui.
3. Paspaudus priimti klientą mygtuką duomenų bazėje patikrinama ar specialistas dar neturi paėmęs kliento. Jeigu turi, rodoma su kuo jis šiuo metu dirba. Jeigu neturi - imamas kitas klientas iš eilės, klientai lentelėje išsaugomas darbo pradžios laikas. Darbuotojai lentelėje specialistui priskiriamas kliento id.
4. Paspaudus atleisti klientą mygtuką, klientai lentelėje, tam klientui pažymima, kad jis aptarnautas, apskaičiuojamas aptarnavimui sugaištas laikas (&quot;00:10:00&quot; 10min), pagal tai atnaujinamas specialisto vidutinis klientui skiriamas aptarnauti laikas, perskaičiuojami reikalingi laukti ir numatomi visų šio specialisto klientų laikai. _Nesugalvojau gero ir lengvo būdo, kaip visiems klientams atnaujinti duomenis lentelėje be for ciklo ir atskirų užklausų į duomenų bazę pagal kiekvieną klientą._

**Reikalavimai sistemai:**

&quot;apache&quot; web serveris ir mysql duomenų bazė. Šie duomenys konfigūruojami &quot;prisijungimas.php&quot; faile.
