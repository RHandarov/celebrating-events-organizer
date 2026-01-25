document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#login-form form"); 

    const fields = [
        {
            id: "username",
            validate: (val) => val.trim().length > 0,
            msg: "Моля, въведете потребителско име."
        },
        {
            id: "password",
            validate: (val) => val.length > 0,
            msg: "Моля, въведете парола."
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