$.ajax("../../api/getStock", {
  method: "GET",
  beforeSend: function () {
    document.getElementById("stockRobux").innerHTML = "...";
    document.getElementById("totalSold").innerHTML = "...";
    document.getElementById("totalOrder").innerHTML = "...";
    document.getElementById("lastOrder").innerHTML = "...";

  },
  success: (data) => {
    const obj = $.parseJSON(data);
    document.getElementById("stockRobux").innerHTML =
      obj["stock"].toLocaleString();
    document.getElementById("totalSold").innerHTML =
      obj["totalSold"].toLocaleString();
    document.getElementById("totalOrder").innerHTML =
      obj["totalOrder"].toLocaleString();
    var rate = obj["rate"];
    var hJual = 143 * rate;
    document.getElementById("hJual").innerHTML = "Rp " + hJual.toLocaleString();
    document.getElementById("lastOrder").innerHTML ="R$ " + obj["lastOrder"].toLocaleString();
    document.getElementById("stock").value = obj["stock"];
    document.getElementById("rate").value = obj["rate"];
  },
});

function number_format(number, decimals, dec_point, thousands_point) {
  if (number == null || !isFinite(number)) {
    throw new TypeError("number is not valid");
  }

  if (!decimals) {
    var len = number.toString().split(".").length;
    decimals = len > 1 ? len : 0;
  }

  if (!dec_point) {
    dec_point = ".";
  }

  if (!thousands_point) {
    thousands_point = ",";
  }

  number = parseFloat(number).toFixed(decimals);

  number = number.replace(".", dec_point);

  var splitNum = number.split(dec_point);
  splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
  number = splitNum.join(dec_point);

  return number;
}
function lanjut() {
  var robux = document.getElementById("inputRobux").value;
  var stok = document.getElementById("stock").value;
  if (!robux) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Jumlah robux wajib diisi",
    });
  } else if (robux < 25) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Minimal Purchase 25 Robux",
    });
  } else {
    window.location.href =
      "https://" +
      window.location.hostname +
      "/public/pengguna?robux=" +
      parseFloat(robux * 1.43).toFixed(0) +
      "&setharga=" +
      parseFloat(robux * 1.43).toFixed(0);
  }
}



function truncate(str, n) {
  return str.length > n ? str.substr(0, n - 1) + "..." : str;
}

function hitungRobux() {
  $.ajax("../../api/getStock", {
    method: "GET",
    success: (data) => {
      const obj = $.parseJSON(data);
      var rate = obj["rate"];
      var robux = document.getElementById("inputRobux").value;
      var Hrobux = robux * 1.43;
      var getPrice = Hrobux * rate;
      document.getElementById("totalharga").value = number_format(getPrice);
    },
  }
)}


let selectedUniverseId = null
function universeListItemClick(el){
  selectedUniverseId = el.getAttribute("data-value");
  document.querySelectorAll("#universe-list-item").forEach((el)=>{
    const universeId = el.getAttribute("data-value");
    if (universeId !== selectedUniverseId) {
      el.classList.remove("active");
      el.removeAttribute('data-universe-selected')
    } else {
      el.setAttribute('data-universe-selected', 1)
      el.classList.add("active");
    }
  });
}
 


function cariPengguna() {
  var namaPengguna = document.getElementById("namapengguna").value;
  if (namaPengguna.length >= 3) {
    const apiPengguna =
      "../api/searchUser?keyword=" + encodeURIComponent(namaPengguna);
    $.ajax(apiPengguna, {
      method: "GET",
      beforeSend: function () {
        document.getElementById("tampil-akun").innerHTML = "";
        // document.getElementById("text-error").innerHTML = "";
        // document.getElementById("loading").style.display = "block";
        // document.getElementById("text-undefined").style.display = "none";
      },
      success: (data) => {
        const obj = $.parseJSON(data);
        // if (obj["data"].length == 0) {
        //   document.getElementById("text-error").innerHTML =
        //     "Tidak ada kecocokan yang tersedia untuk " + obj["Keyword"];
        //   document.getElementById("text-undefined").style.display = "block";
        // } else {
          for (var i = 0; i < obj["data"].length; i++) {
            if (obj["data"][i]["Thumbnails"]) {
              document.getElementById("tampil-akun").innerHTML += `
              <Center>
                          <div onclick="Akun(${obj["data"][i]["UserId"]})">
                            <span class='mb-6 lead text-white'>
                              <strong>${truncate(
                                obj["data"][i]["DisplayName"] +
                                  " (@" +
                                  obj["data"][i]["name"],
                                36
                              )})</strong>
                              </span><br>
                              <a href='#'><img src='${obj["data"][i]["Thumbnails"]}'></a>
                              </div>`;
            }
          }
        // }
        // document.getElementById("loading").style.display = "none";
      },
    });
  }
}
