Lokaal testen (Flask + Gmail SMTP)
==================================
Het Python-bestand heet nu:  flask-local-SAVE-AS-app-py.txt
(Geen .py in de map = Hostinger-migratie / ZIP blokkeert geen archief meer.)

Lokaal ontwikkelen:
  1. Kopieer  flask-local-SAVE-AS-app-py.txt  naar  app.py  in deze map.
  2. pip install -r requirements.txt
  3. python app.py
  4. Open http://127.0.0.1:5000

Zet .env in deze map of projectroot met GMAIL_APP_PASSWORD.

HOSTING: je mag de hele map local-dev uploaden (alleen .txt / geen .py),
of local-dev weglaten — de live site gebruikt send.php, niet Flask.
