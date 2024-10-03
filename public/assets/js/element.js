// Global
var globalCounter = 0
var globalCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

// Edit Text
class ElementEditText {
    constructor(element) {
        this.elmMain = element

        this.init()
    }

    init() {
        this.elmMain.setAttribute("contenteditable", "true")
        this.elmMain.setAttribute("spellcheck", "false")

        this.elmMain.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        })
    }
}

// Form
class ElementForm {
    constructor(element, option = {isUserInput: false}) {
        this.elmRoot = element
        this.option = option
        this.fname = element.getAttribute('id')
        this.elmChildItem = element.querySelector(`#${this.fname}-section`)
        this.btnSubmit = element.querySelector(`#${this.fname}-button-submit`)

        if (!this.option.isUserInput) {
            this.btnAddItem = element.querySelector(`#${this.fname}-button-add`)
        }

        this.memItemList = []

        this.init()
    }

    init() {
        // Add Item
        if (!this.option.isUserInput) {
            this.btnAddItem.addEventListener("click", () => {
                this.onButtonAddItem()
            })
        }

        // Item Children
        for (const child of this.elmChildItem.children) {
            this.memItemList.push(new ElementFormItem(child, this.option))
        }

        // Submit Form
        this.btnSubmit.addEventListener('click', () => {
            this.onButtonSubmit()
        })
    }

    onButtonAddItem() {
        const newElm = document.createElement("div")
        const inum = generateIncrementNumber()
        const uname = `${this.fname}-${inum}`

        newElm.classList.add('form-group')
        newElm.classList.add('my-2')
        newElm.classList.add('d-flex')
        newElm.classList.add('flex-column')
        newElm.setAttribute('id', uname)

        newElm.innerHTML = `
            <div class="form-group my-2 d-flex flex-column" id="${uname}">
                <div class="align-self-center p-2 shadow-sm rounded mt-4 d-none" id="${uname}-image">
                </div>
                <div class="d-flex flex-row gap-1">
                    <label class="col-form-label edit-text rounded" id="${uname}-label">Text 1:</label>
                    <i class="bi bi-pencil-fill mt-1 fs-13px"></i>
                </div>
                <div class="d-flex flex-row gap-2">
                    <input type="text" class="form-control" id="${uname}-input" value="Hello World" disabled>
                    <select class="form-control w-50" id="${uname}-type">
                        <option value="text">Text</option>
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="number">Number</option>
                        <option value="phone">Phone</option>
                        <option value="file">File</option>
                        <option value="payment">Payment</option>
                    </select>
                    <button type="button" class="btn btn-outline-danger" id="${uname}-delete"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `

        // Assign
        const editText = newElm.querySelector(".edit-text")
        new ElementEditText(editText)
        this.memItemList.push(new ElementFormItem(newElm, this.option))

        // Animation
        newElm.classList.add('swipe-in')
        newElm.addEventListener('animationend', () => {
            newElm.classList.remove('swipe-in')
        }, { once: true })

        this.elmChildItem.appendChild(newElm)
    }

    onButtonSubmit() {
        const elmTitle = this.elmRoot.querySelector(`#${this.fname}-title`)
        const elmForm = this.elmRoot.querySelector('form')

        let jsonData = {
            title: elmTitle.innerText,
            section_list: []
        }

        // Get Data Children
        this.memItemList.forEach(item => {
            jsonData.section_list.push(item.toJson())
        })

        // Process
        const jsonInput = document.createElement("input")
        jsonInput.setAttribute('type', 'text')
        jsonInput.setAttribute('name', 'json-data')
        jsonInput.setAttribute('value', JSON.stringify(jsonData))
        jsonInput.classList.add('d-none')

        // Submit
        elmForm.appendChild(jsonInput)
        elmForm.submit()
    }
}

class ElementFormItem {
    constructor(element, option) {
        this.elmMain = element
        this.option = option
        this.uname = element.getAttribute('id')
        this.elmLabel = element.querySelector(`#${this.uname}-label`)
        this.elmInput = element.querySelector(`#${this.uname}-input`)

        if (!this.option.isUserInput) {
            this.elmImage = element.querySelector(`#${this.uname}-image`)
            this.elmType = element.querySelector(`#${this.uname}-type`)
            this.btnDelete = element.querySelector(`#${this.uname}-delete`)
        }

        this.init()
    }

