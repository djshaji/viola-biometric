#!/usr/bin/env python
print("Content-Type: text/html")
print()
import sys
import cgi
args = []

import json
import cgitb
cgitb.enable()
form = cgi.FieldStorage()

print (form)
print ("<form enctype='multipart/form-data' action='/api/recognize.py' method='post' class='section row m-4 p-4 shadow'>")
print ("<input name='image' type='text'><input name='folder' type='text'><button type='submit'>Go</button></form>")

import MySQLdb
hostname = 'localhost'
username = 'viola'
password = 'jennahaze'
database = 'viola'
myConnection = MySQLdb.connect( host=hostname, user=username, passwd=password, db=database )
cur = myConnection.cursor()

semester = form.getfirst ("semester")
if len (sys.argv) > 2:
    semester = sys.argv [3]

sql = f"SELECT * from students where rollno like '{semester}%'"
cur.execute (sql)
result = cur.fetchall ()
data = dict ()
for res in result:
    data [res [6].split ("/")[-1]] = res [1]
# print (f"SQL ({sql}): {result}")
# print (data)
image = form.getfirst ("image")
folder = form.getfirst ("folder")

if len (sys.argv) > 1:
    image = sys.argv [1]
    folder = sys.argv [2]
    semester = sys.argv [3]

# import glob
# print (glob.glob (folder + "/*"))
from deepface import DeepFace
from PIL import Image, ImageDraw, ImageFont

# print ("detecting faces ...")
all_faces = DeepFace.extract_faces (image)
# print (all_faces)
im = Image.open(image)
draw = ImageDraw.Draw(im)
d = DeepFace.find (image, folder, enforce_detection=False)
model = "VGG-Face"
detector = "opencv"
similarity_metrics = "cosine"

students = dict ()

for _face in all_faces:
  #  print (_face)
   draw.rectangle ([_face ["facial_area"]["x"], _face ["facial_area"]["y"], _face ["facial_area"]["x"] + _face ["facial_area"]["w"], _face ["facial_area"]["y"] + _face ["facial_area"]["h"]], None, "red", 3)

fnt = ImageFont.truetype("/usr/share/fonts/julietaula-montserrat-fonts/Montserrat-Regular.otf", 20)

for face in d:
    try:
        color = "red"
        model_ = model + "_" + similarity_metrics
        guess = face.get (model_) 
        probability = 0
        current = face.head (1)

        # for fa in guess:
            # print (fa)
            # if fa > 0.3:
                # continue
            # if probability == 0:
            # probability = fa

        if len (current) == 0:
            continue
        print (f'face: {current}')
        probability = current[model_][0]
        if probability is None:
            continue
        #print (len (probability), type (probability), probability.keys (), probability.get ("VGG-Face_cosine"))
        if probability == 0:
            color = "red"
        elif (probability < .3):
            color = "green"
            
        x = current ["source_x"][0]
        y = current ["source_y"][0]
        w = current ["source_w"][0]
        h = current ["source_h"][0]
        name = current ["identity"][0]
        print (f'probability for {name}: [{probability}]')
        try:
            if probability != 0:
                # for n in name:
                # rollno = result[0][2]
                print ("drawing text...")
                n = name.split ("/")[-1]
                print (n)
                rollno = data [n]
                # students [rollno] = 100 - round ((probability) * 100, 2)
                students [rollno] = dict ()
                students [rollno]["probability"] = 100 - round ((probability) * 100, 2)
                students [rollno]["x"] = x
                students [rollno]["y"] = y
                students [rollno]["w"] = w
                students [rollno]["h"] = h
                students [rollno] = json.dumps (students [rollno], default = str)
                draw.rectangle ([x, y - 40, x + w, y], "white", color, 1)
                draw.text((x + 5, y - 35), rollno, font=fnt, fill=color)
                draw.rectangle ([x, y + h, x + w, y + h + 40], "white", color, 1)
                draw.text((x + (w / 3), y + h + 5), str (100 - (int (round ((probability) * 100, 2)))) + " %", font=fnt, fill=color)
                
            print ("drawing face...")
            draw.rectangle ([x, y, x + w, y + h], None, color, 3)
        except TypeError:
            print ("error")
    except SystemError:
        print ("error")
    
    # print (face)
    

print (f'total faces found: {len(d)}')
print ('\
      </div>\
    </div>\
  </div>\
</div>\
')

students ["response"] = 200
im.save(image + "-detect", "JPEG")
print (f"<img src='{image}-detect'>")
print ("__CUT_HERE__")
print ("__CUT_HERE__")
# print ('{"response": 200}')
print (json.dumps (students))
print ("__CUT_HERE__")