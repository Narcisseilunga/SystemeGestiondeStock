import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# Pour envoyer un e-mail
def envoyer_email(contenu, sujet, email_destinataire, email_expediteur, mot_de_passe):
    message = MIMEMultipart()
    message['From'] = email_expediteur
    message['To'] = email_destinataire
    message['Subject'] = sujet

    # Corps du message
    message.attach(MIMEText(contenu, 'plain'))

    # Connexion au serveur sortant et envoi de l'e-mail
    serveur = smtplib.SMTP('smtp.gmail.com', 587)
    serveur.starttls()
    serveur.login(email_expediteur, mot_de_passe)
    texte = message.as_string()
    serveur.sendmail(email_expediteur, email_destinataire, texte)
    serveur.quit()

# Pour envoyer un SMS via l'API Textbelt
def envoyer_sms(contenu, numero_destinataire):
    import requests
    reponse = requests.post('https://textbelt.com/text', {
        'phone': numero_destinataire,
        'message': contenu,
        'key': 'textbelt',  # Vous pouvez obtenir une clé API gratuite pour un essai
    })
    return reponse.json()

# Utilisation des fonctions
"""contenu_email = "Votre contenu d'email ici"
sujet_email = "Sujet de l'email"
email_destinataire = "21ik062@esisalama.org"
email_expediteur = "ilunganarcisse353@gmail.com"
mot_de_passe = "nar6ilu2"

envoyer_email(contenu_email, sujet_email, email_destinataire, email_expediteur, mot_de_passe)
"""
contenu_sms = "Votre contenu SMS ici"
numero_destinataire = "+243971001691"

resultat_sms = envoyer_sms(contenu_sms, numero_destinataire)
print(resultat_sms)
