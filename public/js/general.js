
function check(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla === 8) {
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /[A-Za-z0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function checkNum(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla === 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /^([0-9]+\.?[0-9]{0,2})$/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function check_digit(e, obj, intsize, deczize) {
    var keycode;

    if (window.event) keycode = window.event.keyCode;
    else if (e) {
        keycode = e.which;
    } else {
        return true;
    }

    var fieldval = (obj.value),
        dots = fieldval.split(".").length;

    if (keycode == 46) {
        return dots <= 1;
    }
    if (keycode == 8 || keycode == 9 || keycode == 46 || keycode == 13) {
        // back space, tab, delete, enter
        return true;
    }
    if ((keycode >= 32 && keycode <= 45) || keycode == 47 || (keycode >= 58 && keycode <= 127)) {
        return false;
    }
    if (fieldval == "0" && keycode == 48) {
        return false;
    }
    if (fieldval.indexOf(".") != -1) {
        if (keycode == 46) {
            return false;
        }
        var splitfield = fieldval.split(".");
        if (splitfield[1].length >= deczize && keycode != 8 && keycode != 0)
            return false;
    } else if (fieldval.length >= intsize && keycode != 46) {
        return false;
    } else {
        return true;
    }
}

const SwalModal = (icon, title, html) => {
    Swal.fire({
        icon,
        title,
        html
    })
}
const SwalConfirmDeleteGeneral =  (icon, title,path) => {
     Swal.fire({
        icon,
        title,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Confirmar",
        reverseButtons: true,
    }).then( result => {
        if (result.value) {
            axios.delete(path).then((res)=> {
                    SwalAlert(res.data.status, res.data.message, 6000)
            });
        }

    })
}

const SwalConfirm = (icon, title, html, confirmButtonText, method, params, callback) => {
    Swal.fire({
        icon,
        title,
        html,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText,
        reverseButtons: true,
    }).then(result => {
        if (result.value) {
            return Livewire.emit(method, params)
        }
        if (callback) {
            return Livewire.emit(callback)
        }
    })
}

const SwalAlert = (icon, title, timeout = 7000) => {
    Swal.fire({
        position: 'center',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: timeout
    })
}

const ToastAlert = (icon, title, timeout = 7000) => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timeout,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: icon,
        title: title
    })
}

document.addEventListener('DOMContentLoaded', () => {

    this.livewire.on('swal:modal', data => {
        SwalModal(data.icon, data.title, data.text)
    })

    this.livewire.on('swal:confirm', data => {
        SwalConfirm(data.icon, data.title, data.text, data.confirmText, data.method, data.params, data.callback)
    })

    this.livewire.on('swal:alert', data => {
        SwalAlert(data.icon, data.title, data.timeout)
    })

    this.livewire.on('swal:list', data => {
        sweetAlertList(data)
    })
    this.livewire.on('swal:toast', data => {
        ToastAlert(data.icon, data.title, data.timeout)
    })
    this.livewire.on('modalClose', data => {
        $('#'+data).modal('hide');
    })
    this.livewire.on('modalOpen', data => {
        $('#'+data).modal('show');
    })
})

document.addEventListener('click', function(event) {
    if (event.target.classList[0] === 'unauthorized') {
        ToastAlert('error', 'Sin permisos',3000)
    }
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

var url=$("#routepath").val();
/* menu activo */
$('ul.nav-treeview a').filter(function() {
    return this.href == url;
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');


function translatorTitleCalendar(data){
    if (data==='Today'){
        return 'Hoy'
    }else if(data==='Yesterday'){
        return 'Ayer'

    }else if(data==='This month'){
        return 'Este Mes'

    }else if(data==='This year'){
        return 'Este Año'

    }else if(data==='Last month'){
        return 'Mes Pasado'

    }
}
