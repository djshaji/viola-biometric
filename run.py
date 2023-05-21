import sys
from deepface import DeepFace

#faces = DeepFace.extract_faces (sys.argv [1])
#print (faces)
d = DeepFace.find (sys.argv [1], "demo/faces")
print (d)
