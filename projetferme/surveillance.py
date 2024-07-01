import threading
import cv2
import tkinter as tk
from tkinter import ttk


face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
profile_face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_profileface.xml')
eye_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_eye.xml')
body_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_fullbody.xml')


adresses_ip = ['192.168.144.245:4747','192.168.144.245:4747', '192.168.144.76:4747', '192.168.0.103:4747']

def obtenir_choix_utilisateur():
    def on_select(event):
        choix = combo.current()
        root.destroy()
        thread = threading.Thread(target=afficher_flux_video, args=(choix,))
        thread.start()

    root = tk.Tk()
    root.title("Sélection de la Caméra")
    root.geometry("300x150")
    root.configure(bg='#2c3e50')

    label = tk.Label(root, text="Choisissez la caméra à afficher :", bg='#2c3e50', fg='white', font=('Helvetica', 12))
    label.pack(pady=10)

    combo = ttk.Combobox(root, values=[f"Caméra {i+1}" for i in range(len(adresses_ip))], font=('Helvetica', 12))
    combo.pack(pady=10)
    combo.bind("<<ComboboxSelected>>", on_select)

    style = ttk.Style()
    style.theme_use('clam')
    style.configure("TCombobox", fieldbackground="#34495e", background="#34495e", foreground="white")

    root.mainloop()

def afficher_flux_video(choix_utilisateur):
    cap = cv2.VideoCapture(f'http://{adresses_ip[choix_utilisateur]}/video')
    while True:
        ret, frame = cap.read()
        frame = cv2.resize(frame, (640, 480))
        if not ret:
            print(f"Impossible de recevoir le flux de la caméra {choix_utilisateur+1}. Sortie...")
            break
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

        profiles = profile_face_cascade.detectMultiScale(gray, scaleFactor=1.2, minNeighbors=7, minSize=(50, 50))
        for (x, y, w, h) in profiles:
            cv2.rectangle(frame, (x, y), (x + w, y + h), (255, 0, 0), 2)

        faces = face_cascade.detectMultiScale(gray, scaleFactor=1.4, minNeighbors=7, minSize=(50, 50))
        for (x, y, w, h) in faces:
            cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
            roi_gray = gray[y:y + h, x:x + w]
            roi_color = frame[y:y + h, x:x + w]
            eyes = eye_cascade.detectMultiScale(roi_gray)
            for (ex, ey, ew, eh) in eyes:
                cv2.rectangle(roi_color, (ex, ey), (ex + ew, ey + eh), (255, 0, 0), 2)

        bodies = body_cascade.detectMultiScale(gray, scaleFactor=1.2, minNeighbors=5, minSize=(50, 50))
        for (bx, by, bw, bh) in bodies:
            cv2.rectangle(frame, (bx, by), (bx + bw, by + bh), (0, 0, 255), 2)

        cv2.imshow(f'Flux en Direct Caméra {choix_utilisateur+1}', frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()

obtenir_choix_utilisateur()