import cv2
import time

adresses_ip = ['192.168.144.245:4747', '192.168.144.245:4747', '192.168.144.245:4747', '192.168.144.245:4747']
# Ouvrir les flux vidéo des 4 caméras (0, 1, 2, 3)
cap0 = cv2.VideoCapture(f'http://{adresses_ip[0]}/video')
cap1 = cv2.VideoCapture(f'http://{adresses_ip[1]}/video')
cap2 = cv2.VideoCapture(f'http://{adresses_ip[2]}/video')
cap3 = cv2.VideoCapture(f'http://{adresses_ip[3]}/video')

# Vérifier si les flux vidéo sont ouverts correctement
if not (cap0.isOpened() and cap1.isOpened() and cap2.isOpened() and cap3.isOpened()):
    print("Impossible d'ouvrir les flux vidéo.")
    exit()

# Créer une fenêtre pour afficher les flux vidéo
cv2.namedWindow("Flux vidéo divisé", cv2.WINDOW_NORMAL)
cv2.resizeWindow("Flux vidéo divisé", 800, 600)

while True:
    # Lire les images des 4 caméras
    ret0, frame0 = cap0.read()
    ret1, frame1 = cap1.read()
    ret2, frame2 = cap2.read()
    ret3, frame3 = cap3.read()

    # Vérifier si les images sont bien lues
    if not (ret0 and ret1 and ret2 and ret3):
        print("Impossible de lire les images.")
        break

    # Diviser chaque image en 4 parties
    height, width, _ = frame0.shape
    half_height, half_width = height // 2, width // 2

    frame0 = cv2.resize(frame0, (width//2, height//2))
    frame1 = cv2.resize(frame1, (width//2, height//2))
    frame2 = cv2.resize(frame2, (width//2, height//2))
    frame3 = cv2.resize(frame3, (width//2, height//2))

    top_left = frame0[:half_height, :half_width]
    top_right = frame1[:half_height, half_width:]
    bottom_left = frame2[half_height:, :half_width]
    bottom_right = frame3[half_height:, half_width:]

    # Concaténer les 4 parties en une seule image
    combined_frame = cv2.hconcat([top_left, top_right, bottom_left, bottom_right])

    # Afficher l'image combinée
    cv2.imshow("Flux vidéo divisé", combined_frame)

    # Quitter la boucle si la touche 'q' est pressée
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Libérer les ressources et fermer les fenêtres
cap0.release()
cap1.release()
cap2.release()
cap3.release()
cv2.destroyAllWindows()