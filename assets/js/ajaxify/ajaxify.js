// global namespace
var $ = require('jquery');

$(document).ready(function () {

    FormAjaxify.common.init();

});



var FormAjaxify = FormAjaxify || {};
FormAjaxify.common = {

    _this: this,

    /**
     * Initializes form ajaxification
     */
    init: function () {

        $(document).on("submit", ".ajaxify_request", function (event) {

            event.preventDefault();

            FormAjaxify.common.handleRequest(this, event);

        });

    },

    /**
     *
     * @param _this
     * @param e
     */
    handleRequest: function (_this, e) {

        var formData = new FormData(_this);

        var submit_btn = $(_this).find('[type=submit]');
        var form_name = $(_this).attr('name');

        /**
         * Disable the submit button if needed
         */
        if ($(_this).data('disable-submit') !== undefined) {
            console.log('Button submitted');
            $(submit_btn).attr("disabled", true);
        } else {
            console.log('Submit not disabled');
        }

        /**
         * Call the onclick handler for the submit button if needed
         */
        if ($(_this).data('onclick_callback') !== undefined) {
            var fn = $(_this).data('onclick_callback');
            var _cb = FormAjaxify.common.getFunctionFromString(fn);
            _cb(_this, e);
        }


        $.ajax({
            url: $(_this).attr('action'),
            type: $(_this).attr('method'),
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if ($(_this).data('callback') !== undefined) {

                    var fn = $(_this).data('callback');
                    var _cb = FormAjaxify.common.getFunctionFromString(fn);
                    return _cb(data, e, _this);


                } else {

                    FormAjaxify.common.genericCallback(data);

                }


            },
            error: function (xhr) {

                if ($(_this).data('callback') !== undefined) {

                    var fn = $(_this).data('callback');
                    var _cb = FormAjaxify.common.getFunctionFromString(fn);
                    var _d = jQuery.parseJSON(xhr.responseText);
                    _cb(_d, e, _this);


                } else {

                    var _d = jQuery.parseJSON(xhr.responseText);
                    FormAjaxify.common.genericCallback(_d);


                }


            }
        });
    },

    /**
     *
     * @param string
     * @returns {*}
     */
    getFunctionFromString: function (string) {
        var scope = window;
        var scopeSplit = string.split('.');
        for (var i = 0; i < scopeSplit.length - 1; i++) {
            scope = scope[scopeSplit[i]];

            if (scope === undefined) return;
        }

        return scope[scopeSplit[scopeSplit.length - 1]];
    },

    /**
     * Generic callback method
     * @param validation_errors_array
     */
    genericCallback: function (response) {

        alert(response.data.message);

    }


};

