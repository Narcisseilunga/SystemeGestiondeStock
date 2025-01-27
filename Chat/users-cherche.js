console.log("oui user chercher");

let inpt_serche = document.querySelector(".box-chat input"),
  btun_serche = document.querySelector(".box-chat   button.btn-valid"),
  div_liste = document.querySelector(".box-chat .list-users-serche"),
  btn_clear = document.getElementById("clear_input");

//  console.log(btun_serche) ;
//   console.log(inpt_serche) ;
//   console.log(btn_clear)   ;
if (btun_serche != null) {
  btun_serche.onclick = () => {
    txt = inpt_serche.value.trim();
    //   console.log("first");

    div_liste.classList.remove("d-none");

    var formData = new FormData();

    formData.append("nameUser", txt);

    fetch("users-cherche.php", {
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
      .then((responce) => {
        div_liste.innerHTML = responce.message;
        if (!responce.ok) {
          console.log("Errors ", responce.message);
        } else {
          if (responce.etat) {
            document.querySelector(".etat").innerHTML = "";
          }
        } 
      })
      .catch((error) => {
        console.log("Erro de Ctqhc " + error);
        // div_liste.innerHTML = error;
      }); 
  };

  btn_clear.onclick = () => {
    inpt_serche.value = "";
    div_liste.classList.add("d-none");
    div_liste.innerHTML = "";
  };
}
