import sys
from deepface import DeepFace
from PIL import Image, ImageDraw

def drawFaces (img, x, y, w, h):
    with Image.open(img) as im:
        draw = ImageDraw.Draw(im)
        draw.rectangle ([x, y, w, h], "red", "red", 5)
        im.save(sys.argv [1] + "-detect", "JPG")
#faces = DeepFace.extract_faces (sys.argv [1])
#print (faces)
d = DeepFace.find (sys.argv [1], sys.argv [2])
im = Image.open(sys.argv [1])
draw = ImageDraw.Draw(im)

for face in d:
    try:
        guess = face.get ("VGG-Face_cosine") 
        print (guess [0])
        if (guess [0] > 0.3):
            continue
        x = face.get ("source_x")
        y = face.get ("source_y")
        w = face.get ("source_w")
        h = face.get ("source_h")
        draw.rectangle ([x, y, x + w, y + h], None, "red", 3)
    except SystemError:
        print ("error")
    
    print (face)
    

print (f'total faces found: {len(face)}')
im.save(sys.argv [1] + "-detect.jpg", "JPEG")
