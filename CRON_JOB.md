```
apt update
apt install cron
```

```
service cron start
```

```
apt install nano
```
Om een cron job te maken open de crontab

```
crontab -e
```

Voeg de cron job toe:
Dit zorgt ervoor dat het script "SetRoundsCommand.php" elke maand om 3 uur 's nachts draait:

```
0 3 1 * * /usr/local/bin/php /var/www/bin/console app:set-rounds >> /var/log/predictions.log 2>&1

```

Ophalen van wedstrijden en teams:
```
0 3 1 * * /usr/local/bin/php /var/www/bin/console app:import-fixtures >> /var/log/predictions.log 2>&1

```

Wil je het elke week laten draaien? Gebruik dan dit commando voor "UpdatePredictionsCommand.php":

```
0 2 * * 0 /usr/local/bin/php /var/www/bin/console app:update-predictions >> /var/log/predictions.log 2>&1
```

cron job start commando's:

```
service cron start

```

```
crontab -l
```



# Uitleg:
0: Minuut (0, dus precies op het hele uur).

3: Uur (3, dus 03:00 uur).

1: De eerste dag van de maand.

*: Elke maand.

*: Elke dag van de week (dit veld wordt genegeerd omdat dag van de maand al is gespecificeerd).


# log maken voor cronjob
```
touch /var/log/predictions.log
```

## Handmatig een cronjob draaien
```

/usr/local/bin/php /var/www/bin/console app:update-predictions >> /var/log/predictions.log 2>&1
```
```

/usr/local/bin/php /var/www/bin/console app:set-rounds >> /var/log/predictions.log 2>&1
```
# en dan kijken in de log of dat gelukt is 

```
cat /var/log/predictions.log
```
#handmatig een cronjob draaien:
```
 php bin/console app:set-rounds
```
```
 php bin/console app:import-fixtures

```

```
php bin/console app:update-predictions
```

