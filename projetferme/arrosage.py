import serial
import time

# Définir les broches Arduino pour le capteur d'humidité et la pompe à eau
sensor_pin = 17
pump_pin = 18

# Ouvrir la connexion série avec l'Arduino
ser = serial.Serial('/dev/ttyUSB0', 9600)  # remplacez '/dev/ttyUSB0' par le port série de votre Arduino

# Valeur seuil d'humidité du sol
seuil_humidite = 50

def read_sensor_value():
    # Envoyer la commande pour lire la valeur du capteur d'humidité
    ser.write(b'read_sensor')
    response = ser.readline().decode().strip()
    return int(response)

def activate_pump():
    # Envoyer la commande pour activer la pompe
    ser.write(b'activate_pump')
    response = ser.readline().decode().strip()
    print(response)

def deactivate_pump():
    # Envoyer la commande pour désactiver la pompe
    ser.write(b'deactivate_pump')
    response = ser.readline().decode().strip()
    print(response)

while True:
    # Lire la valeur du capteur d'humidité
    valeur_humidite = read_sensor_value()

    # Si l'humidité est inférieure au seuil, activer la pompe
    if valeur_humidite < seuil_humidite:
        print("Arrosage en cours...")
        activate_pump()
        time.sleep(10)  # Durée d'arrosage en secondes
        deactivate_pump()
        print("Arrosage terminé.")
    else:
        print("Humidité du sol suffisante.")

    # Attendre un délai avant la prochaine mesure
    time.sleep(60)  # Intervalle de mesure en secondes

# Fermer la connexion série
ser.close()