#!/usr/bin/env python
print("Content-Type: text/html")
print()
import cgi
args = []

import MySQLdb
hostname = 'localhost'
username = 'viola'
password = 'jennahaze'
database = 'viola'
myConnection = MySQLdb.connect( host=hostname, user=username, passwd=password, db=database )
cur = myConnection.cursor()

import cgitb
cgitb.enable()
form = cgi.FieldStorage()

students = []

print ('<nav class="navbar bg-primary">   <div class="container justify-content-center d-flex">     <a class="text-white navbar-brand" href="#">       <img src="/logo.png" width="72" >&nbsp;saक्षam</a>   </div> </nav>')
#print (f"<div class='alert alert-success'>{form}</div>")

print ("""
<form enctype='multipart/form-data' action='/run.py' method='post' class='section row m-4 p-4 shadow'>
    <div class='col-4'>
      <label class="text-muted">Upload photo</label>    
        <input placeholder='Enter image URL' name='url' type='text' class='d-none form-control' id='basic-url' aria-describedby='basic-addon3'>
        <div class="input-group mb-3">
          <input name="image" type="file" class="form-control" id="inputGroupFile02">
        </div>
    </div>
    <div class='col-3'>
      <label class="text-muted">Face Database</label>
      <select class='form-select' name='f'>
        <option value='demo/faces'>Demo</option>
        <option value='photos/2'>Semester 2</option>
        <option value='photos/4'>Semester 4</option>
        <option value='photos/6'>Semester 6</option>
        <option value='photos/all'>All (slow !)</option>
        <option value='photos/staff'>Faculty</option>
      </select>
    </div>
    <div class='col-3'>
      <label class="text-muted">Recognition Model</label>
      <select class='form-select' name='m'>
        <option>Facenet512</option>
        <option>SFace</option>
        <option>ArcFace</option>
        <option>Dlib</option>
        <option>Facenet</option>
        <option>VGG-Face</option>
        <option>Human-beings</option>
        <option>OpenFace</option>
        <option>DeepID</option>
      </select>
    </div>
    <button type='submit' class='p-2 col-2 btn btn-primary'><i class="fas fa-fingerprint"></i>&nbsp;Detect</button>
</form>
""")

print ('<div class="section m-4 p-4 shadow">\
<div class="accordion" id="accordionExample">\
  <div class="accordion-item">\
    <h2 class="accordion-header" id="headingOne">\
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">\
        Console\
      </button>\
    </h2>\
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">\
      <div class="accordion-body">')


print ('<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-auth-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-analytics-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-messaging-compat.js"></script> <script src="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.js"></script> <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> <link rel="shortcut icon" type="image/jpg" href="anneli/assets/img/favicon.png"/> <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script> <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> <link type="text/css" rel="stylesheet" href="/anneli/themer_mdl.php?font=Montserrat&theme=blue_deep_orange&skin=materia"> <link type="text/css" rel="stylesheet" href="/anneli/themer2.php?font=Montserrat&theme=blue_deep_orange&skin=materia"> <link type="text/css" rel="stylesheet" href="anneli/assets/css/all.min.css"> <link type="text/css" rel="stylesheet" href="anneli/assets/css/style.css"> <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.css" /> <script src="/firebaseConfig.js?1684940450"></script> <script src="anneli/util.js?1684940450"></script> <script src="anneli/mime.js"></script> <script src="anneli/colors.js?"></script> <script src="anneli/fonts.js?"></script> ')
import sys, os
from PIL import Image, ImageDraw, ImageFont
import urllib.request
image = None
model = "VGG-Face"
folder = None
if "image" not in form:
    args = cgi.parse()
if "i" in args:
    image = args ["i"][0]
if "f" in args:
    folder = args ["f"][0]

if form.getfirst ("url") is not None:
    image = form.getfirst ("url")
if "image" in form:
    fn = os.path.basename(form ["image"].filename)
    open('tmp/' + fn, 'wb').write(form ["image"].file.read())
    image = 'tmp/' + fn
if form.getfirst ("f") is not None:
    folder = form.getfirst ("f")
if form.getfirst ("m") is not None:
    model = form.getfirst ("m")

# if image.startswith ("http"):
    # urllib.request.urlretrieve(image, "/var/www/viola/tmp/" + os.path.basename (image))
    # image = "tmp/" + os.path.basename (image)
       
print ("<div class='alert alert-warning'>",image, folder,"</div>")
def drawFaces (img, x, y, w, h):
    with Image.open(img) as im:
        draw = ImageDraw.Draw(im)
        draw.rectangle ([x, y, w, h], "red", "red", 5)
        im.save(sys.argv [1] + "-detect", "JPG")
#faces = DeepFace.extract_faces (sys.argv [1])
#print (faces)
if len (sys.argv) > 2:
    image = sys.argv [1]
    folder = sys.argv [2]
if len (sys.argv) > 3:
    model = sys.argv [3]

if (image is None or folder is None):
    exit ()

from deepface import DeepFace

d = DeepFace.find (image, folder, enforce_detection=False, model_name = model)
all_faces = DeepFace.extract_faces (image, enforce_detection=False)

im = Image.open(image)
draw = ImageDraw.Draw(im)

for _face in all_faces:
    draw.rectangle ([_face ["facial_area"]["x"], _face ["facial_area"]["y"], _face ["facial_area"]["x"] + _face ["facial_area"]["w"], _face ["facial_area"]["y"] + _face ["facial_area"]["h"]], None, "red", 3)

fnt = ImageFont.truetype("/usr/share/fonts/julietaula-montserrat-fonts/Montserrat-Regular.otf", 20)

for face in d:
    try:
        color = "red"
        model_ = model + "_cosine"
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
                sql = f"SELECT * from students where photo like '%{os.path.basename (name)}%'"
                cur.execute (sql)
                result = cur.fetchall ()
                print (f"SQL ({sql}): {result}")
                students.append (result)
                rollno = result[0][2]
                print ("drawing text...")
                n = name
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
im.save("/var/www/viola/tmp/" + os.path.basename (image), "JPEG")
print ("<section class='row'>")
print ("<img class='img-fluid' src='tmp/" + os.path.basename (image) + "'>")
print ("</section>")
print ("<section class='row justify-content-center'>")
for _student in students:
    student = _student [0]
    print ('<div class="card col-md-3 m-2">')
    print (f'  <img src="{student [6]}" class="img-fluid card-img-top">')
    print (f'  <div class="card-body text-center">')
    print (f'\
      <div class="card-header">\
        {student [1]} [{student [2]}]\
      </div>\
      <h4 class="card-title m-2">{student [0]}</h4>\
      <p class="card-text"><b>Semester {student [1][0]} </b><br>{student [3].replace (";", "<br>")}</p>\
      <div class="card-footer text-muted">\
        {student [4]}  {student [5]}\
      </div></div></div>\
    ')
print ("</section>")