    init() {
        // Action
        if (!this.option.isUserInput) {
            this.elmType.addEventListener('change', (event) => {
                this.onInputChange(event.target.value)
            })
    
            this.initImage()
    
            this.btnDelete.addEventListener('click', () => {
                this.onButtonDelete()
            })
        } else {
            this.elmInput.addEventListener('input', (event) => {
                this.onInputSanitize(event.target.value)
            });
        }
    }

    initImage() {
        const elmInput = this.elmImage.querySelector('input')
        const elmImg = this.elmImage.querySelector('img')

        if (elmImg && elmInput) {
            elmImg.addEventListener('click', () => {
                elmInput.click();
            })

            elmInput.addEventListener('change', (event) => {
                this.onImageChange(event.target.files[0], elmImg)
            })
        }
    }

    onInputChange(selected) {
        // Option
        if (selected === 'email') {
            this.elmInput.type = "email"
            this.elmInput.value = "johndoe@testmail.com"
        } else if (selected === 'text' || selected === 'name') {
            this.elmInput.type = "text"
            if (selected === 'name') {
                this.elmInput.value = "John Doe Lorem"
            } else {
                this.elmInput.value = "Hello Word"
            }
        } else if (selected === 'number' || selected === 'phone') {
            this.elmInput.type = "number"
            if (selected === 'phone') {
                this.elmInput.value = '081234567890'
            } else {
                this.elmInput.value = '1234456789'
            }
        } else if (selected === 'file' || selected == 'payment') {
            this.elmInput.type = "file"
        }

        if (selected == 'payment') {
            this.elmImage.innerHTML = `
                <img class="image" src="../assets/img/qristes.png" alt="">
                <input type="file" class="image-input d-none" name="${this.uname}-image" accept="image/png, image/jpeg">
            `
            this.elmImage.classList.remove('d-none')
            this.initImage()

        } else {
            this.elmImage.classList.add('d-none')
            this.elmImage.innerHTML = ''
        }
    }

    onInputSanitize(input) {
        let sanitized = ''
        if (this.elmInput.type == 'email') {
            sanitized = input.replace(/[^a-zA-Z0-9 .@]/g, '')
        } else if (this.elmInput.type == 'text') {
            sanitized = input.replace(/[^a-zA-Z0-9 ,;.]/g, '')
        } else if (this.elmInput.type == 'number') {
            sanitized = input.replace(/[^0-9]/g, '')
        } else {
            return
        }
        
        this.elmInput.value = sanitized
    }

    onImageChange(file, elmImg) {
        if (file) {
            const reader = new FileReader()
            reader.onload = function(e) {
                elmImg.src = e.target.result
            }
            reader.readAsDataURL(file)
        }
    }

    onButtonDelete() {
        this.elmMain.classList.add('swipe-out')
        this.elmMain.addEventListener('animationend', () => {
            // Delete
            this.elmMain.remove()
        }, { once: true })
    }

    toJson() {
        let newData

        if (!this.option.isUserInput) {
            newData = {
                label: this.elmLabel.innerText,
                type: this.elmType.value
            }
    
            const inpImage = this.elmImage.querySelector("input")
            if (inpImage) {
                newData['image'] = inpImage.name
            }

        } else {
            const id = parseInt(this.uname.split('-').pop())

            newData = {
                id: id,
                label: this.elmLabel.innerText,
                type: this.elmInput.type
            }

            if (newData.type == 'file') {
                newData['value'] = this.elmInput.name
            } else {
                newData['value'] = this.elmInput.value
            }
            
        }

        return newData
    }
}

class ElementQRCode {
    constructor(element, option = {generate: false, scanner: false}, data = {}) {
        this.elmMain = element
        this.option = option
        this.data = data
        this.lastUUID = ''
        
        if (this.option.generate) {
            this.initGenerate()
        } else if (this.option.scanner) {
            this.initScanner()
        }
    }

