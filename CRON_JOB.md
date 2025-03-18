Om een cron job te maken open de crontab

```
crontab -e
```

Voeg de cron job toe:
Dit zorgt ervoor dat het script "UpdatePredictionsCommand.php" elke maandag om 3 uur 's nachts draait:

```
0 3 * * 1 /usr/bin/php /path/to/your/project/bin/console app:update-predictions >> /var/log/predictions.log 2>&1

```

Wil je het elke nacht laten draaien? Gebruik dan:

```
0 2 * * * /usr/bin/php /path/to/your/project/bin/console app:update-predictions >> /var/log/predictions.log 2>&1
```

cron job start commando's:

```
service cron start

```

crontab -l

crontab -e

cat /var/log/predictions.log

