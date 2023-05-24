import sys, os
from deepface import DeepFace
from PIL import Image, ImageDraw, ImageFont

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
fnt = ImageFont.truetype("/usr/share/fonts/julietaula-montserrat-fonts/Montserrat-Regular.otf", 12)

for face in d:
    try:
        color = "red"
        guess = face.get ("VGG-Face_cosine") 
        probability = 0
        for fa in guess:
            if fa > 0.3:
                continue
            if probability == 0:
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
            for n in name:
                draw.rectangle ([x, y - 20, x + w, y], "white", color, 1)
                draw.text((x + 5, y - 17), os.path.basename (n).split (".")[0].title (), font=fnt, fill=color)
                draw.rectangle ([x, y + h, x + w, y + h + 20], "white", color, 1)
                draw.text((x + (w / 3), y + h + 3), str (int (round ((probability) * 100, 2))) + " %", font=fnt, fill=color)
                
            print ("drawing face...")
            draw.rectangle ([x, y, x + w, y + h], None, color, 3)
        except TypeError:
            print ("error")
    except SystemError:
        print ("error")
    
    print (face)
    

print (f'total faces found: {len(face)}')
im.save(sys.argv [1] + "-detect.jpg", "JPEG")
