$.ajax("../../api/getStock", {
  method: "GET",
  beforeSend: function () {
    document.getElementById("stockRobux").innerHTML = "...";
    document.getElementById("totalSold").innerHTML = "...";
    document.getElementById("totalOrder").innerHTML = "...";
    document.getElementById("hJual").innerHTML = "...";
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
    document.getElementById("hJual").innerHTML = hJual.toLocaleString();
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

function hitungRobux() {
  var robux = document.getElementById("inputRobux").value;
  var rate = document.getElementById("rate").value;
  var Hrobux = robux * 1.43;
  var getPrice = Hrobux * rate;
  document.getElementById("totalharga").value =
    "IDR " + number_format(getPrice);
}
