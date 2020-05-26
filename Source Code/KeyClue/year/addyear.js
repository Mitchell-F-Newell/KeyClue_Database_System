// function to add more keymasters name fields to the addyear form
function addFields() {
            var number = document.getElementById("number_km").value;
            var container = document.getElementById("km_fields");
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            for (i = 0; i < number; i++){
                var divider = document.createElement("div");
                divider.classList.add("inputGroup");
                var label = document.createElement("label");
                label.innerHTML = "Keymaster " + (i + 1);
                var input = document.createElement("input");
                input.type = "text";
                input.classList.add("form-control");
                input.name = "km" + (i);
                divider.appendChild(label);
                divider.appendChild(input);
                container.appendChild(divider);
            }
        }
