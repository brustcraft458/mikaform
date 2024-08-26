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
    constructor(element) {
        this.elmRoot = element
        this.fname = element.getAttribute('id')
        this.elmChildItem = element.querySelector(`#${this.fname}-section`)
        this.btnAddItem = element.querySelector(`#${this.fname}-button-add`)
        this.btnSubmit = element.querySelector(`#${this.fname}-button-submit`)
        this.memItemList = []

        this.init()
    }

    init() {
        // Add Item
        this.btnAddItem.addEventListener("click", () => {
            this.onButtonAddItem()
        })

        // Item Children
        for (const child of this.elmChildItem.children) {
            this.memItemList.push(new ElementFormItem(child))
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
        this.memItemList.push(new ElementFormItem(newElm))

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
    constructor(element) {
        this.elmMain = element
        this.uname = element.getAttribute('id')
        this.elmLabel = element.querySelector(`#${this.uname}-label`)
        this.elmImage = element.querySelector(`#${this.uname}-image`)
        this.elmInput = element.querySelector(`#${this.uname}-input`)
        this.elmType = element.querySelector(`#${this.uname}-type`)
        this.btnDelete = element.querySelector(`#${this.uname}-delete`)

        this.init()
    }

    init() {
        // Action
        this.elmType.addEventListener('change', (event) => {
            this.onInputChange(event.target.value)
        })

        this.initImage()

        this.btnDelete.addEventListener('click', () => {
            this.onButtonDelete()
        })
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
        } else if (selected === 'number') {
            this.elmInput.type = "number"
            this.elmInput.value = '1234456789'
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
        let newData = {
            label: this.elmLabel.innerText,
            type: this.elmType.value
        }

        const inpImage = this.elmImage.querySelector("input")
        if (inpImage) {
            newData['image'] = inpImage.name
        }

        return newData
    }
}

// Assign
document.querySelectorAll(".edit-text").forEach(element => {
    new ElementEditText(element)
})
document.querySelectorAll(".form-data-template").forEach(element => {
    new ElementForm(element)
})

function generateIncrementNumber() {
    globalCounter += 1
    return globalCounter
}
