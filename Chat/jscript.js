(() => {
  "use strict";

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        } else {
          if (event.target.classList.contains("isRegester")) {
            regester();
          }

          if (event.target.classList.contains("isLogin")) {
            login();
          }

          event.preventDefault();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
})();

if (document.querySelector(".show-pass")) {
  document.querySelector(".show-pass").addEventListener("click", (e) => {
    let inpt = e.target.parentElement.parentElement.querySelector("input");

    // <i class="fa-regular fa-eye"></i>

    if (inpt.type == "text") {
      inpt.type = "password";
      e.target.classList.remove("fa-solid", "fa-eye-slash");
      e.target.classList.add("fa-regular", "fa-eye");
    } else {
      inpt.type = "text";
      e.target.classList.add("fa-solid", "fa-eye-slash");
      e.target.classList.remove("fa-regular", "fa-eye");
    }
  });
}
function regester() {
  let fname = document.querySelector(".fname-regester").value;
  let lname = document.querySelector(".lname-regester").value;
  let email = document.querySelector(".email-regester").value;
  let pass = document.querySelector(".pass-regester").value;
  let img = document.querySelector(".img-regester");

  nameComplt = fname + " " + lname;

  const formData = new FormData();

  formData.append("img", img.files[0]);

  // console.log(formData);
  // console.log("----------------------");

  formData.append("email", email);
  formData.append("pass", pass);
  formData.append("nameComplt", nameComplt);
  formData.append("form", "regester");

  fetch("connect.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // Vérifier si la réponse est OK (code 200)
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json(); // Convertir la réponse en JSON
    })
    .then((data) => {
      let divAlert = document.querySelector(".msg-fetch div");
      divAlert.parentElement.classList.remove("d-none");
      divAlert.innerHTML = data.message;
      if (data.ok) {
        rederect("login.php");
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la requête fetch :", error);
    });
  /*  */
}

function login() {
  let email = document.querySelector(".email-login").value;
  let pass = document.querySelector(".password-login").value;

  var formData = new FormData();

  formData.append("email", email);
  formData.append("pass", pass);
  formData.append("form", "login");

  fetch("connect.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // Vérifier si la réponse est OK (code 200)
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json(); // Convertir la réponse en JSON
    })
    .then((data) => {
      // console.log("Réponse du serveur :", data);
      let divAlert = document.querySelector(".msg-fetch div");
      divAlert.parentElement.classList.remove("d-none");
      divAlert.innerHTML = data.message;
      if (data.ok) {
        rederect("chat_page.php");
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la requête fetch :", error);
    });
}

function rederect(locatnio) {
  window.location.href = locatnio;
}

function videChamp($elent) {
  let inpt_vider = document.querySelector($elent);
  if (inpt_vider) {
    inpt_vider.value = "";
  }
  return false;
}

/***************************************************** */
/***************************************************** */
/***** SEND MESSAGE ********************************** */

let body_msg = document.querySelector(".body-msg");

let can_scroll = true;

body_msg.onmouseenter = () => { 
  can_scroll = false;
};
body_msg.onmouseleave = () => { 
  can_scroll = true;
};

let form = document.querySelector("form.chat-form");

if (form) {
  let bntSend = form.querySelector("button.btn-send-msg"),
    inpt = form.querySelector("input.inpt-msg");

  setInterval(() => {
    syncData(0);
  }, 800);

  form.onsubmit = (e) => {
    e.preventDefault();
  };

  // bntSend.addEventListener("click", () => {
  bntSend.onclick = () => {
    if (inpt.value == "") {
      alert("No Message !!!");
      return;
    }
    syncData(1);
    inpt.value = "";
  }; // adevent
} // if Form

function syncData(isClick = 0) {
  const formData = new FormData(form);
  let body_msg = document.querySelector(".body-msg"),
    line = document.querySelector(".line");

  formData.append("isClick", isClick);

  fetch("sendMsg.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json(); // Convertir la réponse en JSON
    })
    .then((responce) => {
      if (!responce.ok) {
        console.log("then 2  ", responce.etatMsg);
      } else {
        body_msg.innerHTML = responce.allMsg;

        if (responce.line) {
          line.innerHTML = "Online";
          line.classList.add("text-success");
          line.classList.remove("text-danger");
        } else {
          line.innerHTML = "Ofline";
          line.classList.remove("text-success");
          line.classList.add("text-danger");
        }

        // body_msg.innerHTML = responce;
      }
    })
    .catch((error) => {
      console.log("Erro de catch ", error);
      body_msg.innerHTML = error;
    });

  scrollBottom();
}

function scrollBottom() {
  let body_msg = document.querySelector(".body-msg");
  if (can_scroll) {
    body_msg.scrollTop = body_msg.scrollHeight;
  }
}