    initGenerate() {
        // Clear
        this.elmMain.innerHTML = "";

        this.onGenerate(this.data)
    }

    onGenerate(json) {
        const text = JSON.stringify(json)

        // Generate new QR code
        new QRCode(this.elmMain, {
            text: text,
            width: 220,
            height: 220,
            colorDark: "#000000",
            colorLight: "#ffffff"
        })
    }

    initScanner() {
        // Init Canvas
        const canvas = document.createElement('canvas')
        const context = canvas.getContext('2d', { willReadFrequently: true })

        // Init Camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                this.elmMain.srcObject = stream
            })
            .catch((error) => {
                console.error('Error accessing the camera:', error)
            })
        
        // Scan
        const scanQRCode = () => {
            if (this.elmMain.readyState === this.elmMain.HAVE_ENOUGH_DATA) {
                canvas.width = this.elmMain.videoWidth
                canvas.height = this.elmMain.videoHeight
                context.drawImage(this.elmMain, 0, 0, canvas.width, canvas.height)

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height)
                const code = jsQR(imageData.data, imageData.width, imageData.height)
                
                try {
                    let djson = JSON.parse(code.data)
                    this.onScanner(djson)
                    // stream.getTracks().forEach(track => track.stop())
                } catch (err) {
                    // pass   
                }
            }

            requestAnimationFrame(scanQRCode)
        }
        
        // Assign Video
        this.elmMain.addEventListener('play', () => {
            scanQRCode()
        })
    }

    async onScanner(json) {
        if (!json.hasOwnProperty("type") || !json.hasOwnProperty("uuid")) {return}
        if (json.type != 'presence') {return}
        if (json.uuid == this.lastUUID) {return}

        // Init
        const templateUUID = this.data.uuid

        // Set
        this.lastUUID = json.uuid
        json['presence_input'] = ''

        // Post
        try {
            let response = await fetch(`/api/presence/scan/${templateUUID}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': globalCSRF
                },
                body: JSON.stringify(json)
            })

            if (!response.ok) {
                let errorData = await response.json()
                throw {status: response.status, message: errorData.message, data: errorData.data}
            }
    
            let data = await response.json()

            // Message
            Swal.fire({
                title: "Scan Success!",
                text: "Terimakasih yang sudah hadir",
                icon: "success",
                timer: 4000
            })
        } catch (err) {
            if (err.status && err.status == 409 && err.message == "presence_input_exists_failed") {
                Swal.fire({
                    title: "Scan Exists!",
                    text: "Anda Sudah Presensi Hari Ini",
                    icon: "warning",
                    timer: 4000
                })
                return
            }

            this.lastUUID = ''
            return
        }
    }
}

// Assign
const queryEditText = document.querySelectorAll(".edit-text");
if (queryEditText) {
    queryEditText.forEach(element => {
        new ElementEditText(element);
    });
}

const queryFormTemplate = document.querySelectorAll(".form-data-template");
if (queryFormTemplate) {
    queryFormTemplate.forEach(element => {
        new ElementForm(element);
    });
}

const queryFormShare = document.querySelectorAll(".form-data-share");
if (queryFormShare) {
    queryFormShare.forEach(element => {
        new ElementForm(element, {isUserInput: true});
    });
}

const queryDataTable = document.querySelectorAll(".datatable");
if (queryDataTable) {
    queryDataTable.forEach(table => {
        const datatable = new simpleDatatables.DataTable(table, {
            perPage: 7,
            perPageSelect: false
        })

        datatable.on('datatable.init', function() {
            table.style.display = 'table'
        });
    })
}


function generateIncrementNumber() {
    globalCounter += 1
    return globalCounter
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Text telah disalin');
    }).catch(function(err) {
        console.error('Error copying text: ', err);
    });
}

function redirectToTab(url) {
    window.open(url, '_blank');
}

function sendFormAction(target, key, value) {
    const form = document.getElementById(target)
    const elmm = document.createElement('input')
    elmm.type = 'hidden';
    elmm.name = key;
    elmm.value = value;

    // Append element
    form.appendChild(elmm);
    
    // Submit
    form.submit()
}