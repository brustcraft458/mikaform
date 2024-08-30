// Global
var globalCounter = 0

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
        if (selected === 'text') {
            this.elmInput.type = "text"
            this.elmInput.value = "Hello Word"
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
        if (this.elmInput.type == 'text') {
            sanitized = input.replace(/[^a-zA-Z0-9 ,;.@]/g, '')
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
        this.elmMain.innerHTML = ''
        this.elmMain.remove()
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
        
        if (this.option.generate) {
            this.generate()
        } else if (this.option.scanner) (
            this.scanner()
        )
    }

    generate() {
        const text = JSON.stringify(this.data);

        // Clear
        this.elmMain.innerHTML = "";

        // Generate new QR code
        new QRCode(this.elmMain, {
            text: text,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff"
        });
    }

    scanner() {
        
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

const queryQrScan = document.querySelector("#qrscan")
if (queryQrScan) {
    new ElementQRCode(queryQrCode, {scanner: true})
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