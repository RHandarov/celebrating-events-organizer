document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#registration-form form");

    const isValidEmail = (email) => {
        return String(email)
            .toLowerCase()
            .match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
    };

    const fields = [
        {
            id: "username",
            validate: (val) => val.trim().length >= 3,
            msg: "Потребителското име трябва да е поне 3 символа."
        },
        {
            id: "email",
            validate: (val) => isValidEmail(val),
            msg: "Моля, въведете валиден имейл адрес."
        },
        {
            id: "full-name",
            validate: (val) => val.trim().length >= 2, 
            msg: "Моля, въведете вашето пълно име."
        },
        {
            id: "password",
            validate: (val) => val.length >= 6,
            msg: "Паролата трябва да е поне 6 символа."
        }
    ];

    fields.forEach(field => {
        const inputElement = document.getElementById(field.id);

        if (inputElement) {
            inputElement.addEventListener("blur", function () {
                validateField(inputElement, field);
            });

            inputElement.addEventListener("input", function () {
                const isValid = field.validate(inputElement.value);
                if (isValid) {
                    clearError(inputElement);
                }
            });
        }
    });

    form.addEventListener("submit", function (event) {
        let hasError = false;

        fields.forEach(field => {
            const inputElement = document.getElementById(field.id);
            const isValid = validateField(inputElement, field);
            
            if (!isValid) {
                hasError = true;
            }
        });

        if (hasError) {
            event.preventDefault();
        }
    });

    function validateField(input, fieldConfig) {
        const isValid = fieldConfig.validate(input.value);

        if (!isValid) {
            showError(input, fieldConfig.msg);
            return false;
        } else {
            clearError(input);
            return true;
        }
    }

    function showError(inputElement, message) {
        const parent = inputElement.parentNode; 
        const existingError = parent.querySelector(`.js-error-text[data-for="${inputElement.id}"]`);
        
        inputElement.style.borderColor = "#dc3545"; 
        inputElement.style.backgroundColor = "#fff8f8";

        const label = document.querySelector(`label[for='${inputElement.id}']`);
        if (label) {
            label.style.color = "#dc3545";
        }

        if (!existingError) {
            const errorSmall = document.createElement("small");
            errorSmall.className = "js-error-text";
            errorSmall.setAttribute("data-for", inputElement.id);
            
            errorSmall.style.setProperty("color", "#dc3545", "important");
            errorSmall.style.fontWeight = "bold";
            errorSmall.style.display = "block";
            errorSmall.style.marginTop = "5px";
            errorSmall.innerText = message;

            if (inputElement.nextSibling) {
                parent.insertBefore(errorSmall, inputElement.nextSibling);
            } else {
                parent.appendChild(errorSmall);
            }
        }
    }

    function clearError(inputElement) {
        inputElement.style.borderColor = ""; 
        inputElement.style.backgroundColor = "";

        const label = document.querySelector(`label[for='${inputElement.id}']`);
        if (label) {
            label.style.color = "";
        }

        const parent = inputElement.parentNode;
        const errorText = parent.querySelector(`.js-error-text[data-for="${inputElement.id}"]`);
        if (errorText) {
            errorText.remove();
        }
    }
});