BEGIN TRANSACTION;
CREATE TABLE category(id INTEGER PRIMARY KEY AUTOINCREMENT, name text);
INSERT INTO "category" VALUES(4,'Mobiles');
INSERT INTO "category" VALUES(5,'Televisions');
INSERT INTO "category" VALUES(6,'Gadget');
CREATE TABLE commandes(id INTEGER PRIMARY KEY AUTOINCREMENT,nom_produit text, prix text, quantite text, nom_client text, adresse_client text, numero_client text, temp text);
INSERT INTO "commandes" VALUES(1,'Kunai','10.99','2','Naruto Uzumaki','123 Rue des Ninjas, Konoha','0612345678','2023-07-08');
INSERT INTO "commandes" VALUES(2,'Katana','99.99','1','Tanjiro Kamado','456 Rue des D�mons, Quartier Yoshiwara','0698765432','2023-07-09');
INSERT INTO "commandes" VALUES(3,'Rouleau de Jutsus','29.99','3','Sasuke Uchiha','789 Rue de la Vengeance, Quartier Uchiha','0612121212','2023-07-10');
INSERT INTO "commandes" VALUES(4,'Masque de Nomu','49.99','1','Shoto Todoroki','159 Rue des H�ros, Quartier UA','0654545454','2023-07-11');
INSERT INTO "commandes" VALUES(5,'Bandeau de Ninja','14.99','5','Zenitsu Agatsuma','357 Rue des Tonnerres, Quartier Demon Slayer','0687878787','2023-07-12');
CREATE TABLE compte(id INTEGER PRIMARY KEY AUTOINCREMENT,nom text,postnom text, prenom text, email text, contact text, adresse text, numero text, temp text, password TEXT, type TEXT);
INSERT INTO "compte" VALUES(1,'Uzumaki','Namikaze','Naruto','naruto@konoha.com','0612345678','123 Rue des Ninjas, Konoha','0612345678','2023-07-08','1234','client');
INSERT INTO "compte" VALUES(2,'Kamado','Tanjirou','Tanjiro','tanjiro@demonslayer.com','0698765432','456 Rue des D�mons, Quartier Yoshiwara','0698765432','2023-07-09','1234','client');
INSERT INTO "compte" VALUES(3,'Uchiha','Sarutobi','Sasuke','sasuke@uchiha.com','0612121212','789 Rue de la Vengeance, Quartier Uchiha','0612121212','2023-07-10','1234','client');
INSERT INTO "compte" VALUES(4,'Todoroki','Shouto','Shoto','shoto@ua.com','0654545454','159 Rue des H�ros, Quartier UA','0654545454','2023-07-11','1234','client');
INSERT INTO "compte" VALUES(5,'Agatsuma','Zenitsu','Zenitsu','zenitsu@demonslayer.com','0687878787','357 Rue des Tonnerres, Quartier Demon Slayer','0687878787','2023-07-12','1234','client');
INSERT INTO "compte" VALUES(6,'Kimetsu','Kyoujurou','Rengoku','rengoku@demonslayer.com','0698989898','159 Rue des Flammes, Quartier Demon Slayer','0698989898','2023-07-13','1234','client');
INSERT INTO "compte" VALUES(7,'Uzumaki','Boruto','Boruto','boruto@konoha.com','0612345656','123 Rue des Nouveaux Ninjas, Konoha','0612345656','2023-07-14','1234','client');
CREATE TABLE employee(id INTEGER PRIMARY KEY AUTOINCREMENT, name text, email text, gender text, contact text, dob text, doj text, password text, type text, address text, salary text, statut_emp VARCHAR(20), image_emp TEXT, info_emp TEXT);
INSERT INTO "employee" VALUES(1,'Casterman','21ik062@esisalama.org','Homme','0854484508','21/01/2003','06/05/2018','0000','Admin','numero 46 av luebo Q/gcm','123000','Actif','_MG_5488.jpg-.jpg','CEO & Admin');
INSERT INTO "employee" VALUES(2,'Danny Ilunga','Danny@gmail.com','Homme','0854484508','06/07/1986','08/01/2020','0000','surveillant','Gueliz, Marrakech','5000','Actif','8655138ace5670bf86acdecbf7f1dc51.jpg','Administrateur des syst�mes reseau');
INSERT INTO "employee" VALUES(3,'Danny Lotimba','Getsuyatensho@gmail.com','Homme','0996645559','06/07/1990','02/01/2018','0000','surveillant','Massira, Marrakech
','5000','Actif','94acc9fb180ff3012387f1407e72e9cf.jpg','Project Manager');
INSERT INTO "employee" VALUES(4,'Alimasi','ali@344gmail.com','Homme','099019345','06/07/2002','10/07/20�2','0000','Employ�','tulipier','20000','Actif','828d853720269765b836bdda148395ff.jpg','Graphique Designer');
CREATE TABLE line_sale(invoice INTEGER, product_id INTEGER , price text, qty Text, PRIMARY KEY (invoice, product_id), FOREIGN KEY (invoice) REFERENCES sales(invoice), FOREIGN KEY (product_id) REFERENCES product(id));
INSERT INTO "line_sale" VALUES(24172454,6,'5000.0','1');
INSERT INTO "line_sale" VALUES(24172454,9,'5000.0','2');
INSERT INTO "line_sale" VALUES(24225533,5,'1300.0','1');
INSERT INTO "line_sale" VALUES(8263251,8,'3200.0','1');
INSERT INTO "line_sale" VALUES(2196658,10,'2500.0','5');
CREATE TABLE message(id INTEGER PRIMARY KEY AUTOINCREMENT, name text, obj text,temp text, email TEXT, number TEXT);
INSERT INTO "message" VALUES(1,'kabanza','BOJOUR VOS SERVICES ?','2024-07-07 14:30:02','21IKM081@ESISALAMA.ORG','098');
INSERT INTO "message" VALUES(2,'JACQUES','ARGENT','2024-09-18 13:39:39','21IK062@ESISALAMA.ORG','0997840980');
CREATE TABLE product(id INTEGER PRIMARY KEY AUTOINCREMENT, category text, supplier text, name text, price text, qty text, status text, image_prod TEXT);
INSERT INTO "product" VALUES(5,'Mobiles','Silva','Oppo f1s','1300','90','actif','');
INSERT INTO "product" VALUES(6,'Mobiles','Alfons','iphone X','5000','48','actif','iPhone Wallpaper, Diagonal Gradient, High Resolution, Instant Download.jpg');
INSERT INTO "product" VALUES(7,'Mobiles','Silva','redmi note 9','3500','20','actif','Xiaomi Redmi Note 9 128gb_4gb Factory GSM Unlocked - International Global Version - Forest Green.jpg');
INSERT INTO "product" VALUES(8,'Televisions','Alfons','Samsung 32 inch','3200','20','actif','Samsung 32_ (32T5300AUXCE).jpg');
INSERT INTO "product" VALUES(9,'Gadget','Marouane Moutawakil','airpods','2500','18','actif','AI AirPods Pro.jpg');
INSERT INTO "product" VALUES(10,'Mobiles','Yassin fouzi','Google Pixel','500','15','actif','d159cbe4f6ba5a6599217f693e4459ac.jpg');
INSERT INTO "product" VALUES(11,'Televisions','Marouane Moutawakil','APPLE _8 INCH','890','34','actif','1f58f507bb5f665802391fdd36a274fa.jpg');
CREATE TABLE sales(invoice INTEGER PRIMARY KEY AUTOINCREMENT, cl_name text, cl_contact text , date text);
INSERT INTO "sales" VALUES(2196658,'nathan','45','02/07/2024');
INSERT INTO "sales" VALUES(8263251,'CASTERMAN','971001691','08/05/2024');
INSERT INTO "sales" VALUES(24172454,'wafa','0898776544','24/05/2022');
INSERT INTO "sales" VALUES(24225533,'yassine','0661348890','24/05/2022');
CREATE TABLE supplier(invoice INTEGER PRIMARY KEY AUTOINCREMENT, name text, contact text, desc text);
INSERT INTO "supplier" VALUES(1,'Gyomei Himejima','0988876787','grossiste : telephones portables');
INSERT INTO "supplier" VALUES(2,'Kenny Ackerman','0996645559','grossiste : AirPods

');
INSERT INTO "supplier" VALUES(3,'Nid','0972352918','Fourniture �quipement bureaux
');
DELETE FROM "sqlite_sequence";
INSERT INTO "sqlite_sequence" VALUES('employee',106);
INSERT INTO "sqlite_sequence" VALUES('supplier',1113);
INSERT INTO "sqlite_sequence" VALUES('category',9);
INSERT INTO "sqlite_sequence" VALUES('product',12);
INSERT INTO "sqlite_sequence" VALUES('sales',24225533);
INSERT INTO "sqlite_sequence" VALUES('message',2);
INSERT INTO "sqlite_sequence" VALUES('commandes',5);
INSERT INTO "sqlite_sequence" VALUES('compte',7);
COMMIT;
