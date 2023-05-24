#!/usr/bin/env python
print("Content-Type: text/html")
print()
import cgitb
cgitb.enable()

print ('<nav class="navbar bg-primary">   <div class="container justify-content-center d-flex">     <a class="text-white navbar-brand" href="#">       <img src="/logo.png" width="72" >&nbsp;saक्षam</a>   </div> </nav>')

print ("""
<form action='/run.py' class='section row m-4 p-4 shadow'>
    <div class='col-8'>
        <input placeholder='Enter image URL' name='url' type='text' class='form-control' id='basic-url' aria-describedby='basic-addon3'>
    </div>
    <div class='col-4'>
      <select class='form-select' name='f>
        <option value='demo/faces'>Demo</option>
        <option>Students</option>
        <option>Teachers</option>
        <option>All</option>
      </select>
    </div>
</form>
""")

print ('<div class="section m-3 p-2 shadow">\
<div class="accordion" id="accordionExample">\
  <div class="accordion-item">\
    <h2 class="accordion-header" id="headingOne">\
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">\
        Console\
      </button>\
    </h2>\
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">\
      <div class="accordion-body">')

import cgi
args = cgi.parse()

print ('<script src="util.js"></script>    <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-auth-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-analytics-compat.js"></script> <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-messaging-compat.js"></script> <script src="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.js"></script> <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> <link rel="shortcut icon" type="image/jpg" href="anneli/assets/img/favicon.png"/> <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script> <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> <link type="text/css" rel="stylesheet" href="/anneli/themer_mdl.php?font=Montserrat&theme=blue_deep_orange&skin=materia"> <link type="text/css" rel="stylesheet" href="/anneli/themer2.php?font=Montserrat&theme=blue_deep_orange&skin=materia"> <link type="text/css" rel="stylesheet" href="anneli/assets/css/all.min.css"> <link type="text/css" rel="stylesheet" href="anneli/assets/css/style.css"> <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.css" /> <script src="/firebaseConfig.js?1684940450"></script> <script src="anneli/util.js?1684940450"></script> <script src="anneli/mime.js"></script> <script src="anneli/colors.js?"></script> <script src="anneli/fonts.js?"></script> ')
print (args)
import sys, os
from deepface import DeepFace
from PIL import Image, ImageDraw, ImageFont
import urllib.request
if args is not None:
    image = args ["i"][0]
    folder = args ["f"][0]
form = cgi.FieldStorage()
if form.getfirst ("url") is not None:
    image = form.getfirst ("url")

if form.getfirst ("f") is not None:
    folder = form.getfirst ("f")
if image.startswith ("http"):
    urllib.request.urlretrieve(image, "/var/www/viola/tmp/" + os.path.basename (image))
    image = "tmp/" + os.path.basename (image)
       
print (image, folder)
def drawFaces (img, x, y, w, h):
    with Image.open(img) as im:
        draw = ImageDraw.Draw(im)
        draw.rectangle ([x, y, w, h], "red", "red", 5)
        im.save(sys.argv [1] + "-detect", "JPG")
#faces = DeepFace.extract_faces (sys.argv [1])
#print (faces)
d = DeepFace.find (image, folder)
all_faces = DeepFace.extract_faces (image)

im = Image.open(image)
draw = ImageDraw.Draw(im)

for _face in all_faces:
    draw.rectangle ([_face ["facial_area"]["x"], _face ["facial_area"]["y"], _face ["facial_area"]["x"] + _face ["facial_area"]["w"], _face ["facial_area"]["y"] + _face ["facial_area"]["h"]], None, "red", 3)

fnt = ImageFont.truetype("/usr/share/fonts/julietaula-montserrat-fonts/Montserrat-Regular.otf", 12)

for face in d:
    try:
        color = "red"
        guess = face.get ("VGG-Face_cosine") 
        probability = 0
        for fa in guess:
            if fa > 0.3:
                continue
            # if probability == 0:
            probability = fa

        if probability == 0:
            color = "red"
        elif (probability < .3):
            color = "green"
            
        x = face.get ("source_x")
        y = face.get ("source_y")
        w = face.get ("source_w")
        h = face.get ("source_h")
        name = face.get ("identity")
        try:
            if probability != 0:
                for n in name:
                    draw.rectangle ([x, y - 20, x + w, y], "white", color, 1)
                    draw.text((x + 5, y - 17), os.path.basename (n).split (".")[0].title (), font=fnt, fill=color)
                    draw.rectangle ([x, y + h, x + w, y + h + 20], "white", color, 1)
                    draw.text((x + (w / 3), y + h + 3), str (100 - (int (round ((probability) * 100, 2)))) + " %", font=fnt, fill=color)
                
            print ("drawing face...")
            draw.rectangle ([x, y, x + w, y + h], None, color, 3)
        except TypeError:
            print ("error")
    except SystemError:
        print ("error")
    
    print (face)
    

print (f'total faces found: {len(face)}')
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
