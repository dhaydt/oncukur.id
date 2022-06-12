$(document).ready(function(){
    const collapseElementList = document.querySelectorAll('.collapse')
    const collapseList = [...collapseElementList].map(collapseEl => new bootstrap.Collapse(collapseEl))
})
async function alertHapus(title = null, text = null){
    konfirmasi = await Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    })
    return konfirmasi
}

function loading(action){
    if(action === true){
        $('#loading').addClass('d-none')
    }else{
        $('#loading').removeClass('d-none')
    }

    $('.modal').modal({backdrop: 'static', keyboard: false})
}

async function swalAlert(icon, message){
    var alert = await Swal.fire({
        text: message,
        icon: icon,
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    })

    return alert
}

function simpleLoading(action){
    console.log('testing');
    if(action == true){
        $('.simple-loading').removeClass('d-none')
    }else{
        $('.simple-loading').addClass('d-none')
    }
}

async function getKabupaten(id_provinsi){
    simpleLoading(true)
    const result = await $.ajax({
        type: "GET",
        url: "/data-master/daerah/get-kabupaten",
        data: {
            id_provinsi: id_provinsi
        },
        dataType: "json"
    });

    if(result.status == 1){
        var kabupaten = result.data.kabupaten
        $('select[name="id_kabupaten"]').html('<option value="">Pilih</option>')
        kabupaten.map((val, index) => {
            $('select[name="id_kabupaten"]').append(`<option value='${val.id}'>${val.nama_kabupaten}</option>`)
        })
        $('select[name="id_kabupaten"]').select2()
    }else{
        swalAlert('error', result.message)
    }
    simpleLoading(false)
    return result
}

async function getKecamatan(id_kabupaten){
    simpleLoading(true)
    const result = await $.ajax({
        type: "GET",
        url: "/data-master/daerah/get-kecamatan",
        data: {
            id_kabupaten: id_kabupaten
        },
        dataType: "json",
    });

    if(result.status == 1){
        var kecamatan = result.data.kecamatan
        $('select[name="id_kecamatan"]').html('<option value="">Pilih</option>')
        kecamatan.map((val, index) => {
            $('select[name="id_kecamatan"]').append(`<option value='${val.id}'>${val.nama_kecamatan}</option>`)
        })
        $('select[name="id_kecamatan"]').select2()
    }else{
        swalAlert('error', result.message)
    }
    simpleLoading(false)
    return result
}

async function getKelurahan(id_kecamatan){
    simpleLoading(true)
    const result = await $.ajax({
        type: "GET",
        url: "/data-master/daerah/get-kelurahan",
        data: {
            id_kecamatan: id_kecamatan
        },
        dataType: "json",
    });

    if(result.status == 1){
        var kelurahan = result.data.kelurahan
        $('select[name="id_kelurahan"]').html('<option value="">Pilih</option>')
        kelurahan.map((val, index) => {
            $('select[name="id_kelurahan"]').append(`<option value='${val.id}'>${val.nama_kelurahan}</option>`)
        })
        $('select[name="id_kelurahan"]').select2()
    }else{
        swalAlert('error', result.message)
    }
    simpleLoading(false)
    return result
}

async function getRW(id_kelurahan){
    simpleLoading(true)
    const result = await $.ajax({
        type: "GET",
        url: "/data-master/daerah/get-rw",
        data: {
            id_kelurahan: id_kelurahan
        },
        dataType: "json",
    });

    if(result.status == 1){
        var rw = result.data.rw
        $('select[name="id_rw"]').html('<option value="">Pilih</option>')
        rw.map((val, index) => {
            $('select[name="id_rw"]').append(`<option value='${val.id}'>${val.nama_rw}</option>`)
        })
        $('select[name="id_rw"]').select2()
    }else{
        swalAlert('error', result.message)
    }
    simpleLoading(false)
    return result
}

async function getRt(id_rw){
    simpleLoading(true)
    const result = await $.ajax({
        type: "GET",
        url: "/data-master/daerah/get-rt",
        data: {
            id_rw: id_rw
        },
        dataType: "json",
    });

    if(result.status == 1){
        var rt = result.data.rt
        $('select[name="id_rt"]').html('<option value="">Pilih</option>')
        rt.map((val, index) => {
            $('select[name="id_rt"]').append(`<option value='${val.id}'>${val.nama}</option>`)
        })
        $('select[name="id_rt"]').select2()
    }else{
        swalAlert('error', result.message)
    }

    simpleLoading(false)
    return result
}
