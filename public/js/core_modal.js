var modal = null;

function Modal() {
    console.debug("Modal instancied");
    this.init();
}

function notifyError(jqXHR, exception)
{
    if (jqXHR.status === 0) {
        return ('Not connected.\nPlease verify your network connection.');
    } else if (jqXHR.status == 401) {
        return ('Error. [401]');
    } else if (jqXHR.status == 403) {
        return ('Permission restricted. [403]');
    } else if (jqXHR.status == 404) {
        return ('The requested page not found. [404]');
    } else if (jqXHR.status == 500) {
        return ('Internal Server Error [500].');
    } else if (exception === 'parsererror') {
        return ('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        return ('Time out error.');
    } else if (exception === 'abort') {
        return ('Ajax request aborted.');
    } else {
        return ('Uncaught Error.\n' + jqXHR.responseText);
    }
}

function manageError(id, idModal, type) {
    if ((type != 401) && (type != 403) && (type != 404) && (type != 500))
        type = 500;
    var rel = 'id=' + id + '&error=' + type;
    var ajax = $.ajax({
        url: '/ui/modal/error',
        data: rel,
        type: 'POST',
        dataType: 'JSON',
        timeout: 50000,
        success: function (data) {
            manageModal(idModal, 'modal', data.html);
        },
    });
}

function manageModal(idModal, findClass, data) {
    if ($(idModal).hasClass(findClass)) {
        $(idModal).remove();
    }
    if (!$(idModal).hasClass(findClass)) {
        $("body").append(data);
        $(idModal).modal("show");
    }
}

Modal.prototype.afterModal = function (rel, idModal) {
    $(idModal).find(".confirm").unbind();
    $(idModal).find(".confirm").click(function () {

        var data_link = "";
        var form_modal = "";
        if ($(idModal).find("#form_modal").length) {
            form_modal = $(idModal).find("#form_modal").serialize();
        }

        if (form_modal == "")
            data_link = $.param(rel);
        else
            data_link = form_modal + "&id=" + rel["id"];

        if (rel["param"] != undefined) {
            param = $.param(rel["param"]);
            data_link = data_link + "&" + param;
        }

        var ajax = $.ajax({
            url: rel["url_confirm"],
            data: data_link,
            type: 'POST',
            dataType: 'JSON',
            timeout: 50000,
            success: function (data) {
                if (data.action >= 0) {
                    if (rel["url_redir"] != "") {
                        document.location.href = rel["url_redir"];
                        if ($(idModal).hasClass('modal')) {
                            $(idModal).remove();
                        }
                    }
                }
                else {
                    manageModal(idModal, 'modal', data.html);
                }
            },
            error: function (jqXHR, exception) {
                // console.debug(notifyError(jqXHR, exception));
                manageError(rel['id'], idModal, jqXHR.status);
            },
        });
        return false;
    });

    $(".close").click(function () {
        $(idModal).remove();
    });

    $(".noconfirm").click(function () {
        $(idModal).remove();
    });
};
/**
 * @constructor 
 * @returns {undefined}
 */
Modal.prototype.init = function () {
    var modal = this;
    $(".smodal").click(function () {
        var rel = $.parseJSON($(this).attr("data-modal"));
        if (rel != "") {
            // console.debug(rel);
            idModal = "#modal_" + rel["id"];
            var ajax = $.ajax({
                url: rel["url_info"],
                data: $.param(rel),
                type: 'POST',
                dataType: 'JSON',
                timeout: 50000,
                success: function (data) {
                    manageModal(idModal, 'modal', data.html);
                    modal.afterModal(rel, idModal);
                },
                error: function (jqXHR, exception) {
                    // console.debug(notifyError(jqXHR, exception));
                    manageError(rel['id'], idModal, jqXHR.status);
                },
            });
        }
        return false;
    });
};

$(document).ready(function () {
    modal = new Modal();
});

