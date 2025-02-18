function validateForm(formId) {
    let isValid = true;
    let form = document.getElementById(formId);
    let inputs = form.querySelectorAll("input, select");

    form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    form.querySelectorAll(".error-message").forEach(el => el.remove());

    inputs.forEach(input => {
        let value = input.value.trim();
        let errorMessage = "";

        if (input.hasAttribute("required") && value === "") {
            errorMessage = `${input.previousElementSibling.innerText} is required.`;
        }

        if (input.type === "email" && value !== "" && !/\S+@\S+\.\S+/.test(value)) {
            errorMessage = "Please enter a valid email address.";
        }

        if (input.id.includes("telephone") && value !== "" && !/^\d{10,15}$/.test(value)) {
            errorMessage = "Telephone must be 10-15 digits.";
        }

        if (input.id.includes("hotel-code") && value !== "" && !/^[A-Z0-9]+$/.test(value)) {
            errorMessage = "Hotel Code must contain only uppercase letters and numbers.";
        }

        if (errorMessage !== "") {
            isValid = false;
            input.classList.add("is-invalid");

            let errorDiv = document.createElement("div");
            errorDiv.className = "error-message";
            errorDiv.innerText = errorMessage;
            input.parentNode.appendChild(errorDiv);
        }
    });

    return isValid;
}
