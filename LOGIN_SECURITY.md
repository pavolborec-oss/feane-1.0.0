# Zabezpečená Login Stránka - Feane

## Vytvorené súbory:

1. **login.php** - Login stránka s formulárom
2. **logout.php** - Odhlasovacia stránka  
3. **admin.php** - Administračný panel (chránený prihlásením)

---

## Bezpečnostné opatrenia:

### 1. **PHP Session Management**
- Používame `session_start()` na všetkých chránených stránkach
- Session premenné na uloženie údajov o prihlásenom používateľovi
- Session timeout po 30 minútach nečinnosti

### 2. **CSRF Ochrana (Cross-Site Request Forgery)**
- Generovanie CSRF tokenu v session: `$_SESSION['csrf_token']`
- Overovanie tokenu pri spracovaní formulárov
- Prevencia neautorizovaných požiadaviek

### 3. **Password Hashing**
- Hesla sú hashovaná pomocou `password_hash()` s algoritmom BCRYPT
- Overenie hesiel pomocou `password_verify()`
- Nikdy neukladáme hesla v plain text

### 4. **XSS Ochrana (Cross-Site Scripting)**
- Sanitizácia výstupov pomocou `htmlspecialchars()`
- Prevenciu injekcie HTML/JavaScript kódu

### 5. **Input Sanitizácia**
- Kontrola prázdnych polí
- Trim() na odstránenie extra whitespace
- Typové konverzie (intval, floatval)

### 6. **Session Security**
- Skrytý CSRF token v každom formulári
- Overenie tokenu pred spracovaním
- Preusmerovania (header()) na zabránenie cache

---

## Demo Účty:

**Admin:**
- Meno: `admin`
- Heslo: `admin123`

**Užívateľ:**
- Meno: `user`
- Heslo: `user123`

---

## Ako to funguje:

### Login proces:
1. Používateľ zadá meno a heslo na `login.php`
2. Validácia vstupov
3. Overenie CSRF tokenu
4. Porovnanie hesla s hashovaným heslom z databázy
5. Ak je správne, vytvorí sa session
6. Presmerovanie na `admin.php`

### Admin panel:
1. Na začiatku kontrola či je session nastavená
2. Ak nie, presmerovanie na login
3. Kontrola session timeout
4. Obnova login času pri aktvnosti
5. Administrácia produktov (CRUD operácie)

### Logout:
1. Vymazanie všetkých session údajov
2. Zničenie session (`session_destroy()`)
3. Presmerovanie na úvodnú stránku

---

## Budúce vylepšenia:

- [ ] Pripojenie k reálnej databáze (MySQL/PostgreSQL)
- [ ] Viacnásobné pokusy o prihlásenie (rate limiting)
- [ ] Dvoufaktorová autentifikácia (2FA)
- [ ] Remember me funkcia
- [ ] Email verifikácia
- [ ] Password reset
- [ ] SQL Injection ochrana (prepared statements)
- [ ] HTTPS/SSL encryption
- [ ] Logging a monitoring

---

## Testovanie:

1. Otvor `http://localhost/feane-1.0.0/feane-1.0.0/login.php`
2. Prihlás sa pomocou demo údajov
3. Vidíš administračný panel s produktmi
4. Môžeš pridávať, upravovať a mazať produkty
5. Klikni "Odhlásiť sa" na vrátenie sa na login

---

Bezpečnosť vytvorená s PHP! 🔐
