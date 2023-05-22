list = {}

for (table of document.getElementsByTagName ("table")) {
    name = null
    email = null
    phone = null
    reg = null 
    rollno  = null
    subjects = null
    father = null
    mother = null
    name = null
    crollno = -1
    //idno = null
    for (tr of table.getElementsByTagName ("tr")) {
        c = tr.getElementsByTagName ("center")
        if (c!= null && c.length > 0) {
            //console.log (c[0].innerText.startsWith ("20"))
            if (c[0].innerText.startsWith ("2")) {
                idno = c [0].innerText
                console.log (idno)
            }
        }
        imgs = tr.getElementsByTagName ("img")
        if (imgs.length > 1) {
            photo = imgs [0].src 
            sign = imgs [1].src
        }

        if (tr.getAttribute ("height") == "25") {
            if (tr.innerText.startsWith ("Father")) {
                father = tr.innerText.split ("\t")[1]
                stream = tr.innerText.split ("\t")[3]
            }
            if (tr.innerText.startsWith ("Mother")) {
                mother = tr.innerText.split ("\t")[1]
                dob = tr.innerText.split ("\t")[3]
            }
            
        }

        for (td of tr.getElementsByTagName ("td")) {            
            if (td.innerText.startsWith ("Class RollNo")) 
                crollno = td.innerText.split ("Class RollNo:")[1]
            if (td.innerText.startsWith ("Medium")) 
                medium = td.innerText.split ("Medium: ")[1]
            if (td.innerText.startsWith ("Religion")) 
                religion = td.innerText.split ("Religion: ")[1]
            if (td.innerText.startsWith ("Category")) 
                category = td.innerText.split ("Category: ")[1]
            if (td.innerText.startsWith ("Male")) 
                gender = "Male"
            if (td.innerText.startsWith ("Female")) 
                gender = "Female"
            if (td.getAttribute("style") == "font-size:14px;font-weight:bold" && td.innerText[0] != "2" && td.innerText [0] != "G")
                name = td.innerText
            if (td.width == "44%") reg = td.innerText
            if (td.width == "50%" && td.getAttribute ("colspan") == 2) {
                if (td.innerText.search ("@") != -1)
                    email = td.innerText.split("Email:")[1]
                else
                    phone = td.innerText.split ("Phone No.")[1]
            }
            if (td.width == "20%") rollno = td.innerText
            if (subjects == null){
                if (td.style.fontSize == "12px" && td.style.fontWeight == "bold" ) {
                    if (td.innerText .startsWith ("Subjects")) {
                        subjects = td.innerText
                        //console.log (subjects)
                        list [reg] = {
                            name: name,
                            rollno: rollno,
                            crollno: crollno,
                            subjects: subjects,
                            email: email,
                            phone: phone,
                            photo: photo,
                            sign: sign,
                            father: father,
                            mother: mother,
                            stream: stream,
                            dob: dob,
                            medium: medium,
                            religion: religion,
                            category: category,
                            gender: gender,
                            id: idno
                        }
                        continue
                    }
                }
            }
        }
    }
}

list
