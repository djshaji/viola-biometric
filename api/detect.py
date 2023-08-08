#!/usr/bin/python3.11
print("Content-Type: text/html")
print()
import cgi
args = []

import cgitb
cgitb.enable()
form = cgi.FieldStorage()

print (form)
# print ("<form enctype='multipart/form-data' action='/api/detect.py' method='post' class='section row m-4 p-4 shadow'>")
# print ("<input name='image' type='text'></form>")

image = form.getfirst ("image")

from deepface import DeepFace
from PIL import Image, ImageDraw, ImageFont

# print ("detecting faces ...")
all_faces = DeepFace.extract_faces (image)
# print (all_faces)
im = Image.open(image)
draw = ImageDraw.Draw(im)

for _face in all_faces:
  #  print (_face)
   draw.rectangle ([_face ["facial_area"]["x"], _face ["facial_area"]["y"], _face ["facial_area"]["x"] + _face ["facial_area"]["w"], _face ["facial_area"]["y"] + _face ["facial_area"]["h"]], None, "red", 3)

im.save(image + "-detect", "JPEG")
# print (f"<img src='{image}-detect'>")
print ("__CUT_HERE__")
print ("__CUT_HERE__")
print ('{"response": 200}')
print ("__CUT_HERE__")
