function auto_preencher_todos() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    const elementos = form.querySelectorAll("input, select, textarea");
    elementos.forEach((el) => {
      if (el instanceof HTMLInputElement) {
        switch (el.type) {
          case "email":
            el.value = `teste${Math.floor(Math.random() * 1e3)}@email.com`;
            break;
          case "number":
            el.value = String(Math.floor(Math.random() * 9999));
            break;
          case "date":
            el.value = (/* @__PURE__ */ new Date()).toISOString().split("T")[0];
            break;
          case "password":
            el.value = "123456";
            break;
          case "checkbox":
            el.checked = Math.random() > 0.5;
            break;
          case "radio":
            if (!el.name) return;
            const radios = document.querySelectorAll(
              `input[type="radio"][name="${el.name}"]`
            );
            if (radios.length) {
              radios[Math.floor(Math.random() * radios.length)].checked = true;
            }
            break;
          case "hidden":
            break;
          default:
            el.value = Math.random().toString(36).substring(2, 10);
        }
      }
      if (el instanceof HTMLSelectElement) {
        if (el.options.length > 0) {
          el.selectedIndex = 0;
        }
      }
      if (el instanceof HTMLTextAreaElement) {
        el.value = Math.random().toString(36).substring(2, 20);
      }
    });
  });
  console.log("Formulários preenchidos.");
}
function auto_limpar_todos() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    const elementos = form.querySelectorAll("input, select, textarea");
    elementos.forEach((el) => {
      if (el instanceof HTMLInputElement) {
        if (el.type === "checkbox" || el.type === "radio") {
          el.checked = false;
        } else {
          el.value = "";
        }
      }
      if (el instanceof HTMLSelectElement) {
        if (el.options.length > 0) {
          el.selectedIndex = 0;
        }
      }
      if (el instanceof HTMLTextAreaElement) {
        el.value = "";
      }
    });
  });
  console.log("Formulários limpos.");
}
document.addEventListener("click", (event) => {
  const target = event.target;
  if (target.matches("[data-seeder]")) {
    auto_preencher_todos();
  }
  if (target.matches("[data-limpar]")) {
    auto_limpar_todos();
  }
});
