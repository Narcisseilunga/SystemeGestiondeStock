import sqlite3

con = sqlite3.connect(r'system.db')
cur = con.cursor()

con.execute("UPDATE  employee SET statut_emp= 'Actif', image_emp = '_MG_5488.jpg-.jpg', info_emp = 'CEO & Admin' WHERE id=1")
con.execute("UPDATE  employee SET statut_emp= 'Actif', image_emp = '8655138ace5670bf86acdecbf7f1dc51.jpg', info_emp = 'Administrateur des systèmes reseau' WHERE id=2")
con.execute("UPDATE  employee SET statut_emp= 'Actif', image_emp = '94acc9fb180ff3012387f1407e72e9cf.jpg', info_emp = 'Project Manager' WHERE id=3")
con.execute("UPDATE  employee SET statut_emp= 'Actif', image_emp = '828d853720269765b836bdda148395ff.jpg', info_emp = 'Graphique Designer' WHERE id=4")


con.commit()
con.close()