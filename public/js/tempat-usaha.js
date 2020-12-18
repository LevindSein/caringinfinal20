//Estimasi Tagihan
$('#los').on('input',function(e){
    var check = $(this).val();
    var words = $(this).val().split(",");
    if(check.slice(-1) == ','){
        var words = words.length - 1;
    }
    else{
        var words = words.length;
    }
    $('#myDiv3').on('input',function(e){
        var tarif = $(this).val();
        var estimasi = words * tarif;
        document.getElementById("estimasiKeamananIpk").innerHTML = "Rp. " + estimasi.toLocaleString();
    });

    $('#myDiv4').on('input',function(e){
        var tarif = $(this).val();
        var estimasi = words * tarif;
        document.getElementById("estimasiKebersihan").innerHTML = "Rp. " + estimasi.toLocaleString();
    });
});
$('#myDiv3').on('input',function(e){
    var tarif = $(this).val();
    $('#los').on('input',function(e){
        var check = $(this).val();
        var words = $(this).val().split(",");
        if(check.slice(-1) == ','){
            var words = words.length - 1;
        }
        else{
            var words = words.length;
        }
        var estimasi = words * tarif;
        document.getElementById("estimasiKeamananIpk").innerHTML = "Rp. " + estimasi.toLocaleString();
    });
});
$('#myDiv4').on('input',function(e){
    var tarif = $(this).val();
    $('#los').on('input',function(e){
        var check = $(this).val();
        var words = $(this).val().split(",");
        if(check.slice(-1) == ','){
            var words = words.length - 1;
        }
        else{
            var words = words.length;
        }
        var estimasi = words * tarif;
        document.getElementById("estimasiKebersihan").innerHTML = "Rp. " + estimasi.toLocaleString();
    });
});

//Search
$(document).ready(function () {
    $('.blok').select2({
        placeholder: '--- Pilih Blok ---',
        ajax: {
            url: "/cari/blok",
            dataType: 'json',
            delay: 250,
            processResults: function (blok) {
                return {
                results:  $.map(blok, function (bl) {
                    return {
                    text: bl.nama,
                    id: bl.nama
                    }
                })
                };
            },
            cache: true
        }
    });
});

$(document).ready(function () {
    $('.pemilik, .pengguna').select2({
        placeholder: '--- Cari Nasabah ---',
        ajax: {
            url: "/cari/nasabah",
            dataType: 'json',
            delay: 250,
            processResults: function (nasabah) {
                return {
                results:  $.map(nasabah, function (nas) {
                    return {
                    text: nas.nama + " - " + nas.ktp,
                    id: nas.id
                    }
                })
                };
            },
            cache: true
        }
    }); 
});

$('#los').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9,]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});

$("#los").on("input", function() {
  if (/^,/.test(this.value)) {
    this.value = this.value.replace(/^,/, "")
  }
  else if (/^0/.test(this.value)) {
    this.value = this.value.replace(/^0/, "")
  }
})

document
    .getElementById('diskonKeamananIpk')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );
document
    .getElementById('diskonKebersihan')
    .addEventListener(
        'input',
        event => event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US')
    );

// Radio Bebas Bayar
function radioAir() {
    if ($('#hanya_airbersih').is(':checked')) {
        document
            .getElementById('hanyaBayarAir')
            .style
            .display = 'block';
        document
            .getElementById('diskonBayarAir')
            .style
            .display = 'none';
    }
    else if ($('#dis_airbersih').is(':checked')) {
        document
            .getElementById('hanyaBayarAir')
            .style
            .display = 'none';
        document
            .getElementById('diskonBayarAir')
            .style
            .display = 'block';
    }
    else {
        document
            .getElementById('hanyaBayarAir')
            .style
            .display = 'none';
        document
            .getElementById('diskonBayarAir')
            .style
            .display = 'none';
    }
}
$('input[type="radio"]')
    .click(radioAir)
    .each(radioAir);

// Status Button
function statusTempat() {
    if ($('#myStatus2').is(':checked')) {
        document
            .getElementById('ketStatus')
            .style
            .display = 'block';
        document
            .getElementById('ket_tempat')
            .required = true;
    }
    else {
        document
            .getElementById('ketStatus')
            .style
            .display = 'none';
        document
            .getElementById('ket_tempat')
            .required = false;
    }
}
$('input[type="radio"]')
    .click(statusTempat)
    .each(statusTempat);

// Metode Pembayaran
function pembayaran() {
    if ($('#cicilan2').is(':checked')) {
        document
            .getElementById('ketCicil')
            .style
            .display = 'block';
        document
            .getElementById('ket_cicil')
            .required = true;
    }
    else {
        document
            .getElementById('ketCicil')
            .style
            .display = 'none';
        document
            .getElementById('ket_cicil')
            .required = false;
    }
}
$('input[type="radio"]')
    .click(pembayaran)
    .each(pembayaran);

// Fasilitas Button
function evaluate() {
    var item = $(this);
    var relatedItem = $("#" + item.attr("data-related-item")).parent();

    if (item.is(":checked")) {
        relatedItem.fadeIn();
    } else {
        relatedItem.fadeOut();
    }
}
$('input[type="checkbox"]')
    .click(evaluate)
    .each(evaluate);

// Checking
function checkAir() {
    if ($('#myCheck1').is(':checked')) {
        document
            .getElementById('myDiv1')
            .required = true;
    } else {
        document
            .getElementById('myDiv1')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkAir)
    .each(checkAir);

function checkListrik() {
    if ($('#myCheck2').is(':checked')) {
        document
            .getElementById('myDiv2')
            .required = true;
    } else {
        document
            .getElementById('myDiv2')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkListrik)
    .each(checkListrik);

function checkKeamananIpk() {
    if ($('#myCheck3').is(':checked')) {
        document
            .getElementById('myDiv3')
            .required = true;
    } else {
        document
            .getElementById('myDiv3')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkKeamananIpk)
    .each(checkKeamananIpk);


function checkKebersihan() {
    if ($('#myCheck4').is(':checked')) {
        document
            .getElementById('myDiv4')
            .required = true;
    } else {
        document
            .getElementById('myDiv4')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkKebersihan)
    .each(checkKebersihan);

function checkAirKotor() {
    if ($('#myCheck5').is(':checked')) {
        document
            .getElementById('myDiv5')
            .required = true;
    } else {
        document
            .getElementById('myDiv5')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkAirKotor)
    .each(checkAirKotor);

function checkLain() {
    if ($('#myCheck6').is(':checked')) {
        document
            .getElementById('myDiv6')
            .required = true;
    } else {
        document
            .getElementById('myDiv6')
            .required = false;
    }
}
$('input[type="checkbox"]')
    .click(checkLain)
    .each(checkLain);