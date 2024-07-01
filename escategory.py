import datetime

date_aujourdhui = datetime.date.today()
heure_actuelle = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

print(f'La date d\'aujourd\'hui est le {date_aujourdhui}')
print(f'L\'heure actuelle est {heure_actuelle}')