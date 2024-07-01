import sqlite3

con = sqlite3.connect(r'system.db')
cur = con.cursor()
con.execute("UPDATE  product SET image_prod= 'oppo.jpg' WHERE id=5")
con.execute("UPDATE  product SET image_prod= 'iPhone Wallpaper, Diagonal Gradient, High Resolution, Instant Download.jpg'  WHERE id=6")
con.execute("UPDATE  product SET image_prod= 'Xiaomi Redmi Note 9 128gb_4gb Factory GSM Unlocked - International Global Version - Forest Green.jpg'  WHERE id=7")
con.execute("UPDATE  product SET image_prod= 'Samsung 32_ (32T5300AUXCE).jpg' WHERE id=8")
con.execute("UPDATE  product SET image_prod= 'AI AirPods Pro.jpg' WHERE id=9")
con.commit()
con.close()